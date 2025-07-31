jQuery(function ($) {
    
    function initMap( $el ) {
    
        // Find marker elements within map.
        var $markers = $el.find('.marker');
        
        // Create gerenic map.
        var mapArgs = {
            zoom        : $el.data('zoom') || 16,
            colorScheme : google.maps.ColorScheme.DARK,
            mapTypeId   : google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            fullscreenControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        };
        
        var map = new google.maps.Map( $el[0], mapArgs );

        // Add markers.
        map.markers = [];
        $markers.each(function(){
            initMarker( $(this), map );
        });

        // Center map based on markers.
        centerMap( map );

        // Return map instance.
        return map;
    }

    function initMarker( $marker, map ) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat( lat ),
            lng: parseFloat( lng )
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position : latLng,
            map: map
        });

        // Append to reference for later use.
        map.markers.push( marker );

        // If marker contains HTML, add it to an infoWindow.
        if( $marker.html() ){

            // Create info window.
            var infowindow = new google.maps.InfoWindow({
                content: $marker.html()
            });

            // Show info window when marker is clicked.
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open( map, marker );
            });
        }
    }

    function centerMap( map ) {

        // Create map boundaries from all map markers.
        var bounds = new google.maps.LatLngBounds();
        map.markers.forEach(function( marker ){
            bounds.extend({
                lat: marker.position.lat(),
                lng: marker.position.lng()
            });
        });

        // Case: Single marker.
        if( map.markers.length == 1 ){
            map.setCenter( bounds.getCenter() );

        // Case: Multiple markers.
        } else{
            map.fitBounds( bounds );
        }
    }
    // Initialize vendor map on page load.
    $('#vendor-page-map').each(function(){
        var map = initMap( $(this) );
    });

        
    let currentLat = '';
    let currentLng = '';

    const lightbox = GLightbox({
        openEffect: 'zoom',
        closeEffect: 'fade'
    });

    $('.card-map-trigger').on('click', function (e) {
        e.preventDefault(); // Cancelamos el comportamiento por defecto del anchor

        currentLat = $(this).data('lat');
        currentLng = $(this).data('lng');

        // Actualizamos el marcador del mapa original
        $('#home-page-map .marker')
        .attr('data-lat', currentLat)
        .attr('data-lng', currentLng);
    
        // Inyectamos el contenido al lightbox (NO lo clona automáticamente)
        lightbox.setElements([{
        content: $('#home-page-map')[0].outerHTML,
        type: 'inline'
        }]);

        lightbox.open();
        $('#glightbox-slider #home-page-map, #glightbox-slider .acf-map').css('height', '100%');
    
        // Esperamos a que se renderice el DOM del lightbox
        setTimeout(() => {
        
        $('.gslide-inline .acf-map').each(function () {
            initMap($(this));
        });
        }, 300);
        
    });

    // Opcional: al cerrar podemos limpiar el mapa base
    lightbox.onClose = () => {
        $('#home-page-map .marker')
        .attr('data-lat', '')
        .attr('data-lng', '');

        $('#home-page-map .acf-map').css('height', '0');
    };
});