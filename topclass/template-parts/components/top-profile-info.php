<div class="profile-header-wrappper">  
    <div class="profile-header-box container-md" >  
        <div class="row">                
            <div class="col-md-12 ">  
            
                <div id="profile-header-info" class="profile-header-info position-absolute align-items-center d-flex justify-content-between flex-column w-100 py-3">
                    
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/banners/top-banner.png"  alt="..." class="d-none d-md-block m-auto position-absolute z-0">
                    
                    <h1 id="header-name"></h1>
                    
                    <div class="m-0 position-relative z-1 mt-2 mt-md-0 d-flex">

                        <div class="d-flex me-3 align-items-center">
                            <span class="atribute-icon d-none d-md-inline-flex me-1">
                                <i class="fa-solid fa-location-dot" style="color:#6ecdff"></i>
                            </span>
                            <span id="header-location" class="m-0 lh-1"></span>
                        </div>

                        <a href="https://api.whatsapp.com/send?phone=<?php echo $vendor_phone; ?>&text=Hola <?php echo $vendor_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" target="_blank" id="header-phone-link" class="d-flex me-3 align-items-center">
                            <span class="atribute-icon position-relative me-1">
                                <i class="fa-brands fa-whatsapp"></i>
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <span id="header-phone" class="m-0 lh-1"></span>
                        </a>

                        <div class="d-flex me-3 align-items-center">
                            <span class="atribute-icon me-1">
                                <i class="fa-solid fa-tag" style="color:#6ecdff"></i>
                            </span>
                            <span class="lh-1">CLP$<span id="header-price" class="ms-1"></span>/hr<span>
                        </div>

                    </div>

                    <div id="header-availability" class="position-absolute"></div>

                </div>

            </div> 
        </div> 
    </div>                      
</div>