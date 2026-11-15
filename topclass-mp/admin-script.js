
jQuery(function ($) {
            

            function addUsePathFlag(){           

                        console.log('window open');

                        let currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('use_path_selector', '1');
                        window.history.replaceState({}, '', currentUrl.toString());
                        const activeFrame = window.wp.media.frame;

                if (activeFrame) {
                    // Si el usuario cierra la ventana o presiona escapar sin subir nada, limpiamos la barra
                    activeFrame.on('escape', function() {
                            removeUsePathFlag();
                        });
                }
                    
                
            }

            function removeUsePathFlag(){
               
                        console.log('window close');
                        let cleanUrl = new URL(window.location.href);
                        cleanUrl.searchParams.delete('use_path_selector');
                        window.history.replaceState({}, '', cleanUrl.toString());
                    
            }
            
            const selectors = [
                '#set-post-thumbnail',
                '.add_product_images a',
                'button.add_media',
                'a[data-name="add"].acf-button'
            ].join(', ');

            $(document).ready( function() {  
                $('.woocommerce-admin-page.product .downloadable_files label').html('Archivo del Contenido');
                $('.woocommerce-admin-page.product .downloadable_files tbody.ui-sortable').html("<tr><td></td><td class='file_name'><input type='text' class='input_text' placeholder='Nombre de archivo' name='_wc_file_names[]' value=''><input type='hidden' name='_wc_file_hashes[]' value=''></td><td class='file_url'><input type='text' class='input_text' placeholder='https://' name='_wc_file_urls[]' value=''></td><td class='file_url_choose' width='1%'><a href='#' class='button upload_s3_button' data-choose='Elegir archivo' data-update='Insertar URL del archivo'>Elegir archivo</a><td width='1%'></td></td></tr>");
                $('.woocommerce-admin-page.product .downloadable_files tfoot').html('');
                $('.woocommerce-admin-page.product h2#postexcerpt-title').html('Descripción del Contenido');

            })

            $(document).on('click', 'a.upload_s3_button', function () {
                var frame = new wp.media.view.MediaFrame.Select({
                    // Modal title
                    title: 'Selecciona',

                    // Enable/disable multiple select
                    multiple: true,

                    // Library WordPress query arguments.
                    library: {
                        order: 'ASC',

                        // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
                        // 'id', 'post__in', 'menuOrder' ]
                        orderby: 'title',

                        // mime type. e.g. 'image', 'image/jpeg'
                        type: ['video', 'image'],

                        // Searches the attachment title.
                        search: null,

                        // Attached to a specific post (ID).
                        uploadedTo: null
                    },

                    button: {
                        text: 'Insertar Url del contenido'
                    }
                });
                var state = frame.state();
                console.log(state);
                frame.open();
            }); 

            

        

            const params = new URLSearchParams(window.location.search);
            const showSelector = params.get('use_path_selector') == '1';

        /*  if (!showSelector) {
                return;
            } */
            
            /**
             * Actualiza el flag que ya utiliza el plugin para elegir
             * el destino público o privado del archivo.
             */
            function setUploadMode(mode) {
                const url = new URL(window.location.href);

                if (mode === 'private') {
                    url.searchParams.set('is_private_content', '1');
                } else {
                    url.searchParams.delete('is_private_content');
                }

                window.history.replaceState({}, '', url.toString());
            }

            /**
             * El HTML del selector viene del hook PHP pre-upload-ui.
             * Aquí solo conectamos los radios cuando WordPress renderiza
             * la vista de subida.
             */
            function bindSelector($container) {
                const $selector = $container.find('.topclass-s3-radio-box');

                if (!$selector.length || $selector.data('topclass-bound')) {
                    return;
                }

                $selector.data('topclass-bound', true);

                $selector.on('change', 'input[name="topclass_s3_mode"]', function () {
                    setUploadMode(this.value);
                });

                setUploadMode(
                    $selector.find('input[name="topclass_s3_mode"]:checked').val() || 'public'
                );
            }

            // Cargador clásico de WordPress dentro de Thickbox.
            $(document).ready(function () {
                bindSelector($(document));
            });

            // Modal de medios moderno. WordPress avisa cuando dibuja la vista de subida.
            if (window.wp && wp.media && wp.media.frame) {
                const frame = wp.media.frame;

                frame.on('content:render:upload', function () {
                    bindSelector(frame.$el);
                });

                frame.on('open', function () {
                    bindSelector(frame.$el);
                });
            }

            // Si el usuario cierra el modal, limpia los flags de esta URL.
            $(document).on('tb_unload', function () {
                const url = new URL(window.location.href);
                url.searchParams.delete('use_path_selector');
                url.searchParams.delete('is_private_content');
                window.history.replaceState({}, '', url.toString());
            });

            const selectorFlag = 'use_path_selector';

            function addSelectorFlag(url) {
                try {
                    const parsed = new URL(url, window.location.href);
                    parsed.searchParams.set(selectorFlag, '1');
                    return parsed.toString();
                } catch (error) {
                    return url;
                }
            }
        

            // Modal moderno: al abrir cualquier frame de la biblioteca de medios.
            if (window.wp && wp.media && wp.media.view && wp.media.view.MediaFrame) {
                $(document).on('click', function () {
                    const frame = wp.media.frame;
                    if (frame && frame.isOpen && frame.isOpen()) {
                        frame.options.use_path_selector = true;
                    }
                });
            }   
});
