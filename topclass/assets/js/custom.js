jQuery(function ($) {
  
  //var opacityFactor;
  var scroll = $(window).scrollTop();
  var hashValue = window.location.hash.substr(1); 
  
  // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
  let vh = window.innerHeight * 0.01;
  // Then we set the value in the --vh custom property to the root of the document
  document.documentElement.style.setProperty('--vh', `${vh}px`);
  const titleElement = document.querySelector('head title');
  if (titleElement) { // Check if the title element exists
      titleElement.setAttribute('translate', 'no');
  }
  $('.account-toggler').append('<span class="d-inline-block d-md-none">Mi Cuenta</span>');
  $('.cart-toggler').append('<span class="d-inline-block d-md-none">Carro de compras</span>');

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
  $("#header-availability").html($( "#availability" ).html()); 
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

  
  const $captionPlaceholder = $('#caption-placeholder');
  
  
  // Asigna IDs únicos a cada caption del slider original
  function assignCaptionIds() {
    $('.owl-main-slider .owl-item').not('.cloned').each(function (index) {
      const $caption = $(this).find('.text-captions');
      if ($caption.length) {
        $caption.attr('data-caption-id', 'caption-' + index);
      }
    });
  }

  // Mueve el caption del slide activo al placeholder
  function moveCaptionOut() {
    const $activeSlide = $('.owl-item.active');
    const $caption = $activeSlide.find('.text-captions');
    var scroll = $(window).scrollTop();
    if ($caption.length && $captionPlaceholder.length) {
      $captionPlaceholder.empty().append($caption);
      if(scroll<90){
          setTimeout(() => {
          $caption.addClass('show-captions'); // <-- acá la agregás para mostrar con transición
        }, 250);
      }
      //console.log('🔵 Caption movida afuera:', $caption.attr('data-caption-id'));
    }
  }

  // Devuelve el caption al slide original o a uno vacío
  function moveCaptionBack() {
    const $caption = $captionPlaceholder.find('.text-captions');
    const captionId = $caption.attr('data-caption-id');

    if ($caption.length) {
      $caption.removeClass('show-captions');
      // Buscar el slide original
      const $targetSlide = $('.owl-main-slider .owl-item')
        .filter(function () {
          return $(this).find('.text-captions').attr('data-caption-id') === captionId;
        });

      if ($targetSlide.length) {
        $targetSlide.find('.text-captions-container').append($caption);
        
        //console.log('🟢 Caption devuelta al slide original:', captionId);
      } else {
        // Fallback: encontrar el primer contenedor vacío
        $('.owl-item').not('.cloned').find('.text-captions-container').each(function () {
          if ($(this).children().length === 0) {
            $(this).append($caption);
            //console.warn('🟡 Caption devuelta por fallback a contenedor vacío');
            return false;
          }
        });
      }
    }
  }

  // ========================
  // Inicialización Owl + lógica de captions
  // ========================

  $('.owl-main-slider').on('initialized.owl.carousel', function () {
    
    //console.log('✅ Owl Carousel inicializado');
    assignCaptionIds();
    setTimeout(() => {
      moveCaptionBack(); // por si quedó algo
      moveCaptionOut();
      $('.owl-dots').addClass('notranslate');
      $('.owl-dots').attr('translate','no');
    }, 100); // más delay para asegurarse de que todo esté renderizado
    
  });

  $('.owl-main-slider').on('change.owl.carousel', function () {
    //console.log('⏳ Cambio de slide iniciado');
    moveCaptionBack();
  });

  $('.owl-main-slider').on('changed.owl.carousel', function () {
    //console.log('✅ Cambio de slide completado');
    setTimeout(() => {
      moveCaptionOut();
    }, 20);
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
  
  //Owl carousel moods slider
  $('#slider-moods').owlCarousel({
    loop:true,
    autoplay:true,
    rewind:true,
    autoplayHoverPause:true,
    lazyLoad:true,
    mouseDrag: true,
    margin:10,
    nav:false,
    navText:["<div class='nav-btn prev-slide'><i class='fa-solid fa-circle-chevron-left'></i></div>","<div class='nav-btn next-slide'><i class='fa-solid fa-circle-chevron-right'></i></div>"],
    dots:false,
    dotsData:true,
    responsive:{
        0:{
            items:2.5
        },
        600:{
            items:4
        },
        1000:{
            items:5
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

  //Owl carousel product slider
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
  

  // Isotope toggle filters
  $('.toggle-top-box').click(function(e){
  
    if($('.top-box').hasClass('is-open')){
      $('.top-box').removeClass('is-open');

    }else{
      $('.top-box').addClass('is-open');
    }
  });
  
  // Open btn animation
  const animated = document.querySelector(".open-button");

  function repeatAnimation() {
    animated.classList.remove("shakeY");

    // Trigger reflow para reiniciar animación
    void animated.offsetWidth;

    animated.classList.add("shakeY");
  }

  // Wait until 4 cycle animation end
  animated.addEventListener("animationend", () => {
    setTimeout(repeatAnimation, 15000); // Pause  5 sec afeter last movement
  });

  //Restart open button animation non scroll stop
  let scrollTimeout;

  window.addEventListener('scroll', function(e) {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(function() {
      animated.style.animationPlayState="running";
      console.log("Scrolling has stopped.");
    }, 250); // Adjust the timeout value (in milliseconds) as needed
  });

  function openSubmenu(origin){

    if(origin =='auto'){    
      autoCloseSubmenu()
    }
    if($('.bottom-actions-menu').hasClass('open')){
      $('.bottom-actions-menu').removeClass('open');
      $('.sub-menu-btn').each(function(){
        $(this).removeClass('show');
      });
      animated.style.animationPlayState="running";
    }else{
      $('.bottom-actions-menu').addClass('open');
      $('.sub-menu-btn').each(function(){
      $(this).addClass('show');
      });
      animated.style.animationPlayState="paused";
    }
  }

  function autoCloseSubmenu(){
    setTimeout(function(){
      $('.bottom-actions-menu').removeClass('open');
      $('.sub-menu-btn').each(function(){
        $(this).removeClass('show');
      });
      animated.style.animationPlayState="running";
    }, 3000)
    
  } 
  // Open btn click
   $('.open-button').click(function(e){
    if(window.innerWidth < 768){
      e.preventDefault();
      openSubmenu('btn');
    }
  }); 

  // Open btn hover
  $('.bottom-actions-menu').hover(
    function(e){
    animated.style.animationPlayState="paused";
    console.log($(this).width())
    openSubmenu('btn');
    },
    function(e){
      animated.style.animationPlayState="running";
      openSubmenu('btn');
    } 
  );
  
  const isAtTop = window.scrollY === 0;
  setTimeout(function(){
    if (isAtTop) {
    openSubmenu('auto');
    }
  }, 3000)

  $('.contact-actions').on('click', '.share-btn', function() {

    // Encuentra la caja de los tres iconos dentro de la misma card
    var sharebox = $(this).closest('.contact-actions').find('.share-rrss');
    var iconOpen = $(this).find('.iconOpen');
    var iconClose = $(this).find('.iconClose');
    
    // Verifica si la caja de iconos está oculta o visible
    if (sharebox.hasClass('invisible')) {
        $('.share-rrss').addClass('invisible'); // Oculta todos los iconos
        $('.share-btn .iconOpen').removeClass('d-none');    // Muestra el primer icono de todos
        $('.share-btn .iconClose').addClass('d-none');       // Oculta el segundo icono de todos
        
        sharebox.removeClass('invisible'); // Muestra los iconos
        iconOpen.addClass('d-none');           // Oculta el primer icono
        iconClose.removeClass('d-none');        // Muestra el segundo icono
    } else {
        
        sharebox.addClass('invisible'); // Oculta los iconos
        iconOpen.removeClass('d-none');    // Muestra el primer icono
        iconClose.addClass('d-none');       // Oculta el segundo icono
    }
});
  
  //var fsEl = $(window.parent.document).find('#site-holder');

  $('.full-screen-button').click(function(){
      toggleFullScreen();
  })
  
  function toggleFullScreen() {
    if (!window.parent.document.fullscreenElement && !window.parent.document.mozFullScreenElement && !window.parent.document.webkitFullscreenElement && !window.parent.document.msFullscreenElement) {
      // Para solicitar fullscreen en diferentes navegadores
      if (document.documentElement.requestFullscreen) {
        window.parent.document.documentElement.requestFullscreen(); // Para navegadores modernos
      } else if (document.documentElement.mozRequestFullScreen) { // Firefox
        window.parent.document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullscreen) { // Chrome, Safari y Opera
        window.parent.document.documentElement.webkitRequestFullscreen();
      } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
        window.parent.document.documentElement.msRequestFullscreen();
      }

      // Cambiar el estado del botón
      $('.full-screen-button .expand').addClass('d-none');
      $('.full-screen-button .compress').removeClass('d-none');
    } else {
      // Si ya está en fullscreen, salir de fullscreen según el navegador
      if (document.exitFullscreen) {
        window.parent.document.exitFullscreen(); // Para navegadores modernos
      } else if (document.mozCancelFullScreen) { // Firefox
        window.parent.document.mozCancelFullScreen();
      } else if (document.webkitExitFullscreen) { // Chrome, Safari y Opera
        window.parent.document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) { // IE/Edge
        window.parent.document.msExitFullscreen();
      }

      // Cambiar el estado del botón
      $('.full-screen-button .expand').removeClass('d-none');
      $('.full-screen-button .compress').addClass('d-none');
    }
  }
  
  // var for detecting sticky
  var offset = $('header').offset().top;
  // show alpha elemnts if so
  if (scroll > 255) {
    $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').addClass('show');
  }else{
    $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').removeClass('show');
  }
  if (scroll > 90){
    $('.moods').removeClass('show');
  }else{
    $('.moods').addClass('show');
  }
  // On Scroll
  $(window).on('scroll', function () {   

    $('.share-rrss').addClass('invisible'); // Oculta todos los iconos
    $('.share-btn .iconOpen').removeClass('d-none');    // Muestra el primer icono de todos
    $('.share-btn .iconClose').addClass('d-none');       // Oculta el segundo icono de todos
    //Pause open button animation and hide menu on scroll stop
    animated.style.animationPlayState="paused";

    var scroll = $(window).scrollTop();
    console.log('scroll:' + scroll);
    //console.log('offset:' + offset);
    if (scroll > 90){
       $('#caption-placeholder .text-captions').removeClass('show-captions');
       $('.moods').removeClass('show');
    }else{
      $('#caption-placeholder .text-captions').addClass('show-captions');
      $('.moods').addClass('show');
    }
    if (window.innerWidth < 768 && $('body.page-template-home').length) {
      if (scroll > 20 && scroll <= 300) {
        $('.bottom-actions-menu').removeClass('visible');
      }else if(scroll >= 300 || scroll < 20){
        $('.bottom-actions-menu').addClass('visible');
      }
   
      if ((Math.round(scroll) >= Math.round(offset) - 160) && (Math.round(scroll) <= Math.round(offset) + 630)){
        console.log("card zone");
        $('.top-box').removeClass('is-open');     
      }else{
        console.log("not card zone");
        $('.top-box').addClass('is-open'); 
      }
    }
    if (scroll > 255) {
      $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').addClass('show');
    }else{
      $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').removeClass('show');
    }
    $('.bottom-actions-menu').removeClass('open');
    $('.sub-menu-btn').each(function(){
      $(this).removeClass('show');
    });
    
    // on scroll close filter dropdown       
    $('.filter-sort .dropdown-toggle, .filter-sort .dropdown-menu').removeClass('show');

  }); 

}); 