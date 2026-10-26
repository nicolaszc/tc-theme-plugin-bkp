<?php
if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('topclass_secure_content', function($atts) {
    $config = include __DIR__ . '/config.php';

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

    $has_access = topclass_user_has_access_to_product($user_id, $product_id)['access'];
    $access_role = (topclass_user_has_access_to_product($user_id, $product_id)['role'] == 'client') ? true : false;
    
    $product_meta = tc_get_product_meta($product_id);

    $product_title = $product_meta['title'];
    $product_author = $product_meta['author'];
    $product_date = $product_meta['date'];
    $product_duration = gmdate("i:s", $product_meta['duration'] + $config['intros_duration']);
    $product_resolution = $product_meta['resolution'];

    $video_aspect_ratio = $product_meta['orientation'];
    $device = $GLOBALS['device'];

    // Validar acceso mediante la capa de queries
    if ( $has_access == false) {
        return '<div class="col-md-6 text-center"><div class="alert alert-warning d-inline-block" role="alert"><i class="fas fa-exclamation-triangle"></i> Debes comprar este producto o iniciar sesión para ver este contenido.</div></div>';
    }
    
    switch ($type) {
        case 'image':
            ob_start();
            ?>
            <div class="topclass-secure-image-wrapper" style="margin: 0;">
                <img src="#" alt="Contenido Protegido" style="max-width: 100%; height: auto;" />
            </div>
            <?php
            break;

        case 'pdf':
            ob_start();
            ?>
            <div class="topclass-secure-pdf-wrapper" style="margin: 0;">
                <a href="#" target="_blank" rel="noopener" class="button topclass-pdf-btn">
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


            if($video_aspect_ratio == 'portrait' && !$device){
                if($access_role){
                    $video_wrapper_class = 'col-md-6';
                    $video_info_class = 'col-md-6';
                }else{
                    $video_wrapper_class = 'col-md-3';
                    $video_info_class = 'col-md-3';
                }               
            }else{               
                if($access_role){
                    $video_wrapper_class = 'col-md-12 mb-3';
                    $video_info_class = 'col-md-12';
                }else{
                    $video_wrapper_class = 'col-md-8';
                    $video_info_class = 'col-md-4';
                }  
            }
            
            // URL del manifiesto maestro VIRTUAL (sin firmar, servido por tu endpoint/controlador local)
            // Este master virtual se encargará de servir internamente el master limpio y sus submanifiestos limpios.
            $virtual_master_url = rest_url('topclass/v1/stream/' . $product_id . '/master');

            $rest_nonce = wp_create_nonce('wp_rest'); // Creamos el nonce de la REST API

            $player_id = 'topclass-player-' . esc_attr($product_id);
            ob_start();
            ?>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Birthstone&display=swap');
                @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
                .vjs-text-track-display{
                    bottom: 0;
                }

                .vjs-text-track-display > div{
                    margin: 0!important;
                }

                .vjs-text-track-cue{
                    top: 45% !important;
                    height: unset !important;
                    bottom: 30px !important;
                    padding: 1rem !important;
                }

                .vjs-text-track-cue > div{
                    background: transparent !important;
                    text-shadow: none !important;                   
                }

                .vjs-text-track-cue span.intro-text, 
                .vjs-text-track-cue span.legal-disclaimer{
                    opacity: 0;
                    transition: opacity 0.5s;
                    font-family: 'Inter';
                    font-size: 14px;
                    font-weight: 300;
                }

                .vjs-text-track-cue span.vendor-name{
                    opacity: 0;
                    transition: opacity 0.5s;
                    transition-delay: 0.25s;
                    font-family: 'Birthstone';
                    font-size: 48px;                   
                }
                
                .vjs-text-track-cue span.legal.disclaimer{

                }

               
                .vjs-text-track-cue span.intro-text,
                 .vjs-text-track-cue span.vendor-name,
                  .vjs-text-track-cue span.legal-disclaimer {
                    /* Estado final: Totalmente visible y en su posición original */
                    opacity: 1;
                    transition: opacity 0.5s;
                }

                .vjs-control-bar{
                    height: 30px;
                }
               
            </style>
            <div class="topclass-secure-video-wrapper <?php echo $video_wrapper_class; ?>" style="margin: 0;">
                <video-js id="<?php echo $player_id; ?>" controls preload="auto">
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

                    // Inicialización limpia de Video.js con Signed Cookies nativas
                    var player = videojs(playerId, {
                        html5: {
                            vhs: {
                                overrideNative: true,
                                withCredentials: true // <- Esto le ordena a Video.js/VHS que incluya las cookies en los requests al CDN
                                
                            }
                        },
                        fluid: true,
                        preloadTextTracks: true,
                        children: [
                            'BigPlayButton',
                            'ControlBar',
                            'TextTrackDisplay'
                        ],
                        ControlBar: {
                            children: [
                                'PlayToggle',
                                'DurationDisplay',
                                'ProgressControl',
                                'RemainingTimeDisplay',
                                'VolumePanel',
                                'FullscreenToggle'
                            ]
                        },
                                             
                    });

                    player.src({ src: finalVideoUrl, type: 'application/x-mpegURL' });

                    // Protección anti-click derecho básica
                    player.el().addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                    });
                }
            });
            </script>
            <style>
                .content-info p{
                    font-size: 0.875rem;
                }
                .content-info i{
                    width: 16px;
                }
            </style>
            <div class="<?php echo $video_info_class; ?> text-light content-info">
                <p class="mb-1"><i class="fas fa-female me-2 text-center"></i> <strong class="me-1">Creadora:</strong> <?php echo $product_author; ?></p>
                <p class="mb-1"><i class="fas fa-video me-2 text-center"></i> <strong class="me-1">Titulo:</strong> <?php echo $product_title; ?></p>
                <p class="mb-1"><i class="fas fa-clock me-2 text-center"></i> <strong class="me-1">Duracion:</strong> <?php echo $product_duration; ?></p>
                <p class="mb-1"><i class="fas fa-calendar-alt me-2 text-center"></i> <strong class="me-1">Publicado:</strong> <?php echo $product_date; ?></p>
                <p class="mb-1"><i class="fas fa-expand-arrows-alt me-2 text-center"></i> <strong class="me-1">Resolución:</strong> <?php echo $product_resolution; ?></p>
            </div>
        <?php
        break;
    }
    return ob_get_clean();
});
