jQuery(function ($){
$(document).ready(function() {
    var qsRegex;
    //initHomeLayout();
    setTimeout(function() {
      //if (window.innerWidth >= 768) {

        // DESKTOP - ISOTOPE
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
        //$grid.isotope({ sortBy: 'random' });

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

        // Isotope filter items on dropdown
        $('#district-filter-dropdown').on( 'click', 'button', function() {
          var filterValue = $(this).attr('data-value');
          //alert(filterValue);
          filterValue = filterValue;
          $grid.isotope({ filter: filterValue });
          //var contentHeight = Math.round($('body').height()) - bannerHeight;
          //$('.footer-bubbles').css('height', contentHeight);
        });

        // Isotope sort
        $('.card-sort-dropdown ul').on( 'click', 'button', function() {
          var sortByValue = $(this).attr('data-value');;
          $grid.isotope({ sortBy: sortByValue });
        });
      //}
}, 500);
})
});