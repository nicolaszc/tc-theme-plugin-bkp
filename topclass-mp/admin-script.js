jQuery(function ($) {
    $(document).ready(function () {
        $('.woocommerce-admin-page.product .downloadable_files label').html('Subir archivo del Contenido');
        //$('.woocommerce-admin-page.product .downloadable_files tbody.ui-sortable').html("<tr><td></td><td class='file_name'><input type='text' class='input_text' placeholder='Nombre de archivo' name='_wc_file_names[]' value=''><input type='hidden' name='_wc_file_hashes[]' value=''></td><td class='file_url'><input type='text' class='input_text' placeholder='https://' name='_wc_file_urls[]' value=''></td><td class='file_url_choose' width='1%'><a href='#' class='button upload_s3_button' data-choose='Elegir archivo' data-update='Insertar URL del archivo'>Elegir archivo</a><td width='1%'></td></td></tr>");
        //$('.woocommerce-admin-page.product .downloadable_files tfoot').html('');
        $('.post-type-product h2#postexcerpt-title').html('Descripción del Contenido');
        $('.post-type-product .downloadable_files tfoot .button.insert').trigger('click');
        $('.product .downloadable_files tfoot .button.upload_file_button').trigger('click');
        $selector = $(selectorMarkup);
        $selector.find(`input[name="topclass_s3_mode"][value="${uploadMode}"]`).prop('checked', true);
        $selector.on('change', 'input[name="topclass_s3_mode"]', function () {
            setUploadMode(this.value);
        });
        $('.post-type-product #general_product_data .show_if_downloadable').prepend($selector);
        $('input[name="topclass_s3_mode"]').trigger('change');
        $('.post-type-product .downloadable_files tfoot').css('display','none');


    }); 

    const selectorMarkup = `
        <fieldset class="topclass-s3-radio-box" style="padding:12px 15px;margin:0 auto 15px;max-width:450px;text-align:left;">
            <span style="display:block;font-weight:600;margin-bottom:8px;font-size:13px;color:#1d2327;">Tipo de Contenido</span>
            <label style="display:block;margin:0 0 6px 0;cursor:pointer;font-size:13px;">
                <input type="radio" name="topclass_s3_mode" value="public" style="margin-right:8px;">
                🌐 <strong>Público</strong> (Gratuito)
            </label>
            <label style="display:block;cursor:pointer;font-size:13px;color:#b32d2e;margin:0 0 0 0">
                <input type="radio" name="topclass_s3_mode" value="private" checked style="margin-right:8px;">
                🔒 <strong>Privado</strong> (Pagado)
            </label>
        </fieldset>`;

    let uploadFrame = null;
    let uploadMode = 'private';
    let pendingUrlInput = null;

    // El PHP ya lee este parámetro de la URL/referer al procesar cada subida.
    function setUploadMode(mode) {
        uploadMode = mode === 'private' ? 'private' : 'public';
        const url = new URL(window.location.href);

        if (uploadMode === 'private') {
            url.searchParams.set('is_private_content', '1');
        } else {
            url.searchParams.delete('is_private_content');
        }

        window.history.replaceState({}, '', url.toString());
    }

    function renderUploadSelector(frame) {
       /*  const $uploadPane = frame.$el.find('.media-frame-content .uploader-inline');
        if (!$uploadPane.length) {
            return;
        }

        let $selector = $uploadPane.find('.topclass-s3-radio-box');
        if ($selector.length) {
            $selector.find(`input[name="topclass_s3_mode"][value="${uploadMode}"]`).prop('checked', true);
            return;
        }

        $selector = $(selectorMarkup);
        $selector.find(`input[name="topclass_s3_mode"][value="${uploadMode}"]`).prop('checked', true);
        $selector.on('change', 'input[name="topclass_s3_mode"]', function () {
            setUploadMode(this.value);
        });
        $uploadPane.prepend($selector); */
    }

    function getUploadFrame() {
        if (uploadFrame) {
            return uploadFrame;
        }

        //let $uploadFrame = $uploadPane.find('.acf-media-modal');
        console.log('custom modal')
        uploadFrame = new wp.media.view.MediaFrame.Select({
            title: 'Selecciona',
            multiple: false,
            library: {
                order: 'ASC',
                orderby: 'title',
                type: ['video', 'image'],
                search: null,
                uploadedTo: null
            },
            button: { text: 'Insertar URL del contenido' }
        }); 

        // WordPress emite este evento al dibujar la pestaña "Subir archivos".
        uploadFrame.on('content:render:upload', function () {
            //renderUploadSelector(uploadFrame);
        });

        // Al confirmar un archivo, devuelve su URL a la fila de producto que abrió el frame.
        uploadFrame.on('select', function () {
            const selection = uploadFrame.state().get('selection');
            const attachment = selection && selection.first();
            const $attachmentInfo = uploadFrame.$el.find('.attachment-info');
            const $attachmentDetails = uploadFrame.$el.find('.attachment-details');
            const url = $attachmentDetails.find('#attachment-details-copy-link').val() ||
                (attachment && attachment.get('url'));
            const filename = $attachmentInfo.find('.filename').first().text().trim() ||
                (attachment && attachment.get('filename'));

            if (!pendingUrlInput || !url) {
                return;
            }

            pendingUrlInput.val(url).trigger('input').trigger('change');
            if (filename) {
                pendingUrlInput.closest('tr').find('input[name="_wc_file_names[]"]').first()
                    .val(filename).trigger('input').trigger('change');
            }
            pendingUrlInput = null;
        });

        uploadFrame.on('open', function () {
            setUploadMode('public');
            renderUploadSelector(uploadFrame);
        });

        uploadFrame.on('close', function () {
            pendingUrlInput = null;
            setUploadMode('public');
        });

        return uploadFrame;
    }

    // Un frame propio y persistente: cada clic reabre la misma ventana.
    /* $(document).on('click', 'a.upload_file_button', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (!window.wp || !wp.media || !wp.media.view || !wp.media.view.MediaFrame) {
            return;
        }

        pendingUrlInput = $(this).closest('tr').find('input[name="_wc_file_urls[]"]').first();
        if (!pendingUrlInput.length) {
            pendingUrlInput = null;
            return;
        }

        getUploadFrame().open();
    }); */
});
