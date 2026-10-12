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
