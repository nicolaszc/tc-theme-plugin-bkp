jQuery(function ($) {
    
    //initHomeLayout();
    setTimeout(function() {
      if (window.innerWidth < 768) {
        // MOBILE - SWIPER 
        var swiperInstance = new Swiper('.swiper', {
          slidesPerView: 1.15,
          spaceBetween: 16,
          grabCursor: true,
          loop: false,

          /* freeMode: {
            enabled: false,
            momentum: false,
            momentumBounce: true,
            sticky: true,
            momentumRatio: 0,
            minimumVelocity: 1,
            momentumVelocityRatio: 0.5,
           },*/
          freeMode: false,          // Apaga el 'momentum libre'
          centeredSlides: false,    // No centra los slides
          resistanceRatio: 0,       // No permite “estirar” antes de cambiar
          slideToClickedSlide: false, // No permite saltear slides al hacer click
          speed: 600,
          lazy: true,
        });
      }
    }, 500);
});