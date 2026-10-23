<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Genera y setea las Signed Cookies de CloudFront usando OpenSSL (Soporta comodines /*).
 * Esto elimina los tokens de la URL, permitiendo URLs limpias y evitando que expiren durante la reproducción.
 */
function topclass_set_cloudfront_signed_cookies($resource_url, $expires_timestamp, $use_wildcard = true) {
    $config = include __DIR__ . '/config.php';
    
    $private_key_path = $config['private_key_path'];
    $key_pair_id      = $config['key_pair_id'];

    if (!file_exists($private_key_path)) {
        return false;
    }

    $private_key = file_get_contents($private_key_path);
    
    // Si se activa el comodín, aseguramos que la ruta termine en /* para que cubra todos los chunks/manifiestos
    $policy_resource = $resource_url;
    if ($use_wildcard) {
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

    // Codificación segura para URL (Base64 URL Safe) que exige CloudFront
    $encoded_policy    = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($json_policy));
    $encoded_signature = str_replace(array('+', '=', '/'), array('-', '_', '~'), base64_encode($signature));

    error_log("SETTING COOKIES PARA RECURSO: " . $resource_url);
    error_log("RECURSO EN JSON (COOKIES): " . $policy_resource);

    // Obtenemos el dominio actual o el dominio base para el scope de la cookie
    // Asegúrate de que el dominio de la cookie coincida con tu entorno (ej: .topclassclub.com)
    $parsed_url = parse_url($resource_url);

    // Extraemos dinámicamente el dominio base (ej: topclassclub.com a partir de trans.topclassclub.com)
    $host_parts = explode('.', $_SERVER['HTTP_HOST']);
    $count = count($host_parts);
    if ($count >= 2) {
        // Tomamos las últimas dos partes (ej: topclassclub y com) y les anteponemos el punto
        $cookie_domain = '.' . $host_parts[$count - 2] . '.' . $host_parts[$count - 1];
    } else {
        // Respaldo por si acaso es un entorno local tipo 'localhost'
        $cookie_domain = $_SERVER['HTTP_HOST'];
    }
    
    // Seteamos las 3 cookies obligatorias de CloudFront en el navegador del cliente
    // secure = true (requiere HTTPS), httponly = true (protegido contra XSS si no se requiere lectura por JS)
    $cookie_path = '/';
    
    setcookie('CloudFront-Policy', $encoded_policy, $expires_timestamp, $cookie_path, $cookie_domain, true, false);
    setcookie('CloudFront-Signature', $encoded_signature, $expires_timestamp, $cookie_path, $cookie_domain, true, false);
    setcookie('CloudFront-Key-Pair-Id', $key_pair_id, $expires_timestamp, $cookie_path, $cookie_domain, true, false);

    return true;
}
function tc_insert_shortcode($role){
    $product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : get_the_ID();

    
    if (!is_user_logged_in()) {
        echo '<div class="row justify-content-center">';
        echo '<div class="col-md-6 text-center">';
        echo '<div class="alert alert-warning d-inline-block" role="alert"><i class="fas fa-exclamation-triangle"></i> Debes iniciar sesión para ver este contenido.</div>';
        echo '</div>';
        echo '</div>';
        return;
    }
    
    echo '<div class="row justify-content-center">';
        if ($product_id > 0) {
            if($role=='client'){           
                echo do_shortcode('[topclass_secure_content product_id="' . $product_id . '" type="video"]');          
            }else{
                echo do_shortcode('[topclass_secure_content product_id="' . $product_id . '" type="video"]');
            }
        } else {
            echo '<div class="col-md-6 text-center">';
            echo '<div class="alert alert-warning d-inline-block" role="alert"><i class="fas fa-exclamation-triangle"></i> Por favor selecciona un contenido válido desde tus descargas.</div>';
            echo '</div>';
        }
    echo '</div>';
}
