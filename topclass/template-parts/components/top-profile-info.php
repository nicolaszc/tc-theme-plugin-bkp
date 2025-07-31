<div class="top-profile-info-box" >
    <div id="header-top-profile-info-box" class="position-absolute titular-container align-items-center d-flex justify-content-center flex-column">
    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/banners/top-banner.png"  alt="..." class="d-none d-md-block m-auto position-absolute z-0">
        <h1 id="header-name"></h1>
        <p class="m-0 position-relative z-1 mt-2 mt-md-0">
            <span class="atributos d-none d-md-inline-flex"><i class="fa-solid fa-location-dot" style="color:#6ecdff"></i><span id="header-location" class="m-0"></span></span>
            <span class="atributos"><a href="https://api.whatsapp.com/send?phone=<?php echo $vendor_phone; ?>&text=Hola <?php echo $vendor_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" target="_blank" id="header-phone-link"><i class="fa-brands fa-whatsapp"></i><span id="header-phone" class="m-0"></span></a></span>
            <span class="atributos"><i class="fa-solid fa-tag" style="color:#6ecdff"></i>CLP$<span id="header-price" class="m-0"></span> /hr</span>
        </p>
        <div id="header-availability" class="position-absolute"></div>
    </div>
</div> 