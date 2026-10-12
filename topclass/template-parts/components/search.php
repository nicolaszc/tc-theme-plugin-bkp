<?php if(is_front_page() || is_page_template('home-grid.php')) : ?>
    <div class="w-100 d-md-flex">
        <div class="search-bar search-bar d-flex justify-content-center align-items-center">  
            <div class="input-group search-filter-sort-r rounded-pill show">
                <input id="search-input" type="text" class="border-white form-control rounded-start-pill search-input quicksearch" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="search">
                <button class="input-group-text btn btn-primary rounded-end-pill px-4 search-btn" id="search-btn"  data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            
        </div>
        <small class="mt-3 mb-5 d-block d-md-none text-center">* Buscar por nombre de la modelo.</small>
    </div>
<?php endif; ?>
            