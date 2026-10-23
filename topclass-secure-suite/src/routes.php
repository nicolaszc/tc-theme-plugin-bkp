<?php
if (!defined('ABSPATH')) {
    exit;
}



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

    // -------------------------------------------------------------
    // AQUÍ ESTÁ EL CAMBIO CLAVE: SETEAMOS LAS SIGNED COOKIES DE CLOUDFRONT
    // -------------------------------------------------------------
    $config = include __DIR__ . '/config.php';
    $cloudfront_domain = rtrim($config['cloudfront_domain'], '/');
    
    // Definimos el recurso wildcard que cubrirá toda la carpeta (ej: https://tudominio.com/curso/*)
    $path_info = pathinfo($s3_path);
    $dir_path  = trim($path_info['dirname'], '/');
    $wildcard_resource = $cloudfront_domain . '/' . ($dir_path ? $dir_path . '/' : '') . '*';

    // Duración amplia para que no exprese durante la reproducción (ej: 4 horas)
    $expires = time() + 14400; 

    // Llamamos a tu nueva función de helper para despachar las cookies al navegador
    topclass_set_cloudfront_signed_cookies($wildcard_resource, $expires);

    // -------------------------------------------------------------
    // PREPARACIÓN DE DATOS PARA LOS MANIFIESTOS
    $filename  = $path_info['filename'];           
    $base_s3_hls_folder = $cloudfront_domain . '/' . $dir_path . '/' . $filename . '_hls/' . $filename;

    $post_meta = tc_get_product_meta($product_id);
    $original_duration = 0;
    if ($post_meta) {
        $original_duration = $post_meta['duration'];
        $resolution = $post_meta['resolution'];
    }
    if ($original_duration <= 0) {
        $original_duration = 60.0; 
    }

    $content_duration = $original_duration + $config['intros_duration'];

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
            "#EXT-X-VERSION:" . $config['version'],
            "#EXT-X-INDEPENDENT-SEGMENTS",
            '#EXT-X-STREAM-INF:BANDWIDTH=' . $config['bandwidth'] . ',AVERAGE-BANDWIDTH=' . $config['average_bandwidth'] . ',VIDEO-RANGE=SDR,CODECS="' . $config['codec'] . '",RESOLUTION=' . $resolution . ',FRAME-RATE=30.000,AUDIO="main"',
            $video_endpoint,
            '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="main",LANGUAGE="eng",NAME="main",DEFAULT=YES,AUTOSELECT=YES,LANGUAGE="es",CHANNELS="2",URI="' . $audio_endpoint . '"'
        );

        echo implode("\n", $lines);
        exit;
    }

    $target_duration = $config['target_duration'];
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
    // LOOP MANIFIESTO DE VIDEO & AUDIO (¡URLs 100% limpias de firmas!)
    // -------------------------------------------------------------
    if ($filetype === 'video' || $filetype === 'audio') {
        $lines = array(
            "#EXTM3U",
            "#EXT-X-VERSION:" . $config['version'],
            "#EXT-X-TARGETDURATION:" . $target_duration,
            "#EXT-X-MEDIA-SEQUENCE:" . $config['sequence'],
            "#EXT-X-PLAYLIST-TYPE:VOD",
            '#EXT-X-MAP:URI="' . $base_s3_hls_folder . $file_nameModifier . 'init' . $file_extension . '"',
        );

        for ($i = 0; $i < $total_chunks; $i++) {
            $duration = ($i === $total_chunks - 1) ? ($content_duration - ($i * $target_duration)) : $target_duration;
            if ($duration <= 0) $duration = $target_duration;
            $segment_num = sprintf('%09d', $i + 1);
            $chunk_name = sprintf($base_s3_hls_folder . $file_nameModifier . '_' . $segment_num . $file_extension, $i);
            
            // ¡Se acabaron los query params de firma en los chunks!
            $lines[] = "#EXTINF:" . $duration . ',';
            $lines[] = $chunk_name;
        }
        $lines[] = "#EXT-X-ENDLIST";

        echo implode("\n", $lines);
        exit;
    }

    status_header(404);
    echo 'Not Found';
    exit;
}
