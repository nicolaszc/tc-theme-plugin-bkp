<?php
/**
 * Plugin Name: TopClass Secure AWS Media Manager
 * Description: Gestión unificada de subidas a S3, procesado CMAF/HLS, cookies firmadas de CloudFront y reproductor Video.js.
 * Version: 1.0.0
 * Author: TopClass
 */

if (!defined('ABSPATH')) exit;

define('TC_MEDIA_PATH', plugin_dir_path(__FILE__));
define('TC_MEDIA_URL', plugin_dir_url(__FILE__));

// Carga de módulos principales de includes/
require_once TC_MEDIA_PATH . 'includes/helpers.php';
require_once TC_MEDIA_PATH . 'includes/filters.php';
require_once TC_MEDIA_PATH . 'includes/s3-upload-router.php';
require_once TC_MEDIA_PATH . 'includes/shortcodes.php';
require_once TC_MEDIA_PATH . 'includes/rest-api.php';

// Carga del script de subida a S3 (migrado desde topclass-mp)
add_action('admin_enqueue_scripts', function($hook) {
    if (file_exists(TC_MEDIA_PATH . 'assets/js/s3-upload.js')) {
        wp_enqueue_script(
            'tc-s3-upload-js',
            TC_MEDIA_URL . 'assets/js/s3-upload.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }
});
