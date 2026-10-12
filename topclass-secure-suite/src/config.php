<?php
if (!defined('ABSPATH')) {
    exit;
}

// Configuración general de CloudFront y rutas del servidor
return array(
    'cloudfront_domain' => 'https://cdn.topclassclub.com',
    'key_pair_id'       => 'K3DIY62GDW2JSC',
    'private_key_path'  => '/var/www/trans.topclassclub.com/private_keys/topclassclub-cf-private.pem',
);
