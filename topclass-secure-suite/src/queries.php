<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Valida si un usuario compró un producto de WooCommerce o es administrador.
 */
function topclass_user_has_access_to_product($user_id, $product_id) {

    $user  = get_userdata($user_id);   
    $email = $user ? $user->user_email : '';
    $post = get_post($product_id);
    $user_vendor_id = get_user_meta($user_id, '_wcpv_active_vendor', true);
    $user_access = ['access'=>false, 'role'=>''];
    
    if (current_user_can('administrator')) {
        $user_access = ['access'=>true, 'role'=>'admin'];
        return $user_access;
    }

    if (!$user_id || !$product_id) {
        return $user_access;
    }

    if (function_exists('wc_customer_bought_product') && wc_customer_bought_product($email, $user_id, $product_id)) {
        $user_access = ['access'=>true, 'role'=>'client'];
        return $user_access;
    }

    if (!$post) {
        return $user_access;
    }

    if ((int) $post->post_author === (int) $user_id) {
        $user_access = ['access'=>true, 'role'=>'author'];
        return $user_access;
    }
    
    if ($user_vendor_id) {
        $product_vendors = wp_get_post_terms($product_id, 'wcpv_product_vendors', array('fields' => 'ids'));
        
        if (!is_wp_error($product_vendors) && in_array((int)$user_vendor_id, array_map('intval', $product_vendors), true)) {
            $user_access = ['access'=>true, 'role'=>'vendor'];
            return $user_access;
        }
    }
    

    return $user_access;
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

function tc_get_product_meta($product_id) {
    $config = include __DIR__ . '/config.php';
    $cloudfront_domain = rtrim($config['cloudfront_domain'], '/');
    $post = get_post($product_id);
    $title = get_the_title($product_id);
    $author_id = $post->post_author;
    $author = get_the_author_meta('user_firstname' , $post->post_author) . ' ' . get_the_author_meta('user_lastname' , $post->post_author);
    $date = get_the_date('j-n-Y',$product_id);
    $s3_path = topclass_get_product_s3_path($product_id);
    $resource_url = $cloudfront_domain . '/' . ltrim($s3_path, '/');
    $attachment_id = attachment_url_to_postid($resource_url);
    $meta = wp_get_attachment_metadata($attachment_id);
    $duration = isset($meta['length']) ? floatval($meta['length']) : 0;
    $width  = isset($meta['width']) ? intval($meta['width']) : 1280;
    $height = isset($meta['height']) ? intval($meta['height']) : 720;
    $resolution = $width . 'x' . $height;
    $orientation = ($width > $height)? 'landscape' : 'portrait';
    
    $meta_data = ['title'=>$title, 'author'=>$author, 'date'=>$date, 'width'=>$width, 'height'=>$height, 'duration'=>$duration, 'orientation'=>$orientation, 'resolution'=>$resolution];
    return $meta_data;
}



