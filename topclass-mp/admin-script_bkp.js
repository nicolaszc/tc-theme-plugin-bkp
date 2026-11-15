jQuery(function ($) {
    $(document).ready(function () {
        $('.woocommerce-admin-page.product .downloadable_files label').html('Archivo del Contenido');
        $('.woocommerce-admin-page.product .downloadable_files tbody.ui-sortable').html("<tr><td></td><td class='file_name'><input type='text' class='input_text' placeholder='Nombre de archivo' name='_wc_file_names[]' value=''><input type='hidden' name='_wc_file_hashes[]' value=''></td><td class='file_url'><input type='text' class='input_text' placeholder='https://' name='_wc_file_urls[]' value=''></td><td class='file_url_choose' width='1%'><a href='#' class='button upload_s3_button' data-choose='Elegir archivo' data-update='Insertar URL del archivo'>Elegir archivo</a><td width='1%'></td></td></tr>");
        $('.woocommerce-admin-page.product .downloadable_files tfoot').html('');
        $('.woocommerce-admin-page.product h2#postexcerpt-title').html('Descripción del Contenido');
    });

    const selectorMarkup = `
        <div class="topclass-s3-radio-box" style="background:#f0f0f1;padding:12px 15px;margin:0 auto 15px;max-width:450px;border-radius:4px;border:1px solid #c3c4c7;text-align:left;">
            <span style="display:block;font-weight:600;margin-bottom:8px;font-size:13px;color:#1d2327;">Destino del archivo en AWS S3:</span>
            <label style="display:block;margin-bottom:6px;cursor:pointer;font-size:13px;">
                <input type="radio" name="topclass_s3_mode" value="public" checked style="margin-right:8px;">
                🌐 <strong>Público</strong> (CDN / perfil / imágenes generales)
            </label>
            <label style="display:block;cursor:pointer;font-size:13px;color:#b32d2e;">
                <input type="radio" name="topclass_s3_mode" value="private" style="margin-right:8px;">
                🔒 <strong>Privado</strong> (S3 seguro / contenido descargable)
            </label>
        </div>`;

    let uploadFrame = null;
    let uploadMode = 'public';

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
        const $uploadPane = frame.$el.find('.media-frame-content .uploader-inline');
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
        $uploadPane.prepend($selector);
    }

    function getUploadFrame() {
        if (uploadFrame) {
            return uploadFrame;
        }

        uploadFrame = new wp.media.view.MediaFrame.Select({
            title: 'Selecciona',
            multiple: true,
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
            renderUploadSelector(uploadFrame);
        });

        uploadFrame.on('open', function () {
            setUploadMode('public');
            renderUploadSelector(uploadFrame);
        });

        uploadFrame.on('close', function () {
            setUploadMode('public');
        });

        return uploadFrame;
    }

    // Un frame propio y persistente: cada clic reabre la misma ventana.
    $(document).on('click', 'a.upload_s3_button', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (!window.wp || !wp.media || !wp.media.view || !wp.media.view.MediaFrame) {
            return;
        }

        getUploadFrame().open();
    });
});
