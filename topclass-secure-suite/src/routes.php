<?php
if (!defined('ABSPATH')) {
    exit;
}

// Permitir autenticación por cookie en nuestra ruta REST específica sin requerir Nonce
add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/wp-json/topclass/v1/stream/') !== false) {
        if (is_user_logged_in()) {
            return true;
        }
    }
    return $result;
});

// Registrar la ruta REST limpia (sin extensiones en la URL)
add_action('rest_api_init', function () {
    register_rest_route('topclass/v1', '/stream/(?P<product_id>\d+)/(?P<filetype>[a-zA-Z0-9_-]+)', array(
        'methods' => 'GET',
        'callback' => 'topclass_virtual_manifest_endpoint',
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ));
});

function topclass_virtual_manifest_endpoint($request) {
    $product_id = intval($request['product_id']);
    $filetype   = sanitize_text_field($request['filetype']); // 'master', 'video' o 'audio'
    
    if (!is_user_logged_in()) {
        status_header(401);
        echo 'No autorizado. Inicia sesión.';
        exit;
    }

    $user_id = get_current_user_id();

    if (!topclass_user_has_access_to_product($user_id, $product_id)) {
        status_header(403);
        echo 'No tienes una orden válida para este contenido.';
        exit;
    }

    $s3_path = topclass_get_product_s3_path($product_id);
    if (empty($s3_path)) {
        status_header(404);
        echo 'El producto no tiene configurada una ruta de contenido.';
        exit;
    }

    $path_info = pathinfo($s3_path);
    $filename  = $path_info['filename'];           
    $dir_path  = trim($path_info['dirname'], '/'); 

    $config = include __DIR__ . '/config.php';
    $cloudfront_domain = rtrim($config['cloudfront_domain'], '/');
    $base_s3_hls_folder = $cloudfront_domain . '/' . $dir_path . '/' . $filename . '_hls/' . $filename;

    $resource_url = $cloudfront_domain . '/' . ltrim($s3_path, '/');
    $attachment_id = attachment_url_to_postid($resource_url);
    $original_duration = 0;
    if ($attachment_id) {
        $meta = wp_get_attachment_metadata($attachment_id);
        $original_duration = isset($meta['length']) ? floatval($meta['length']) : 0;
        $original_resolution = isset($meta['resolution']) ? $meta['resolution'] : '';
    }
    if ($original_duration <= 0) {
        $original_duration = 60.0; 
    }

    $content_duration = $original_duration + 10;
    $expires = time() + 60;

    // Cabeceras estrictas de HLS en texto plano puro
    header('Content-Type: application/vnd.apple.mpegurl; charset=UTF-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    status_header(200);

    // -------------------------------------------------------------
    // CASO 1: MANIFIESTO MASTER
    // -------------------------------------------------------------
    if ($filetype === 'master') {
        $video_endpoint = rest_url('topclass/v1/stream/' . $product_id . '/video');
        $audio_endpoint = rest_url('topclass/v1/stream/' . $product_id . '/audio');

        $lines = array(
            "#EXTM3U",
            "#EXT-X-VERSION:6",
            "#EXT-X-INDEPENDENT-SEGMENTS",
            '#EXT-X-STREAM-INF:BANDWIDTH=834408,AVERAGE-BANDWIDTH=535245,VIDEO-RANGE=SDR,CODECS="avc1.64001f",RESOLUTION=720x1280,FRAME-RATE=30.000,AUDIO="main"',
            $video_endpoint,
            '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="main",LANGUAGE="eng",NAME="main",DEFAULT=YES,AUTOSELECT=YES,LANGUAGE="es",CHANNELS="2",URI="' . $audio_endpoint . '"'
        );

        echo implode("\n", $lines);
        exit;
    }

    // Generar firma wildcard
    $remote_wildcard_url = $base_s3_hls_folder . '*';
    $signed_wildcard_url = topclass_generate_cloudfront_signed_url($remote_wildcard_url, $expires, true);
    $query_params = '';
    if ($signed_wildcard_url) {
        $parsed_wildcard = parse_url($signed_wildcard_url);
        $query_params = isset($parsed_wildcard['query']) ? $parsed_wildcard['query'] : '';
    }

    $target_duration = 6;
    $total_chunks = (int) ceil($content_duration / $target_duration);
    if ($total_chunks < 1) $total_chunks = 1;

    // -------------------------------------------------------------
    // CASO 2: MANIFIESTO DE VIDEO
    // -------------------------------------------------------------
    if ($filetype === 'video') {
        $file_nameModifier = '-video';
        $file_extension = '.cmfv';
    }
    // -------------------------------------------------------------
    // CASO 3: MANIFIESTO DE AUDIO
    // -------------------------------------------------------------
    if ($filetype === 'audio') {
        $file_nameModifier = '-audio';
        $file_extension = '.cmfa';
    }

    // -------------------------------------------------------------
    // LOOP MANIFIESTO DE VIDEO & AUDIO
    // -------------------------------------------------------------
    if ($filetype === 'video' || $filetype === 'audio') {
        $lines = array(
            "#EXTM3U",
            "#EXT-X-VERSION:6",
            "#EXT-X-TARGETDURATION:" . $target_duration,
            "#EXT-X-MEDIA-SEQUENCE:1",
            "#EXT-X-PLAYLIST-TYPE:VOD",
            '#EXT-X-MAP:URI="' . $base_s3_hls_folder . $file_nameModifier . 'init' .$file_extension . '?' . $query_params . '"',
        );

        for ($i = 0; $i < $total_chunks; $i++) {
            $duration = ($i === $total_chunks - 1) ? ($content_duration - ($i * $target_duration)) : $target_duration;
            if ($duration <= 0) $duration = $target_duration;
            $segment_num = sprintf('%09d', $i + 1);
            $chunk_name = sprintf($base_s3_hls_folder . $file_nameModifier . '_' . $segment_num . $file_extension, $i);
            $lines[] = "#EXTINF:" . $duration . ',';
            $lines[] =  $chunk_name . '?' . $query_params;
        }
        $lines[] = "#EXT-X-ENDLIST";

        echo implode("\n", $lines);
        exit;
    }

    status_header(404);
    echo 'Not Found';
    exit;
}
