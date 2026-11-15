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

    function calculateStickyPos() {
        var topOffset = 230;
        var bottomOffset = 50

        var wih = Math.round(window.innerHeight);
        var availableHeight = wih - (topOffset + bottomOffset);
        
        var leftColHeight = Math.round($('.profile-info').height());
        var rightColHeight = Math.round($('.media-tabs').height());

        var leftColStickyPos = -((leftColHeight - wih) +  bottomOffset);
        var rightColStickyPos = -((rightColHeight - wih) +  bottomOffset);

        var scrollDir = ((document.body.getBoundingClientRect()).top > scrollPos) ? 'Up' : 'Down';


        // Si la columna derecha es más corta que el espacio visible disponible, se fija en 230px
        if (rightColHeight <= availableHeight) {
            $('.media-tabs').css('top', topOffset);
        } else {
            // Aplica el top dinámico según la dirección del scroll
            if (scrollDir == "Up") {
		        console.log('UP - LCH: ' + leftColHeight + '/' + $('.profile-info').css('top') + '/' + $('.profile-info').offset().top + ' - RCH: ' + rightColHeight + '/' + $('.media-tabs').css('top') + '/' + $('.media-tabs').offset().top);
                $('.media-tabs').css('top', topOffset);
            } else {
                console.log('DOWN - LCH: ' + leftColHeight + '/' + $('.profile-info').css('top') + '/' + $('.profile-info').offset().top + ' - RCH: ' + rightColHeight + '/' + $('.media-tabs').css('top') + '/' + $('.media-tabs').offset().top);
                $('.media-tabs').css('top', -((rightColHeight - wih) + 50));
            }
        }

        // Misma evaluación para la columna de información
        if (leftColHeight <= availableHeight) {
            $('.profile-info').css('top', topOffset);
        } else {
            if (scrollDir == "Up") {
                console.log('UP - LCH: ' + leftColHeight + '/' + $('.profile-info').css('top') + '/' + $('.profile-info').offset().top + ' - RCH: ' + rightColHeight + '/' + $('.media-tabs').css('top') + '/' + $('.media-tabs').offset().top);
                $('.profile-info').css('top', topOffset);
            } else {
                console.log('DOWN - LCH: ' + leftColHeight + '/' + $('.profile-info').css('top') + '/' + $('.profile-info').offset().top + ' - RCH: ' + rightColHeight + '/' + $('.media-tabs').css('top') + '/' + $('.media-tabs').offset().top);
                $('.profile-info').css('top', -((leftColHeight - wih) + 50));
            }
        }

        scrollPos = (document.body.getBoundingClientRect()).top;

    }
    

    $('.profile-info').css('top', 230);
    $('.media-tabs').css('top', 230);
    var scrollPos = 0;
    
    $(window).on('scroll', function () {   
       

        calculateStickyPos();
    })

    // Se ejecuta justo después de que la pestaña nueva se hace visible
    $('a[data-toggle="tab"], button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {       

        calculateStickyPos();
        tabId = e.target.id;
        console.log(tabId);
        if(tabId == "nav-gallery-tab"){
            $('.counter').addClass('d-flex').removeClass('d-none');
            console.log('visible');
            var profileContentHeight = ($('html').height()) - 160 ; 
            console.log(profileContentHeight);
            $('.page-template-page-modelo .footer-bubbles').css('height', Math.round(profileContentHeight));

        }else{
            $('.counter').addClass('d-none').removeClass('d-flex');
            console.log('invisible');
            var profileContentHeight = ($('html').height()) - 160 ; 

            console.log(profileContentHeight);
            $('.page-template-page-modelo .footer-bubbles').css('height', Math.round(profileContentHeight));


        }
       
    });

    
    function transitionEnd(){
        console.log("Transition ended");
        calculateStickyPos();
       

       
    }

    $('.counter a').on( "click", function(e) {
        var itemType = $(this).data('filter');
        var opositeItemType = (itemType == 'image') ? 'video' : 'image';
        console.log(itemType);
        if($(this).hasClass('item-type-selected')){
            $('.gallery .item').removeClass('hide');
            $('.counter a').removeClass('item-type-selected');
        }else{

            $('.gallery .item.' + opositeItemType).addClass('hide');
            $('.gallery .item.' + itemType).removeClass('hide');
            
            $('.counter a.' + opositeItemType + '-filter').removeClass('item-type-selected');
            $(this).addClass('item-type-selected');
        }

       
        var galleryItems = $('.gallery .item');
        
        galleryItems.on('transitionend', transitionEnd());


    });


})