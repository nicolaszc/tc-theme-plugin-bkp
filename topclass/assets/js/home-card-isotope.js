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
            masonry: {
              columnWidth: 306,
              gutter: 24,
              isFitWidth: true
            },
            /* percentPosition: true,
            masonry: {
              columnWidth: '.grid-sizer'
            }, */
            
             /* layoutMode: 'fitRows',
            fitRows: {
              horizontalAlignment: 0.5,
            },
            stagger: 30,layoutMode: 'fitRows',
            masonry: {
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
            $('html').animate({
              scrollTop: [$('#main-container').offset().top - 180
              , 'linear']},300);
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
            filterValue = filterValue;
            $grid.isotope({ filter: filterValue });
          });

          // Isotope sort
          $('.card-sort-dropdown ul').on( 'click', 'button', function() {
            var sortByValue = $(this).attr('data-value');
            $grid.isotope({ sortBy: sortByValue });
          });
        //}
  }, 500);
  })


});