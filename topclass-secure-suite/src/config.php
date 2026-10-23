<?php
if (!defined('ABSPATH')) {
    exit;
}

// Configuración general de CloudFront y rutas del servidor
return array(
    'cloudfront_domain' => 'https://cdn.topclassclub.com',
    'key_pair_id'       => 'K3DIY62GDW2JSC',
    'private_key_path'  => '/var/www/trans.topclassclub.com/private_keys/topclassclub-cf-private.pem',
    'target_duration'   => '6',
    'intros_duration'   => '10',
    'bandwidth'   => '1835660',
    'average_bandwidth'   => '1322393',
    'codec'   => 'avc1.64001f',
    'version'   => '6',
    'sequence'   => '1',
);
