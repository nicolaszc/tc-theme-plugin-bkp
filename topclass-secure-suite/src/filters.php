<?php

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
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

// Registrar el endpoint en WooCommerce
add_action('init', function() {
    add_rewrite_endpoint('content', EP_ROOT | EP_PAGES);
});

// Interceptar visitas sin sesión en el endpoint de contenido
add_action('template_redirect', function() {
    if (is_account_page() && isset($_GET['product_id']) && !is_user_logged_in()) {
        // En lugar de guardar la URL del video, guardamos la URL de descargas como destino
        $downloads_url = wc_get_account_endpoint_url('downloads');
        $login_url = add_query_arg('redirect_to', urlencode($downloads_url), wc_get_page_permalink('myaccount'));
        
        wp_safe_redirect($login_url);
        exit;
    }
});

// Redirigir según el origen tras el inicio de sesión
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


// Renderizar el contenido (reproductor) dentro de Mi Cuenta cuando ya está dentro
add_action('woocommerce_account_content_endpoint', function() {
    tc_insert_shortcode('client');
});

// Renderizar el contenido (reproductor) dentro de producto para preview de admin y vendor
add_action('woocommerce_before_single_product_summary', function(){
    tc_insert_shortcode('admin');
});

// Para el panel "Mi Cuenta -> Descargas"
add_filter('woocommerce_customer_available_downloads', function($downloads) {
    if (empty($downloads)) {
        return $downloads;
    }

    foreach ($downloads as $key => $download) {
        $product_id = isset($download['product_id']) ? intval($download['product_id']) : 0;
        
        if ($product_id > 0) {
            // Apuntamos al nuevo endpoint de la cuenta
            $downloads[$key]['download_url'] = wc_get_account_endpoint_url('content') . '?product_id=' . $product_id;
        }
    }

    return $downloads;
});

// Para los correos de "Pedido completado"
add_filter('woocommerce_order_get_downloadable_items', function($items, $order) {
    if (empty($items)) {
        return $items;
    }

    foreach ($items as $key => $item) {
        $product_id = isset($item['product_id']) ? intval($item['product_id']) : 0;
        
        if ($product_id > 0) {
            // Apuntamos al nuevo endpoint de la cuenta también en los mails
            $items[$key]['download_url'] = wc_get_account_endpoint_url('content') . '?product_id=' . $product_id;
        }
    }

    return $items;
}, 10, 2);