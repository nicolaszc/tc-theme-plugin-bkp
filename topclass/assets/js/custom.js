jQuery(function ($) {
  
  var scroll = $(window).scrollTop();
  
  // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
  let vh = window.innerHeight * 0.01;

  // Then we set the value in the --vh custom property to the root of the document
  document.documentElement.style.setProperty('--vh', `${vh}px`);
  const titleElement = document.querySelector('head title');
  if (titleElement) { // Check if the title element exists
      titleElement.setAttribute('translate', 'no');
  }


  /* document.addEventListener("contextmenu", function (e){
    e.preventDefault();
  }, false); */

  $('.account-toggler').append('<span class="d-inline-block d-md-none">Mi Cuenta</span>');
  $('.cart-toggler').append('<span class="d-inline-block d-md-none">Carro de compras</span>');
  
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
    //console.log($(this).width())
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
  
 

  // On Scroll
  $(window).on('scroll', function () {   

    $('.share-rrss').addClass('invisible'); // Oculta todos los iconos
    $('.share-btn .iconOpen').removeClass('d-none');    // Muestra el primer icono de todos
    $('.share-btn .iconClose').addClass('d-none');       // Oculta el segundo icono de todos
    //Pause open button animation and hide menu on scroll stop
    animated.style.animationPlayState="paused";
    
    $('.bottom-actions-menu').removeClass('open');
    $('.sub-menu-btn').each(function(){
      $(this).removeClass('show');
    });

  }); 
   //Bubbles

    if(window.innerWidth < 768){
      var bannerHeight = ($('.swiper-wrapper').height());
      var galleryHeight = ($('.slider-row').height());
 
      var totalHeight = galleryHeight+160;
      var homeContentHeight = ($('html').height()) - bannerHeight;   
      var profileContentHeight = ($('html').height()) - totalHeight ; 
        
      $('.home .footer-bubbles').css('height', Math.round(homeContentHeight - 97));
      $('.home .footer-bubbles').css('top', Math.round(bannerHeight));

      $('.page-template-page-modelo .footer-bubbles').css('top', Math.round(totalHeight));
      $('.page-template-page-modelo .footer-bubbles').css('height', Math.round(profileContentHeight));

    }else{
      var bannerHeight = ($('.swiper-wrapper').height());
      
      var totalHeight = bannerHeight+180;
      var homeContentHeight = ($('html').height()) - bannerHeight;   
      var profileContentHeight = ($('html').height()) - totalHeight;  
      
      $('.home .footer-bubbles').css('height', Math.round(homeContentHeight - 37));
      $('.home .footer-bubbles').css('top', Math.round(bannerHeight));

      $('.page-template-page-modelo .footer-bubbles').css('top', Math.round(totalHeight));
      $('.page-template-page-modelo .footer-bubbles').css('height', Math.round(profileContentHeight));

    }
    
    $('.bubbles-wrapper .bubble').each(function() {
      var parentHeight = Math.round($(this).parent().height());
      var rndInt = Math.floor(Math.random() * 6) + 1;
      $(this).attr('data-speed', (Math.round(parentHeight/350)+rndInt));
    });

    
}); 