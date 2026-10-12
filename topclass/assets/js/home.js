jQuery(function ($) {

    var scroll = $(window).scrollTop();

    // Insertarlo en el lugar visible
    $('#dropdown-district-filter').html($('#district-dropdown').html());
    // Eliminar el contenedor original del DOM
    $('#district-dropdown').remove();
    
    
    const $captionPlaceholder = $('#caption-placeholder');
    //============
    // Swiper Main
    //============
    function mainSliderInit() {
        
        //console.log('✅ Owl Carousel inicializado');
        SwiperAssignCaptionIds();
        setTimeout(() => {
        SwiperMoveCaptionBack(); // por si quedó algo
        SwiperMoveCaptionOut();
        $('.owl-dots').addClass('notranslate');
        $('.owl-dots').attr('translate','no');
        }, 100); // más delay para asegurarse de que todo esté renderizado
        
    }
    // Asigna IDs únicos a cada caption del slider original
    function SwiperAssignCaptionIds() {
        $('#main-swiper .swiper-slide').each(function (index) {
        const $caption = $(this).find('.text-captions');
        if ($caption.length) {
            $caption.attr('data-caption-id', 'caption-' + index);
        }
        });
    }

    // Mueve el caption del slide activo al placeholder
    function SwiperMoveCaptionOut() {
        const $activeSlide = $('.swiper-slide-active');
        const $caption = $activeSlide.find('.text-captions');
        var scroll = $(window).scrollTop();
        if ($caption.length && $captionPlaceholder.length) {
        $captionPlaceholder.empty().append($caption);
            if(scroll<90){
                setTimeout(() => {
                $caption.addClass('show-captions'); // <-- acá la agregás para mostrar con transición
                }, 250);
            }
        }
    }

    // Devuelve el caption al slide original o a uno vacío
    function SwiperMoveCaptionBack() {
        const $caption = $captionPlaceholder.find('.text-captions');
        const captionId = $caption.attr('data-caption-id');

        if ($caption.length) {
        $caption.removeClass('show-captions');
        // Buscar el slide original
        const $targetSlide = $('#main-swiper .swiper-slide')
            .filter(function () {
            return $(this).find('.text-captions').attr('data-caption-id') === captionId;
            });

        if ($targetSlide.length) {
            $targetSlide.find('.text-captions-container').append($caption);
            
            //console.log('🟢 Caption devuelta al slide original:', captionId);
        } else {
            // Fallback: encontrar el primer contenedor vacío
            $('.swiper-slide').find('.text-captions-container').each(function () {
            if ($(this).children().length === 0) {
                $(this).append($caption);
                //console.warn('🟡 Caption devuelta por fallback a contenedor vacío');
                return false;
            }
            });
        }
        }
    }

    

    var mainSwiperInstance = new Swiper('#main-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        direction: 'horizontal',
        speed: 600,
        lazy: true,
        dela: 1500,
        autoplay: true,
        pauseOnMouseEnter: true,
        mousewheel: {
        forceToAxis: true,
        },     
        pagination: {
            enabled: true,
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        on: {
        init: function () {
            pagination_width = ((($('.swiper-pagination').width()/2) - 10) * -1) + 12;
            mainSliderInit ()
            $('.swiper-pagination').css('left', pagination_width)
        },
        slideChangeTransitionStart: function(){
            SwiperMoveCaptionBack();
        },
        slideChangeTransitionEnd: function(){
            setTimeout(() => {
            SwiperMoveCaptionOut();
            }, 20);
        },
        },
        observer: true,
  observeParents: true,
  resizeObserver: true,
    });

    //===============
    //End Swiper main
    //===============
    
    
    //================
    //Swiper moods
    //================

    var moodsSwiperInstance = new Swiper('#moods-swiper', {
        slidesPerView: 2.5,
        spaceBetween: 0,
        loop: true,
        delay: 1000,
        direction: 'horizontal',
        autoplay: true,
        mousewheel: {
        forceToAxis: true,
        }, 
        observer: true,
  observeParents: true,
  resizeObserver: true,    
    });

    //================
    //End Swiper moods
    //================

    // Isotope toggle filters
    $('.toggle-top-box').click(function(e){
    
        if($('.top-box').hasClass('is-open')){
            $('.top-box').removeClass('is-open');
        }else{
            $('.top-box').addClass('is-open');
        }

    });

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

    if (scroll > 90){
        $('.moods').removeClass('show');
    }else{
        $('.moods').addClass('show');
    }
    
    var offset = $('header').offset().top;

    // On Scroll
    $(window).on('scroll', function () { 

        var scroll = $(window).scrollTop();

        if (scroll > 255) {
            $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').addClass('show');
        }else{
            $('.bottom-actions-menu, .contact-toggler, .account-toggler, .cart-toggler, .brand').removeClass('show');
        }

        if (scroll > 90){
            $('#caption-placeholder .text-captions').removeClass('show-captions');
            $('.moods').removeClass('show');
        }else{
            $('#caption-placeholder .text-captions').addClass('show-captions');
            $('.moods').addClass('show');
        }

        if (window.innerWidth < 768 && ($('body.page-template-home').length)) {
            if (scroll > 20 && scroll <= 300) {
                $('.bottom-actions-menu').removeClass('visible');
            }else if(scroll >= 300 || scroll < 20){
                $('.bottom-actions-menu').addClass('visible');
            }
        
            if ((Math.round(scroll) >= Math.round(offset) - 160) && (Math.round(scroll) <= Math.round(offset) + 630)){
                //.log("card zone");
  
                //$('.top-box').removeClass('is-open');  
                 
            }else{
                //console.log("not card zone");
                
                //$('.top-box').addClass('is-open'); 
                
            }
        }

        if (scroll > window.innerHeight){
            $('#main-swiper').removeClass('show');
        }else{
            $('#main-swiper').addClass('show');
        }

        // on scroll close filter dropdown       
        $('.filter-sort .dropdown-toggle, .filter-sort .dropdown-menu').removeClass('show');

    })

})