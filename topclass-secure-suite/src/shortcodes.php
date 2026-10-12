<?php
if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('topclass_secure_content', function($atts) {
    $atts = shortcode_atts(array(
        'product_id' => 1183,
        'type'       => 'video', // video, image, pdf
        'path'       => '',      // Opcional si usa _private_s3_path del producto
        'text'       => 'Descargar Documento PDF seguro'
    ), $atts);

    $product_id = intval($atts['product_id']);
    $type       = sanitize_text_field($atts['type']);
    $file_path  = sanitize_text_field($atts['path']);
    $link_text  = sanitize_text_field($atts['text']);
    $user_id    = get_current_user_id();

    // Validar acceso mediante la capa de queries
    if (!topclass_user_has_access_to_product($user_id, $product_id)) {
        return '<p style="color: red;">Debes comprar este producto o iniciar sesión para ver este contenido.</p>';
    }

    // Si no se pasó un path directo, se busca en los metadatos del producto
    if (empty($file_path)) {
        $s3_path = topclass_get_product_s3_path($product_id);
        if (!empty($s3_path)) {
            $file_path = '/' . ltrim($s3_path, '/');
        } else {
            return '<p style="color: red;">Error: No se encontró una ruta configurada para este contenido.</p>';
        }
    }

    $config       = include __DIR__ . '/config.php';
    $resource_url = $config['cloudfront_domain'] . $file_path;
    $expires      = time() + 3600; // 1 hora

    $signed_url = topclass_generate_cloudfront_signed_url($resource_url, $expires);

    if (!$signed_url) {
        return '<p style="color: red;">Error al generar la URL firmada de CloudFront.</p>';
    }

    // Renderizado HTML según el tipo
    ob_start();
    
    switch ($type) {
        case 'image':
            ?>
            <div class="topclass-secure-image-wrapper" style="margin: 15px 0;">
                <img src="<?php echo esc_url($signed_url); ?>" alt="Contenido Protegido" style="max-width: 100%; height: auto;" />
            </div>
            <?php
            break;

        case 'pdf':
            ?>
            <div class="topclass-secure-pdf-wrapper" style="margin: 15px 0;">
                <a href="<?php echo esc_url($signed_url); ?>" target="_blank" rel="noopener" class="button topclass-pdf-btn">
                    <?php echo esc_html($link_text); ?>
                </a>
            </div>
            <?php
            break;

        case 'video':
        default:
            ?>
            <div class="topclass-secure-video-wrapper" style="margin: 15px 0;">
                <video controls width="100%" preload="metadata">
                    <source src="<?php echo esc_url($signed_url); ?>" type="video/mp4">
                    Tu navegador no soporta el elemento de video.
                </video>
            </div>
            <?php
            break;
    }

    return ob_get_clean();
});
