
<div class="filter-sort">
    <div class="filter-container d-flex justify-content-between align-items-center">

        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/banners/top-banner-box-center.webp"  alt="..." class="d-none d-md-block m-auto position-absolute">
        
        <div class="button-group filter-group d-flex align-items-center">
            <div id="dropdown-district-filter"></div>
        </div>
             
        <div class="btn-group card-sort-dropdown">
            <button class="btn btn-secondary dropdown-toggle px-0 px-md-4 py-2 rounded-pill" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"><span>
                Ordenar <i class="fa-solid fa-arrow-up-wide-short"></i><span>
            </button>
            <ul class="dropdown-menu w-100" id="card-sort-dropdown">
                <li><button class="dropdown-item" type="input" data-value="nombre">Nombre</button></li>
                <li><button class="dropdown-item" type="input" data-value="precio_ma">Precio Mayor</button></li>
                <li><button class="dropdown-item" type="input" data-value="precio_me">Precio Menor</button></li>
                <li><button class="dropdown-item" type="input" data-value="random" checked>Aleatorio</button></li>
                <li class="drop-down-last-item" style="border-bottom-left-radius: 6px !important; border-bottom-right-radius: 6px !important; height: 5px; background-color: rgb(from var(--light-color) r g b / .9);"></li>
            </ul>
        </div>
         
    </div>
</div>
