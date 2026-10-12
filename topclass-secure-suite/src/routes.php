<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function() {
    if (isset($_GET['topclass_action']) && $_GET['topclass_action'] === 'get_stream_url') {
        topclass_handle_custom_stream_request();
    }
});

function topclass_handle_custom_stream_request() {
    header('Content-Type: application/json; charset=UTF-8');

    if (!is_user_logged_in()) {
        echo json_encode(array('success' => false, 'message' => 'No autorizado. Inicia sesión.'));
        exit;
    }

    $user_id    = get_current_user_id();
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

    if (!$product_id) {
        echo json_encode(array('success' => false, 'message' => 'ID de producto no válido.'));
        exit;
    }

    if (!topclass_user_has_access_to_product($user_id, $product_id)) {
        echo json_encode(array('success' => false, 'message' => 'No tienes una orden válida para este contenido.'));
        exit;
    }

    $s3_path = topclass_get_product_s3_path($product_id);
    if (empty($s3_path)) {
        echo json_encode(array('success' => false, 'message' => 'El producto no tiene configurada una ruta privada de contenido.'));
        exit;
    }

    $config     = include __DIR__ . '/config.php';
    $resource_url = $config['cloudfront_domain'] . '/' . ltrim($s3_path, '/');
    $expires      = time() + 7200; // 2 horas

    $signed_url = topclass_generate_cloudfront_signed_url($resource_url, $expires);

    if (!$signed_url) {
        echo json_encode(array('success' => false, 'message' => 'Error al generar la firma criptográfica con OpenSSL.'));
        exit;
    }

    echo json_encode(array(
        'success'    => true,
        'stream_url' => $signed_url,
        'expires_at' => $expires
    ));
    exit;
}
