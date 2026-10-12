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
 * Obtiene la ruta privada S3 asociada a un producto de WooCommerce.
 */
function topclass_get_product_s3_path($product_id) {
    return get_post_meta($product_id, '_private_s3_path', true);
}
