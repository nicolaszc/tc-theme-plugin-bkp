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



/**
 * Función auxiliar para resolver el alias (Vendor o Fallback) de forma segura
 */
function topclass_get_current_upload_alias( $product_id = 0 ) {
    $GLOBALS['fallback_name'] = 'TopClass' . ( $GLOBALS['current_site_cat'] ?? 'General' );

    // 1. Si NO es administrador, usamos directamente el vendor_name global
    if ( ! current_user_can( 'administrator' ) && ! empty( $GLOBALS['vendor_name'] ) ) {
        return $GLOBALS['vendor_name'];
    }

    // 2. Si es admin y tenemos un product_id válido, revisamos los términos de WCPV
    if ( $product_id > 0 && get_post_type( $product_id ) === 'product' ) {
        $vendors = wp_get_post_terms( $product_id, 'wcpv_product_vendors' );
        
        if ( ! is_wp_error( $vendors ) && ! empty( $vendors ) ) {
            $term = $vendors[0];
            if ( ! empty( $term->slug ) ) {
                return $term->slug;
            }
        }
    }

    // 3. Fallback si no tiene vendor o no es un producto con vendor asignado
    return $fallback_name;
}






/**
 * Filtro dinámico para el prefijo de AWS S3 (WP Offload Media / AS3CF)
 * Lee de forma síncrona los parámetros de la URL actual de la sesión.
 */
$GLOBALS['topclass_media_context'] = 'public';

/* function topclass_detect_upload_context_in_flight( $file ) {
    // 1. Capturamos la URL exacta que golpeó al VPS (incluye async-upload.php?is_woo_download=1)
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // 2. Si no la encuentra ahí, revisamos el REFERER (la página madre del navegador) por si acaso
    $referer_url = $_SERVER['HTTP_REFERER'] ?? '';

    // Tu ternario maestro adaptado para morder los canales crudos del servidor
    $is_downloadable = ( strpos( $request_uri, 'is_woo_download=1' ) !== false || strpos( $referer_url, 'is_woo_download=1' ) !== false );

    $GLOBALS['topclass_media_context'] = $is_downloadable ? 'private' : 'public';

    return $file;
} */

function topclass_detect_upload_context_in_flight( $file ) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $referer_url = $_SERVER['HTTP_REFERER'] ?? '';

    // Detección de contexto (Público vs Privado)
    $is_downloadable = ( strpos( $request_uri, 'is_woo_download=1' ) !== false || strpos( $referer_url, 'is_woo_download=1' ) !== false );
    $GLOBALS['topclass_media_context'] = $is_downloadable ? 'private' : 'public';

    // Capturar el ID del producto de forma segura (desde la petición o parseando el navegador/referer)
    $product_id = 0;
    if ( isset( $_REQUEST['post_id'] ) ) {
        $product_id = intval( $_REQUEST['post_id'] );
    } elseif ( isset( $_POST['post_id'] ) ) {
        $product_id = intval( $_POST['post_id'] );
    } elseif ( ! empty( $referer_url ) ) {
        // Extraer el ID si venimos de la pantalla de edición de un post/producto
        if ( preg_match( '/[?&]post=(\d+)/', $referer_url, $matches ) ) {
            $product_id = intval( $matches[1] );
        }
    }

    // Llamamos a la función pasándole el ID detectado
    $alias = topclass_get_current_upload_alias( $product_id );
    $GLOBALS['topclass_media_alias'] = sanitize_title( $alias );

    // Renombrado del archivo
    $filename = $file['name'];
    $pathinfo = pathinfo( $filename );
    $ext      = isset( $pathinfo['extension'] ) ? strtolower( $pathinfo['extension'] ) : '';
   
    $timestamp  = date( 'Ymd-His' );

    $file['name'] = sanitize_title( $alias ) . '.' .$ext;

    return $file;
}

add_filter( 'wp_handle_upload_prefilter', 'topclass_detect_upload_context_in_flight', 1 );

function topclass_media_object_prefix_by_browser_url( $prefix ) {
    // Inicializamos el contexto por defecto local
    $context = $GLOBALS['topclass_media_context'] ?? 'public';
    $alias   = $GLOBALS['topclass_media_alias'] ?? sanitize_title($GLOBALS['fallback_name']);

    // Retornamos la ruta final mapeada en tu bucket de S3
    return 'trans/' . $context . '/' . $alias . '/';
}
add_filter( 'as3cf_setting_object-prefix', 'topclass_media_object_prefix_by_browser_url', 10, 1 );


