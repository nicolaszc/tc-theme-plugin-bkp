<?php
/**
 * Plugin Name: TopClass Secure CloudFront Suite
 * Description: Suite modular para la gestión y entrega segura de contenido privado mediante CloudFront y WooCommerce.
 * Version: 3.0
 * Author: TopClass
 */

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

// Definir constantes del plugin
define('TOPCLASS_SUITE_PATH', plugin_dir_path(__FILE__) . 'src/');

// Cargar componentes de forma modular
require_once TOPCLASS_SUITE_PATH . 'config.php';
require_once TOPCLASS_SUITE_PATH . 'helpers.php';
require_once TOPCLASS_SUITE_PATH . 'queries.php';
require_once TOPCLASS_SUITE_PATH . 'routes.php';
require_once TOPCLASS_SUITE_PATH . 'shortcodes.php';

// 1. Registrar el endpoint en WooCommerce
add_action('init', function() {
    add_rewrite_endpoint('content', EP_ROOT | EP_PAGES);
});

// 2. Interceptar visitas sin sesión en el endpoint de contenido
add_action('template_redirect', function() {
    if (is_account_page() && isset($_GET['product_id']) && !is_user_logged_in()) {
        // En lugar de guardar la URL del video, guardamos la URL de descargas como destino
        $downloads_url = wc_get_account_endpoint_url('downloads');
        $login_url = add_query_arg('redirect_to', urlencode($downloads_url), wc_get_page_permalink('myaccount'));
        
        wp_safe_redirect($login_url);
        exit;
    }
});

// 3. Redirigir según el origen tras el inicio de sesión
add_filter('woocommerce_login_redirect', function($redirect, $user) {
    if (in_array('administrator', $user->roles)) {
        return $redirect;
    }

    // Si viene con una redirección guardada (porque intentó entrar a ver un contenido), va a descargas
    if (isset($_REQUEST['redirect_to']) && !empty($_REQUEST['redirect_to'])) {
        return esc_url_raw($_REQUEST['redirect_to']);
    }

    // Si entró a loguearse por voluntad propia (sin venir de un contenido), va al escritorio/dashboard
    return wc_get_account_endpoint_url('dashboard');
}, 10, 2);


// 4. Renderizar el contenido (reproductor) dentro de Mi Cuenta cuando ya está dentro
add_action('woocommerce_account_content_endpoint', function() {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    
    if (!is_user_logged_in()) {
        echo '<p>Debes iniciar sesión para ver este contenido.</p>';
        return;
    }

    if ($product_id > 0) {
        echo do_shortcode('[topclass_secure_content product_id="' . $product_id . '" type="video"]');
    } else {
        echo '<p>Por favor selecciona un contenido válido desde tus descargas.</p>';
    }
});





