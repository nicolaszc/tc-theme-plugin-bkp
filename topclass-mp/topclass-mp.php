<?php
/*
Plugin Name:  Topclass MP
Plugin URI:   http:/topclass.club
Description:  Topclass MP WordPress Plugin
Version:      1.0
Author:       Nicolás Zakrzewicz
Author URI:   http:/topclass.club
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
    exit;
}

// Add vendors fieds to vendors settings
// 1. Ensure acf_form_head runs early
add_action('admin_head', 'acf_form_head');
// Vendor styles
/* function vendor_admin_styles(){
    wp_enqueue_style( 'admin-style', plugins_url( '/vendor.css' , __FILE__ ));

}
add_action('admin_head', 'vendor_admin_styles'); */

function vendor_admin_script(){
    wp_enqueue_style( 'admin-style', plugins_url( '/vendor.css' , __FILE__ ));

    wp_enqueue_script( 'admin-script', plugins_url( '/admin-script.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
}

add_action('admin_enqueue_scripts', 'vendor_admin_script');

add_action('admin_init', function() {
    global $vendor_id, $vendor_term, $vendor_name;

    $vendor_id = get_current_user_id();
    //$vendor_term = get_user_meta($vendor_id, '_wcpv_active_vendor', true);

    $vendor_user_object = get_user_by( 'id', $vendor_id );
    $vendor_name = $vendor_user_object->user_nicename;
});

// Función para renombrar imagen al subir y actualizar ACF
function topclass_rename_vendor_image_on_upload( $value, $post_id, $field ) {
    if ( ! $value || ! is_numeric( $value ) ) {
        return $value;
    }

    $fallback_name = 'TopClass' . $GLOBALS['current_site_cat'];
    $attachment_id = $value;
    $file = get_attached_file( $attachment_id );
    if ( ! file_exists( $file ) ) return $value;

    $alias = $GLOBALS['vendor_name'] ?? $fallback_name;

    $pathinfo = pathinfo( $file );
    $ext = $pathinfo['extension'];
    $dirname = $pathinfo['dirname'];

    $timestamp = date( 'Ymd-His' );
    $new_filename = "{$alias}-{$timestamp}.{$ext}";
    $new_file_path = $dirname . '/' . $new_filename;

    if ( rename( $file, $new_file_path ) ) {
        update_attached_file( $attachment_id, $new_file_path );

        wp_update_post([
            'ID' => $attachment_id,
            'post_title' => $alias,
            'post_name'  => $alias,
        ]);

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        wp_generate_attachment_metadata( $attachment_id, $new_file_path );

        // Actualizar el campo ACF para que quede con la URL del tamaño optimizado
        $size = 'custom-card-avatar'; // Cambiar por el tamaño que uses
        $image_src_array = wp_get_attachment_image_src( $attachment_id, $size );
        if ( $image_src_array ) {
            update_field( $field['name'], $image_src_array[0], $post_id );
        }
        
        // Usar la variable global vendor_id que tenés disponible
        $user_id = $GLOBALS['vendor_id'] ?? 0;

        if ($user_id) {
            // Guardar en user meta la ID del attachment como avatar personalizado
            update_user_meta( $user_id, 'wp_user_avatar', $attachment_id );
            // También podés guardar una URL directa, pero con ID es mejor para WP
        }
    }
  
    return $attachment_id;
}



/**
 * Filtro dinámico para el prefijo de AWS S3 (WP Offload Media / AS3CF)
 * Lee de forma síncrona los parámetros de la URL actual de la sesión.
 */
$GLOBALS['topclass_media_context'] = 'public';

function topclass_detect_upload_context_in_flight( $file ) {
    // 1. Capturamos la URL exacta que golpeó al VPS (incluye async-upload.php?is_woo_download=1)
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // 2. Si no la encuentra ahí, revisamos el REFERER (la página madre del navegador) por si acaso
    $referer_url = $_SERVER['HTTP_REFERER'] ?? '';

    // Tu ternario maestro adaptado para morder los canales crudos del servidor
    $is_downloadable = ( strpos( $request_uri, 'is_woo_download=1' ) !== false || strpos( $referer_url, 'is_woo_download=1' ) !== false );

    $GLOBALS['topclass_media_context'] = $is_downloadable ? 'private' : 'public';

    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'topclass_detect_upload_context_in_flight', 1 );

function topclass_media_object_prefix_by_browser_url( $prefix ) {
    // Inicializamos el contexto por defecto local
    $context = $GLOBALS['topclass_media_context'] ?? 'public';


    // Retornamos la ruta final mapeada en tu bucket de S3
    return 'trans/' . $context . '/';
}
add_filter( 'as3cf_setting_object-prefix', 'topclass_media_object_prefix_by_browser_url', 10, 1 );


