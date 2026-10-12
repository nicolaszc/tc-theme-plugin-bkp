<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Valida si un usuario compró un producto de WooCommerce o es administrador.
 */
function topclass_user_has_access_to_product($user_id, $product_id) {
    if (current_user_can('administrator')) {
        return true;
    }

    if (!$user_id || !$product_id) {
        return false;
    }

    $user  = get_userdata($user_id);
    $email = $user ? $user->user_email : '';

    if (function_exists('wc_customer_bought_product') && wc_customer_bought_product($email, $user_id, $product_id)) {
        return true;
    }

    return false;
}

/**
 * Obtiene la ruta del archivo utilizando el sistema nativo de 
 * "Productos Descargables" de WooCommerce (_downloadable_files).
 */
function topclass_get_product_s3_path($product_id) {
    // Obtenemos los archivos descargables nativos de WooCommerce
    $downloads = get_post_meta($product_id, '_downloadable_files', true);

    if (empty($downloads) || !is_array($downloads)) {
        return '';
    }

    // Tomamos el primer archivo de la lista descargable (o el que necesites)
    $file = reset($downloads);
    
    if (isset($file['file'])) {
        // Retornamos la ruta o URL configurada en el producto descargable
        // Si guardas la ruta relativa (ej. /videos/clase1.mp4), la devolvemos limpia
        $file_url = $file['file'];
        
        // Si pegaron la URL completa de CloudFront por error, extraemos solo la ruta relativa
        $parsed = parse_url($file_url, PHP_URL_PATH);
        return $parsed ? $parsed : $file_url;
    }

    return '';
}

// 1. Para el panel "Mi Cuenta -> Descargas"
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

// 2. Para los correos de "Pedido completado"
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


