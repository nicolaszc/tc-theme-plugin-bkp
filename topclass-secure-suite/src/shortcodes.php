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
    $product_vendor = $product_meta['vendor'];
    $product_date = $product_meta['date'];
    $product_duration = gmdate("i:s", $product_meta['duration'] + $config['intros_duration']);
    $product_resolution = $product_meta['resolution'];

    $is_aspect_portrait = ($product_meta['aspect'] == 'portrait') ? true : false;
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
           
            $info_text_margin_class = 'mt-3 mt-md-0';

            if($is_aspect_portrait){

                $intro_text_size = '45%';
                $vendor_name_size = '180%';
                $legal_disclaimer_size = '45%';

                if($access_role){
                    $video_wrapper_class = 'col-md-6';
                    $video_info_class = 'col-md-6 ' . $info_text_margin_class;
                }else{
                    $video_wrapper_class = 'offset-md-3 col-md-4 px-md-5';
                    $video_info_class = 'col-md-3 ms-md-n5 ps-md-4' . $info_text_margin_class;
                } 
            }else{    
                
                $intro_text_size = '65%';
                $vendor_name_size = '230%';
                $legal_disclaimer_size = '65%';
        
                if($access_role){
                    $video_wrapper_class = 'col-md-12 mb-3';
                    $video_info_class = 'col-md-12 ' . $info_text_margin_class;
                }else{
                    $video_wrapper_class = 'col-md-8';
                    $video_info_class = 'col-md-4 ' . $info_text_margin_class;
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

                .vjs-theme-tcc .vjs-big-play-button {
                    background-color: #ffffff40;
                    border: none;
                }
                
                .vjs-theme-tcc .vjs-control-bar{
                    height: 40px!important;
                    background-color: #ffffff40;
                    z-index: 1;
                }

                .vjs-theme-tcc .vjs-progress-holder {
                    height: 100%;
                    margin: 0 !important;
                }

                .vjs-theme-tcc .vjs-play-progress{
                    background-color: #ffffff50;
                }

                .vjs-theme-tcc .vjs-play-progress:before{
                    content: none !important;
                }

                .vjs-theme-tcc .vjs-button>.vjs-icon-placeholder:before,
                .vjs-theme-tcc .vjs-time-control {
                    line-height: 40px;
                }

                .vjs-theme-tcc .vjs-control:focus-visible, .vjs-theme-tcc .vjs-control:focus-within, .vjs-theme-tcc .vjs-control:focus{
                    outline: 0;
                }

                .vjs-theme-tcc .vjs-text-track-display{
                    bottom: 0;
                    z-index: 0;
                }

                .vjs-theme-tcc .vjs-text-track-display > div{
                    margin: 0!important;
                    opacity: 0;
                    transition: opacity 0.5s; 
                    transition-delay: 1s;
                }

                .vjs-theme-tcc .vjs-text-track-cue,
                .vjs-theme-tcc.vjs-user-inactive.vjs-playing .vjs-text-track-display {
                    top: 0 !important;
                    height: 100% !important;
                    bottom: 0 !important;
                    padding: 1rem 40px 40px !important;
                }
                
                .vjs-theme-tcc .vjs-text-track-cue > div{
                    background: transparent !important;
                    text-shadow: none !important;  
                    display: block !important;  
                    line-height: 1 !important;   
                    inset: 45% 0 0 !important;   
                    padding: 10px 0 0; 
                    height: 55%;     
                }

                .vjs-theme-tcc .vjs-text-track-cue span.intro-text, 
                .vjs-theme-tcc .vjs-text-track-cue span.legal-disclaimer{
                    font-family: 'Inter';
                    color: #ffffff60;
                    font-weight: 200;
                    line-height: 1;
                }

                .vjs-theme-tcc .vjs-text-track-cue span.intro-text{
                    margin-bottom: 5%;
                    display: inline-block;
                    font-size: <?php echo $intro_text_size ?>;
                }

                .vjs-theme-tcc .vjs-text-track-cue span.legal-disclaimer{
                    line-height: 1.5;
                    font-size: <?php echo $legal_disclaimer_size ?>;
                }

                .vjs-theme-tcc .vjs-text-track-cue span.vendor-name{
                    font-family: 'Birthstone';
                    font-size: <?php echo $vendor_name_size ?>;  
                    font-style: italic; 
                    animation-name:glow;
                    animation-duration:1s;
                    animation-iteration-count:infinite;
                    animation-direction:alternate;                                  
                }  
                    
                @keyframes glow{
                    from{text-shadow:0px 0px 5px #fff,0px 0px 5px #614ad3;}
                    to{text-shadow:0px 0px 20px #fff,0px 0px 20px #614ad3;}
                }

                .vjs-theme-tcc.vjs-has-started .vjs-text-track-display > div{
                    /* Estado final: Totalmente visible y en su posición original */
                    opacity: 1;
                }

            </style>
            <div class="topclass-secure-video-wrapper <?php echo $video_wrapper_class; ?>" style="margin: 0;">
                <video-js id="<?php echo $player_id; ?>" controls preload="metadata" class="vjs-theme-tcc">
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
                            },
                            nativeTextTracks: false
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
                                'ProgressControl',
                                'RemainingTimeDisplay',
                                'FullscreenToggle'
                            ],
                            volumePanel: {
                            inline: false
                            }       
                        }                                
                    });

                    player.src({ src: finalVideoUrl, type: 'application/x-mpegURL' });
                    // Get all text tracks for the current player.
                    var tracks = player.remoteTextTracks();
                    var metadataTrack;

                    for (var i = 0; i < tracks.length; i++) {
                    var track = tracks[i];
                    track.mode = 'showing';
                    metadataTrack = track;
                    console.log(metadataTrack);
                    }

                    // Add a listener for the "cuechange" event and start ad playback.
                    tracks.addEventListener('cuechange', function() {
                        console.log('cue');
                    });
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
                <p class="mb-1"><i class="fas fa-female me-2 text-center"></i> <strong class="me-1">Creadora:</strong> <?php echo $product_vendor; ?></p>
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
