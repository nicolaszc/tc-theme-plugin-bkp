jQuery(function ($) {
    $(window).on('scroll', function () {

        if (window.innerWidth >= 768) {
            // check if user is on card zone
            function isCardZoneVisible(el) {
            // Check device window width
            
            // Check if the element is out of bounds
                if(el.id=='main'){
                
                const rectHome = el.getBoundingClientRect();
                
                if (rectHome.bottom < 230 || rectHome.top > 230){
                    $('.top-banner-box').addClass('show');
                    $('.search-filter-sort').removeClass('opacity-100 pe-auto');
                    $('.filter-sort').removeClass('show');
                    //console.log('top-banner-box');
                    return false;
                }else{
                    $('.top-banner-box').removeClass('show');
                    $('.filter-sort').addClass('show');
                    $('.search-filter-sort').addClass('opacity-100 pe-auto');
                    //console.log('filter-sort');
                }

                }           
            
            return true;
            }

            // asign constant for checking if user is on card zone
            const profileCards = document.querySelector('#main');
            // check if user is in home page and trigger the filter show function
            if( $('body.page-template-home').length ){
            
                isCardZoneVisible(profileCards);
            }
               
        }
        

    }); 
});