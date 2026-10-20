<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Genera la URL firmada de CloudFront usando OpenSSL (Soporta comodines /*).
 */
function topclass_generate_cloudfront_signed_url($resource_url, $expires_timestamp, $use_wildcard = true) {
    $config = include __DIR__ . '/config.php';
    
    $private_key_path = $config['private_key_path'];
    $key_pair_id      = $config['key_pair_id'];

    if (!file_exists($private_key_path)) {
        return false;
    }

    $private_key = file_get_contents($private_key_path);
    
    // Si se activa el comodín, aseguramos que la URL termine en /*
    $policy_resource = $resource_url;
    if ($use_wildcard) {
        // Quitamos cualquier archivo final (ej: .mp4 o .m3u8) y dejamos la ruta con /*
        $policy_resource = rtrim(dirname($resource_url), '/') . '/*';
    }

    // Política de acceso en formato JSON con el recurso adaptado
    $json_policy = '{"Statement":[{"Resource":"' . $policy_resource . '","Condition":{"DateLessThan":{"AWS:EpochTime":' . $expires_timestamp . '}}}]}';

    // Firmar la política con OpenSSL (SHA1)
    $signature = '';
    $success = openssl_sign($json_policy, $signature, $private_key, OPENSSL_ALGO_SHA1);

    if (!$success) {
        return false;
    }

    // Codificación segura para URL (Base64 URL Safe)
    $encoded_policy    = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($json_policy));
    $encoded_signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($signature));

    error_log("URL SOLICITADA: " . $resource_url);
    error_log("RECURSO EN JSON: " . $policy_resource);

    // IMPORTANTE: La URL a la que accederá el cliente debe ser el archivo exacto (ej: el .m3u8),
    // pero la política firmada en el JSON autoriza a toda la carpeta con el comodín.
    return $resource_url . '?Policy=' . $encoded_policy . '&Signature=' . $encoded_signature . '&Key-Pair-Id=' . $key_pair_id;
}

