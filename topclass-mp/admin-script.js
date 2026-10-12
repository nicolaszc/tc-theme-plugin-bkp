
/* document.addEventListener('DOMContentLoaded', function () {
    const downloadableCheckbox = document.querySelector('#_downloadable');

    if (downloadableCheckbox) {
        // Función para inyectar o remover el parámetro de la barra del navegador
        const toggleUrlParameter = function() {
            // Creamos un objeto URL basado en la dirección actual del navegador
            let currentUrl = new URL(window.location.href);

            if (downloadableCheckbox.checked) {
                // Añadimos el parámetro de forma visible arriba
                currentUrl.searchParams.set('is_woo_download', '1');
                console.log('TopClass: [URL Browser] Parámetro inyectado en la barra de direcciones.');
            } else {
                // Lo removemos si se desmarca
                currentUrl.searchParams.delete('is_woo_download');
                console.log('TopClass: [URL Browser] Parámetro removido de la barra de direcciones.');
            }

            // Cambiamos la URL de la barra de direcciones en caliente SIN recargar la página
            window.history.replaceState({}, '', currentUrl.toString());
        };

        // 1. Escuchamos cuando el usuario cambia el estado del checkbox manualmente
        downloadableCheckbox.addEventListener('change', toggleUrlParameter);

        // 2. Lo ejecutamos al cargar por si el producto ya venía guardado como descargable
        toggleUrlParameter();
    }
}); */


document.addEventListener('DOMContentLoaded', function () {
    // 1. Verificamos que la API de medios de WordPress esté disponible
    if (typeof window.wp !== 'undefined' && window.wp.media) {
        console.log('TopClass: Monitor de Medios Inteligente activado.');

        // 2. Escuchamos de forma global el clic en el botón específico de WooCommerce para archivos descargables
        document.addEventListener('click', function (event) {
            // Detectamos si el clic provino del botón de archivos descargables de WooCommerce
            const isDownloadableButton = event.target.closest('.downloadable_files .upload_file_button');

            if (isDownloadableButton) {
                console.log('TopClass: Ventana abierta desde botón Descargable. Activando modo privado...');
                
                // Inyectamos el parámetro arriba en la barra de direcciones inmediatamente
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('is_woo_download', '1');
                window.history.replaceState({}, '', currentUrl.toString());

                // 3. Nos enganchamos al frame activo actual que abrió WordPress
                const activeFrame = window.wp.media.frame;
                if (activeFrame) {
                    // Si el usuario cierra la ventana o presiona escapar sin subir nada, limpiamos la barra
                    activeFrame.on('close', function() {
                        console.log('TopClass: Ventana cerrada. Restableciendo entorno público...');
                        let cleanUrl = new URL(window.location.href);
                        cleanUrl.searchParams.delete('is_woo_download');
                        window.history.replaceState({}, '', cleanUrl.toString());
                    });
                }
            }
        });

        // 4. SEGURIDAD: Limpieza post-subida.
        // Nos aseguramos de limpiar la barra de direcciones en cuanto CUALQUIER archivo termine de subir,
        // garantizando que si el usuario cambia el thumbnail justo después, este vaya a la carpeta pública.
        if (window.wp.Uploader && window.wp.Uploader.queue) {
            window.wp.Uploader.queue.on('add', function() {
                // Cuando el uploader termine su ciclo con el archivo descargable, limpiamos la barra
                setTimeout(function() {
                    let cleanUrl = new URL(window.location.href);
                    if (cleanUrl.searchParams.has('is_woo_download')) {
                        cleanUrl.searchParams.delete('is_woo_download');
                        window.history.replaceState({}, '', cleanUrl.toString());
                        console.log('TopClass: Subida procesada de forma segura. Barra restablecida a público.');
                    }
                }, 1000); // Pequeño buffer de 1 segundo para asegurar que PHP termine de leer el referer
            });
        }
    }
});
