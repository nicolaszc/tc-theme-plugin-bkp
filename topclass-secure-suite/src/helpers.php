<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Genera la URL firmada de CloudFront usando OpenSSL.
 */
function topclass_generate_cloudfront_signed_url($resource_url, $expires_timestamp) {
    $config = include __DIR__ . '/config.php';
    
    $private_key_path = $config['private_key_path'];
    $key_pair_id      = $config['key_pair_id'];

    if (!file_exists($private_key_path)) {
        return false;
    }

    $private_key = file_get_contents($private_key_path);
    
    // Política de acceso en formato JSON
    $json_policy = '{"Statement":[{"Resource":"' . $resource_url . '","Condition":{"DateLessThan":{"AWS:EpochTime":' . $expires_timestamp . '}}}]}';

    // Firmar la política con OpenSSL (SHA1)
    $signature = '';
    $success = openssl_sign($json_policy, $signature, $private_key, OPENSSL_ALGO_SHA1);

    if (!$success) {
        return false;
    }

    // Codificación segura para URL (Base64 URL Safe)
    $encoded_policy    = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($json_policy));
    $encoded_signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($signature));

    return $resource_url . '?Policy=' . $encoded_policy . '&Signature=' . $encoded_signature . '&Key-Pair-Id=' . $key_pair_id;
}
