jQuery(function ($) {
    
    //initHomeLayout();
    setTimeout(function() {
      if (window.innerWidth < 768) {
        // MOBILE - SWIPER 
        var swiperInstance = new Swiper('.swiper', {
         slidesPerView: 1.15,
           spaceBetween: 16,
          loop: false,
          mousewheel: {
          forceToAxis: true,
          }, 
          speed: 600,
          lazy: true,
          observer: true,
          observeParents: true,
          resizeObserver: true,
          navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
         /*  effect: "cards",
      grabCursor: true, // Optional: Shows a 'grab' cursor on hover
      cardsEffect: {
        perSlideOffset: 20, // Space between cards in px
        perSlideRotate: 1, // Rotation of cards in degrees
      }, */
      // 
        });
      }
    }, 500);
});