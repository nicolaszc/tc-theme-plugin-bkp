jQuery(function ($) {
 
  //Hide banner scrolling height var
  var  mainBannerScrollingHeight = ($('.owl-main-slider').height() - $('.owl-main-slider .caption').height())/4;
  //var  topBannerScrollingHeight = $('.top-box').height()/2;
   ///////////// var  topBannerScrollingHeight = $('.top-box').height() - $('.top-banner-box').height();
  var opacityFactor;
  var scroll = $(window).scrollTop()-240;
  var hashValue = window.location.hash.substr(1);
  
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))  
  
  $('.account-toggler').append('<span class="d-inline-block d-md-none">Mi Cuenta</span>');
  $('.cart-toggler').append('<span class="d-inline-block d-md-none">Carro de compras</span>');
  $(document).ready(function() {
    if(hashValue){
      console.log(hashValue)
      $('html').animate({
        scrollTop: [$('#sticky-comments').offset().top - 240
    , 'linear']},300)
    }
    // Insertarlo en el lugar visible
    $('#dropdown-district-filter').html($('#district-dropdown').html());
    // Eliminar el contenedor original del DOM
    $('#district-dropdown').remove();
    // tomar data desde home.php para copiar en header
    $("#header-name").text($( "#main-name" ).text());
    $("#header-location").text($( "#main-location" ).text());
    $("#header-phone").text($( "#main-phone" ).text());
    var whatsapp = $( this ).find('.btn-whatsapp').attr('href');
    $("#header-phone-link").attr( 'href' , whatsapp);
    $("#header-price").text($( "#main-price" ).text());
    
    $('#commentsubmit').attr('data-scroll', '#to-commentform')
    $('.contact .nav-link').attr({
      'data-bs-toggle':'offcanvas',
      'data-bs-target':'#offcanvas-contact',
      'aria-controls':'offcanvas-contact'
    });
    $('#to-commentform, #to-commentform-fixed').on('click', function (event) {
      var clicked = $(this).attr('data-scroll');
      $('html').animate({
        scrollTop: [$(clicked).offset().top - 240
    , 'linear']},300)
    })
    /* $('#main-slider .item').each(function () {
      const $slide = $(this);
    
      // Clone the hidden global animation
      const $smoke = $('#global-smoke #smoke').clone().removeAttr('id').show();
      const $bubbles = $('#global-bubbles').clone().removeAttr('id').show();
      
      if($smoke){
        // Optionally tweak class here
        $smoke.addClass('slider-smoke-clone');
        // Inject in the desired place
        $slide.find('.caption.mobile').after($smoke);
      }
      
      if($bubbles){
        // Optionally tweak class here
        $bubbles.removeClass('footer-bubbles');
        $bubbles.addClass('banner-bubbles');
        // Inject in the desired place
        $slide.find('.caption.mobile').after($bubbles);
      }    
      
    }); */

  

    //Bubbles
    var bannerHeight = Math.round($('.owl-main-slider .item').height());
    var contentHeight = Math.round($('body').height()) - bannerHeight;   

    $('.banner-bubbles').css('height', bannerHeight);
    $('.footer-bubbles').css('height', contentHeight);
    $('.footer-bubbles').css('top', bannerHeight);
  
    
    $('.bubbles-wrapper .bubble').each(function() {
      var parentHeight = Math.round($(this).parent().height());
      var rndInt = Math.floor(Math.random() * 6) + 1;
      $(this).attr('data-speed', (Math.round(parentHeight/350)+rndInt));
    });
   
    //Owl carousel main silider
    $('.owl-main-slider').owlCarousel({
      loop:true,
      autoplay:true,
      rewind:false,
      autoplayHoverPause:true,
      animateOut: 'animate__fadeOutLeft',
      lazyLoad:true,
      mouseDrag: true,
      margin:0,
      nav:false,
      navText:["<div class='nav-btn prev-slide'><i class='fa-solid fa-chevron-left'></i></div>","<div class='nav-btn next-slide'><i class='fa-solid fa-chevron-right'></i></div>"],
      dots:true,
      dotsData:true,
      /*  dotsContainer: '.test', */
      responsive:{
          0:{
              items:1,
              autoplayTimeout:5000
          },
          600:{
              items:1
          },
          1000:{
              items:1
          }
      }
    })
    
    //Owl carousel profile slider
    $('.owl-profile-slider').owlCarousel({
      loop:true,
      autoplay:true,
      rewind:true,
      autoplayHoverPause:true,
      lazyLoad:true,
      mouseDrag: true,
      nav:false,
      navText:["<div class='nav-btn prev-slide'><i class='fa-solid fa-chevron-left'></i></div>","<div class='nav-btn next-slide'><i class='fa-solid fa-chevron-right'></i></div>"],
      dots:false,
      dotsData:true,
      /*  dotsContainer: '.test', */
      responsive:{
          0:{
              items:1.25
          },
          600:{
              items:2
          },
          1000:{
              items:3
          }
      }
    })

    //Owl carousel profile slider
    $('.owl-product-slider').owlCarousel({
      loop:true,
      autoplay:false,
      rewind:true,
      autoplayHoverPause:true,
      lazyLoad:true,
      mouseDrag: true,
      margin:24,
      nav:true,
      navText:["<div class='nav-btn prev-slide'><i class='fa-solid fa-circle-chevron-left'></i></div>","<div class='nav-btn next-slide'><i class='fa-solid fa-circle-chevron-right'></i></div>"],
      dots:false,
      dotsData:true,
      /*  dotsContainer: '.test', */
      responsive:{
          0:{
              items:1.25,
              margin:16,
              nav:false
          },
          600:{
              items:2
          },
          1000:{
              items:6
          }
      }
    })

    $('.owl-dot').click(function() {
      $('.owl-dot').trigger('to.owl.carousel', [$(this).index(), 1000]);
    }) 

    // Isotopeb var for searching
    var qsRegex;

    // Isotope init
    var $grid = $('.profiles-grid').isotope({
      // options
      itemSelector: '.profile-item',
      layoutMode: 'fitRows',
      stagger: 30,
      /* masonry: {
        columnWidth: '.grid-sizer'
      }, */
      filter: function() {
        return qsRegex ? $(this).text().match( qsRegex ) : true;
      },
      getSortData: {
        nombre: '.nombre',
        precio_ma: '.precio parseInt',
        precio_me: '.precio parseInt'
      },
      sortAscending: {
        nombre: true,
        precio_ma: false,
        precio_me: true
      }
    });

    // Isotope random on init
    $grid.isotope({ sortBy: 'random' });

    // use value of search field to filter
    var $quicksearch = $('.quicksearch').keyup( debounce( function() {
      qsRegex = new RegExp( $quicksearch.val(), 'gi' );
      $grid.isotope();
    }, 200 ) );

    // debounce so filtering doesn't happen every millisecond
    function debounce( fn, threshold ) {
      var timeout;
      threshold = threshold || 100;
      return function debounced() {
        clearTimeout( timeout );
        var args = arguments;
        var _this = this;
        function delayed() {
          fn.apply( _this, args );
        }
          timeout = setTimeout( delayed, threshold );
        };
    }

    // Isotope filter items on button click
    $('.filter-radio-button-group').on( 'click', 'input', function() {
      //alert('click');
      var filterValue = this.value;
      //alert(filterValue);
      filterValue = filterValue;
      $grid.isotope({ filter: filterValue });
      var contentHeight = Math.round($('body').height()) - bannerHeight; 
      $('.footer-bubbles').css('height', contentHeight);
    });

    // Isotope filter items on dropdown
    $('#district-filter-dropdown').on( 'click', 'button', function() {
      var filterValue = $(this).attr('data-value');
      //alert(filterValue);
      filterValue = filterValue;
      $grid.isotope({ filter: filterValue });
      var contentHeight = Math.round($('body').height()) - bannerHeight;
      $('.footer-bubbles').css('height', contentHeight);
    });

    // Isotope sort
    $('.card-sort-dropdown').on( 'click', 'button', function() {
      var sortByValue = $(this).attr('data-value');;
      $grid.isotope({ sortBy: sortByValue });
    });

    

    // Isotope toggle filters
    $('.toggle-top-box').click(function(e){
   
      if($('.top-box').hasClass('is-open')){
        $('.top-box').removeClass('is-open');

      }else{
        $('.top-box').addClass('is-open');
      }
    });

    // Google maps

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
     
  })

  $(window).on('scroll', function () {
    // Window scroll top var
    var scroll = $(window).scrollTop();

   

    function isElementVisible(el) {
  
      // Check if the element is out of bounds
      if(el.id=='main'){
        const rectHome = el.getBoundingClientRect();
        
        if (rectHome.bottom < 180 || rectHome.top > 180){
          $('.top-banner-box').addClass('show');
          $('.filter-sort').removeClass('show');
          //console.log('top-banner-box');
          return false;
        }else{
          $('.top-banner-box').removeClass('show');
          $('.filter-sort').addClass('show');
          //console.log('filter-sort');
        }
      }
      
      return true;
    }

    //console.log(window.location.href);
    if( $('body.page-template-home').length ){
      const profileCards = document.querySelector('#main');
      isElementVisible(profileCards);
    }
    
    $('.filter-sort .dropdown-toggle, .filter-sort .dropdown-menu').removeClass('show');
   


  }); 

 
  

}); 