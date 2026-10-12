<?php
/**
 * Plugin Name: TopClass Secure Content CloudFront
 * Description: Generador de URLs firmadas para CloudFront con validación de WooCommerce y shortcode multipropósito (video, imagen, pdf).
 * Version: 2.0
 * Author: TopClass
 */

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

/**
 * Función centralizada para generar la URL firmada de CloudFront usando OpenSSL.
 */
function topclass_generate_cloudfront_signed_url($resource_url, $expires_timestamp) {
    // Rutas y credenciales
    $private_key_path = '/var/www/trans.topclassclub.com/private_keys/pk-K3DIY62GDW2JSC.pem'; 
    $key_pair_id      = 'K3DIY62GDW2JSC';

    if (!file_exists($private_key_path)) {
        return 'Error: La llave privada no existe en la ruta especificada.';
    }

    $private_key = file_get_contents($private_key_path);
    
    // Política de acceso para CloudFront
    $json_policy = '{"Statement":[{"Resource":"' . $resource_url . '","Condition":{"DateLessThan":{"AWS:EpochTime":' . $expires_timestamp . '}}}]}';

    // Firmar la política usando OpenSSL SHA1
    $signature = '';
    $success = openssl_sign($json_policy, $signature, $private_key, OPENSSL_ALGO_SHA1);

    if (!$success) {
        return 'Error: No se pudo generar la firma criptográfica con OpenSSL.';
    }

    // Limpiar la firma para que sea amigable con URLs
    $encoded_policy    = base64_encode($json_policy);
    $encoded_signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($signature));
    $encoded_policy    = str_replace(array('+', '=', '/'), array('-', '_', '~'), $encoded_policy);

    // Retornar la URL final con los parámetros de seguridad de CloudFront
    return $resource_url . '?Policy=' . $encoded_policy . '&Signature=' . $encoded_signature . '&Key-Pair-Id=' . $key_pair_id;
}

/**
 * Shortcode [topclass_secure_content]
 * Atributos:
 * - product_id: ID del producto en WooCommerce (por defecto 1183)
 * - type: Tipo de contenido ('video', 'image', 'pdf')
 * - path: Ruta del archivo dentro de CloudFront (ej. '/videos/clase1.mp4')
 * - text: Texto personalizado para el enlace (solo aplica para 'pdf')
 */
add_shortcode('topclass_secure_content', function($atts) {
    $atts = shortcode_atts(array(
        'product_id' => 1183,
        'type'       => 'video', // video, image, pdf
        'path'       => '',      // Ruta relativa del archivo en el CDN
        'text'       => 'Descargar Documento PDF seguro'
    ), $atts);

    $product_id = intval($atts['product_id']);
    $type       = sanitize_text_field($atts['type']);
    $file_path  = sanitize_text_field($atts['path']);
    $link_text  = sanitize_text_field($atts['text']);
    $user_id    = get_current_user_id();

    // Validar si el usuario compró el producto en WooCommerce
    $has_bought = false;
    if ($user_id && function_exists('wc_customer_bought_product')) {
        $user = get_userdata($user_id);
        $email = $user ? $user->user_email : '';
        if (wc_customer_bought_product($email, $user_id, $product_id)) {
            $has_bought = true;
        }
    }

    // (Opcional para pruebas) Descomenta la siguiente línea si quieres saltarte la compra:
    // $has_bought = true;

    if (!$has_bought && !current_user_can('administrator')) {
        return '<p style="color: red;">Debes comprar este producto o iniciar sesión para ver este contenido.</p>';
    }

    // Si no se pasó una ruta por atributo, puedes definir rutas por defecto según el ID o tipo
    if (empty($file_path)) {
        if ($product_id == 1183) {
            $file_path = '/ruta/al/video/archivo.mp4'; // Tu ruta por defecto anterior
        } else {
            return '<p style="color: red;">Error: No se especificó la ruta del archivo.</p>';
        }
    }

    // Definir la URL base de CloudFront
    $cloudfront_domain = 'https://cdn.topclassclub.com'; 
    $resource_url      = $cloudfront_domain . $file_path;

    // Tiempo de expiración (ej. 1 hora a partir de ahora)
    $expires = time() + 3600;

    // Generar la URL firmada
    $signed_url = topclass_generate_cloudfront_signed_url($resource_url, $expires);

    // Renderizar según el tipo solicitado
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
