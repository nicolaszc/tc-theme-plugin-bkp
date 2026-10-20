<?php
if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('topclass_secure_content', function($atts) {
    $atts = shortcode_atts(array(
        'product_id' => '',
        'type'       => 'video', // video, image, pdf
        'path'       => '',      // Opcional si usa _private_s3_path del producto
        'text'       => 'Descargar Documento PDF seguro'
    ), $atts);

    $product_id = intval($atts['product_id']);
    $type       = sanitize_text_field($atts['type']);
    $file_path  = sanitize_text_field($atts['path']);
    $link_text  = sanitize_text_field($atts['text']);
    $user_id    = get_current_user_id();

    // Validar acceso mediante la capa de queries
    if (!topclass_user_has_access_to_product($user_id, $product_id)) {
        return '<p style="color: red;">Debes comprar este producto o iniciar sesión para ver este contenido.</p>';
    }

    // Si no se pasó un path directo, se busca en los metadatos del producto
    

    $signed_url = topclass_generate_cloudfront_signed_url($resource_url, $expires);

    if (!$signed_url) {
        return '<p style="color: red;">Error al generar la URL firmada de CloudFront.</p>';
    }

    // Renderizado HTML según el tipo
    ob_start();
    
    switch ($type) {
        case 'image':
            ?>
            <div class="topclass-secure-image-wrapper" style="margin: 15px 0;">
                <img src="<?php echo esc_url($signed_url); ?>" alt="Contenido Protegido" style="max-width: 100%; height: auto;" />
            </div>
            <?php
            break;

        case 'pdf':
            ?>
            <div class="topclass-secure-pdf-wrapper" style="margin: 15px 0;">
                <a href="<?php echo esc_url($signed_url); ?>" target="_blank" rel="noopener" class="button topclass-pdf-btn">
                    <?php echo esc_html($link_text); ?>
                </a>
            </div>
            <?php
            break;

        case 'video':
            default:
            // Aseguramos que se carguen los scripts y estilos de Video.js (CDN oficial)
            wp_enqueue_style('video-js-css', 'https://vjs.zencdn.net/8.10.0/video-js.css', array(), '8.10.0');
            wp_enqueue_script('video-js-js', 'https://vjs.zencdn.net/8.10.0/video.min.js', array(), '8.10.0', true);

            // 4. URL del manifiesto maestro VIRTUAL (sin firmar, servido por tu endpoint/controlador local)
            // Este master virtual se encargará de servir internamente el master limpio y sus submanifiestos limpios.
            $virtual_master_url = rest_url('topclass/v1/stream/' . $product_id . '/master');

            $rest_nonce = wp_create_nonce('wp_rest'); // Creamos el nonce de la REST API

            $player_id = 'topclass-player-' . esc_attr($product_id);
            ob_start();
            ?>
            <div class="topclass-secure-video-wrapper" style="margin: 15px 0;">
                <video-js id="<?php echo $player_id; ?>" class="vjs-big-play-centered vjs-fluid" controls preload="metadata" data-setup='{}'>
                    <p class="vjs-no-js">
                        Para ver este video, por favor habilita JavaScript y considera actualizar a un navegador web que <a href="https://videojs.com/html5-video-support/" target="_blank">soporte video HTML5</a>.
                    </p>
                </video-js>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof videojs !== 'undefined') {
                        var playerId = '<?php echo $player_id; ?>';
                        var finalVideoUrl = <?php echo json_encode($virtual_master_url); ?>;
                        var wpNonce = <?php echo json_encode($rest_nonce); ?>;

                        // Inyectamos el Nonce de WordPress en los headers de todas las peticiones XHR de Video.js
                        videojs.xhr.beforeRequest = function(options) {
                            options.headers = options.headers || {};
                            options.headers['X-WP-Nonce'] = wpNonce;
                            options.withCredentials = true;
                            return options;
                        };

                        var player = videojs(playerId);
                        player.src({ src: finalVideoUrl, type: 'application/x-mpegURL' });

                        player.el().addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                        });

                        var isRefreshingToken = false;

                        player.on('error', function() {
                            var err = player.error();
                            console.log('¡Error capturado por Video.js!', err);

                            var status = 0;
                            if (err && err.status) {
                                status = err.status;
                            }

                            // Si detectamos un error 403 (Token vencido) y no estamos refrescando ya:
                            if ((status === 403 || (err && err.code === 2)) && !isRefreshingToken) {
                                isRefreshingToken = true;
                                console.log('Token expirado (403) detectado por límite de búfer. Renovando firmas...');

                                // 1. Guardamos el segundo exacto donde iba el reproductor
                                var currentPlaybackTime = player.currentTime();

                                // 2. Aquí podemos disparar la renovación de los sub-manifiestos (video y audio)
                                // Usamos fetch simultáneo o encadenado a tus endpoints hijos si corresponde, 
                                // o recargamos el master url que ya genera las rutas frescas por detrás.
                                fetch(finalVideoUrl, {
                                    method: 'GET',
                                    credentials: 'include',
                                    headers: {
                                        'X-WP-Nonce': wpNonce
                                    }
                                })
                                .then(function(response) {
                                    if (!response.ok) {
                                        throw new Error('No se pudo renovar el manifiesto.');
                                    }
                                    return response.text();
                                })
                                .then(function(manifestText) {
                                    console.log('Manifiestos y firmas renovadas con éxito.');
                                    
                                    // 3. Forzamos la recarga de la fuente con timestamp para evitar caché del navegador en el m3u8
                                    player.src({ src: finalVideoUrl + '&_r=' + new Date().getTime(), type: 'application/x-mpegURL' });
                                    
                                    // 4. Restauramos el tiempo exacto y reanudamos
                                    player.one('loadedmetadata', function() {
                                        player.currentTime(currentPlaybackTime);
                                        player.play().catch(function(e) {
                                            console.log('Autoplay prevenido:', e);
                                        });
                                        isRefreshingToken = false;
                                    });
                                })
                                .catch(function(error) {
                                    console.error('Error crítico al refrescar el token:', error);
                                    isRefreshingToken = false;
                                });
                            }
                        });
                    }
                });
            </script>

        <?php
        break;
    }
    return ob_get_clean();
});
