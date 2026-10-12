jQuery(function ($) {

    var hashValue = window.location.hash.substr(1); 

    if(hashValue){
    $('html').animate({
    scrollTop: [$('#sticky-comments').offset().top - 240
    , 'linear']},300)
    }

    $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').addClass('show');

    // tomar data desde del main para copiar en header
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

    //================
    //Swiper profile
    //================

    var profileSwiperInstance = new Swiper('#profile-swiper', {
        runCallbacksOnInit: true,
        observer: true,
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        speed: 600,
        lazy: true,
        autoplay: false,
        delay: 4000,
        direction: 'horizontal',      
        navigation:{
            enabled: true,
            nextEl: '.profile-swiper-button-next',
            prevEl: '.profile-swiper-button-prev',
            addIcons: true,
        },
        pagination: {
        enabled: false
        },
        mousewheel: {
        forceToAxis: true,
        }, 
        breakpoints: {
        768: {
            initialSlide: 1,
            slidesPerView: 3,
            pagination: {
            enabled: true,
            el: ".profile-swiper-pagination",
            clickable: true,
            dynamicBullets: true,
            },    
        },
        },
        on: {
        init: function () {           
            $('.profile-swiper .item  i').addClass('show');
        },
        slideChangeTransitionStart: function(){
            $('.profile-swiper .item  i').removeClass('show');
        },
        slideChangeTransitionEnd: function(){
            $('.profile-swiper .item  i').addClass('show');
            //$('.profile-swiper .item.swiper-slide-next i').css('font-size', '3rem');
        },
        },
    });

    //==================
    //End Swiper profile
    //==================

    //================
    //Swiper product
    //================

    var productSwiperInstance = new Swiper('#product-swiper', {
        slidesPerView: 1.5,
        spaceBetween: 16,
        loop: true,
        speed: 600,
        lazy: true,
        autoplay: true,
        delay: 4000, 
        direction: 'horizontal',
        pagination: {
        enabled: false
        },
        mousewheel: {
        forceToAxis: true,
        }, 
        breakpoints: {
        768: {
            slidesPerView: 6,
            spaceBetween: 16,
            pagination: {
            enabled: true,
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
            },    
        },
        },
        
    });

    //==================
    //End Swiper product
    //==================

    $('.gallery-counter').on( 'click', 'a', function() {
    var filter = $(this).attr('data-filter');
    
    $('#profile-swiper .swiper-slide').css('display', 'none')
    //$('#profile-swiper .swiper-slide').attr('data-gallery', '')
    
    if($(this).hasClass('swiper-active')){
        //var filter = '';
        $('#profile-swiper .swiper-slide').css('display', '');
        //$( this ).removeClass( 'swiper-active' );
        $( this ).removeClass( 'swiper-active' );
        //$('#profile-swiper .swiper-slide a').attr('data-gallery', '')
        //$('#profile-swiper .swiper-slide').attr('data-gallery', '')
        //alert('has');
    }else{
    $( '.gallery-counter a' ).removeClass( 'swiper-active' );
    $( this ).addClass( 'swiper-active' );
    $('#profile-swiper .swiper-slide' + '.' + filter).css('display', '')
    $('#profile-swiper .swiper-slide' + '.' + filter).data('gallery', filter)
    $('#profile-swiper .swiper-slide' + '.' + filter).attr('data-gallery', filter)

    //$('#profile-swiper .swiper-slide' + '.' + filter).attr('data-gallery', filter)
    //alert($('#profile-swiper .swiper-slide .glighbox' + '.' + filter).attr('data-gallery'));
    }
    profileSwiperInstance.updateSize();
    profileSwiperInstance.updateSlides();
    profileSwiperInstance.updateProgress();
    profileSwiperInstance.updateSlidesClasses();
    profileSwiperInstance.slideTo(0);
    profileSwiperInstance.scrollbar.updateSize();
    
    return false;
  });
})