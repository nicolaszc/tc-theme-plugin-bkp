// 1. Monitor para el botón específico de WooCommerce (Descargables)
document.addEventListener('click', function (event) {
    const isDownloadableButton = event.target.closest('.downloadable_files .upload_file_button');

    let currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('is_woo_download', '1');
    window.history.replaceState({}, '', currentUrl.toString());

    const activeFrame = window.wp && window.wp.media ? window.wp.media.frame : null;
    if (activeFrame) {
        activeFrame.on('close', function () {
            let cleanUrl = new URL(window.location.href);
            cleanUrl.searchParams.delete('is_woo_download');
            window.history.replaceState({}, '', cleanUrl.toString());
        });
    }
}
    // 2. Monitor para inyectar los radios en cualquier ventana general de medios
    ,

    // 2. Monitor para inyectar los radios en cualquier ventana general de medios
    document.addEventListener('click', function (event) {
        const mediaTrigger = event.target.closest('.thickbox, .upload_image_button, .set_image_icon, .media-modal, button.insert-media, #set-post-thumbnail, #menu-item-upload');

        if (mediaTrigger) {
            const observer = new MutationObserver(function (mutations, obs) {
                const uploaderInline = document.querySelector('.media-frame-content .uploader-inline');

                if (uploaderInline) {
                    obs.disconnect();

                    if (!document.querySelector('.topclass-s3-radio-box')) {
                        const box = document.createElement('div');
                        box.className = 'topclass-s3-radio-box';
                        box.style.cssText = 'background: #f0f0f1; padding: 12px 15px; margin: 0 auto 15px auto; max-width: 450px; border-radius: 4px; border: 1px solid #c3c4c7; text-align: left;';
                        box.innerHTML = `
                        <span style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #1d2327;">Destino del archivo en AWS S3:</span>
                        <label style="display: block; margin-bottom: 6px; cursor: pointer; font-size: 13px;">
                            <input type="radio" name="topclass_s3_mode" value="public" checked style="margin-right: 8px;"> 
                            🌐 <strong>Público</strong> (CDN / Perfil / Imágenes generales)
                        </label>
                        <label style="display: block; cursor: pointer; font-size: 13px; color: #b32d2e;">
                            <input type="radio" name="topclass_s3_mode" value="private" style="margin-right: 8px;"> 
                            🔒 <strong>Privado</strong> (S3 Seguro / Contenido descargable)
                        </label>
                    `;
                        uploaderInline.prepend(box);

                        const radios = box.querySelectorAll('input[name="topclass_s3_mode"]');
                        radios.forEach(radio => {
                            radio.addEventListener('change', function () {
                                let currentUrl = new URL(window.location.href);
                                currentUrl.searchParams.set('is_woo_download', '1');
                                window.history.replaceState({}, '', currentUrl.toString());
                            });
                        });
                    }
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }));
