<?php
/**
 * The template for displaying the footer
*/
// Exit if accessed directly
defined('ABSPATH') || exit;
?>
      
      </main>
    </div><!-- /Main Container -->
    <!-- /page -->
    <?php 
      
      $classes = get_body_class();
      if (in_array('page-template-page-modelo',$classes)) :   
        comments_template();  
      endif;
    ?>
    
    <!-- Footer -->
    <footer id="footer" class="pt-md-5 position-relative">
      
      <?php if(!is_checkout()) { ?>
        <!-- Silueta -->
        <div class="silueta-mobile position-absolute d-block d-md-none">
          <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/silueta-mobile.svg" />
        </div>
        <div class="silueta-desktop position-absolute d-none d-md-block">
          <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/silueta-desktop.svg" />
        </div>
         <!-- /Silueta -->
      <?php 
          get_template_part('template-parts/components/partners');
        }
      ?>
      <!-- Footer Gradient -->  
      <div class="<?php if(!is_checkout()) { ?>footer-gradient<?php } ?>">
        <div class="container">  
          <div class="row mt-5 text-light justify-content-center">

            <?php if(!is_checkout()) { ?>             
              <!-- Newsletter -->
              <div class="col col-md-4 text-center pb-5 newsletter mb-5 mb-md-0">
                <p class="mb-0">Mantente informado</p> 
                <h5 class="mb-3">Suscríbete a nuestro newsletter</h5> 
                <div class="input-group mx-auto mb-0 w-75 rounded-pill">
                    <input type="text" class="border-white form-control rounded-start-pill search-input quicksearch" placeholder="email" aria-label="email">
                    <button class="input-group-text btn btn-primary rounded-end-pill px-4" id="newsletter">
                      <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
              </div>
              <!-- /Newsletter -->
              <!-- RRSS -->
              <div class="col-12 d-flex align-items-center justify-content-center rrss">
                Síguenos en: 
                <a href="https://x.com/topclassclub" target="_blank" class="ps-2 pe-1"><i class="fa-brands fa-x-twitter"></i></a>
						    <a href="https://t.me/topclassClub" target="_blank" class="px-1 px-1"><i class="fa-brands fa-telegram"></i></a>
						    <a href="https://api.whatsapp.com/send?phone=56977253992&text=Hola%20TopclassClub%20quiero%20saber..." target="_blank" class="px-1 ps-2"><i class="fa-brands fa-whatsapp"></i></a>
              </div>
              <!-- /RRSS -->
              <!--  Brand -->
              <div class="col-6 col-md-3 pt-4 pb-5">
                <!-- <a href="#" class="m-auto"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/logo/topclass-logo-white.svg" class="w-35" /></a> -->
                <?php get_template_part('template-parts/components/logo-tc');  ?>
              </div>
              <!--  /Brand -->
            <?php } ?>
            <!-- Footer links -->
            <div class="col-12 d-block d-md-flex align-items-center justify-content-center mb-3 text-center footer-links">
              <a href="#" class="footer-links px-md-4 d-block d-md-inline-block mt-3 mt-md-0">Términos y condiciones</a><span class="d-none d-md-inline-block">|</span>
              <a href="#" class="footer-links px-md-4 d-block d-md-inline-block mt-3 mt-md-0">Política de privacidad</a><span class="d-none d-md-inline-block">|</span>
              <a href="#" class="footer-links px-md-4 d-block d-md-inline-block mt-3 mt-md-0">Contáctanos</a>
            </div>
            <!-- /Footer links -->
          </div>
           <!-- Footer copy -->
      <div class="footer-copy">
        <div class="text-body-secondary pb-2 text-center footer-info">            
          <div class="container footer-info">
            <?php global $site_cat; ?>
            <div class="small copyright text-light notranslate" translate="no"><span class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> TopClass<?php echo $GLOBALS['current_site_cat'] ?> - <?php echo $_SERVER['HTTP_HOST'] ?><span class="d-none d-md-inline-block"> - TopclassClub 2005</span></div>
          </div>
        </div>
      </div>
      <!-- /Footer copy -->
        </div>
      </div>
      <!-- /Footer Gradient -->
     
    </footer>
    <!-- /Footer -->
    <!-- Smoke -->
    <?php 
    $animation = get_animation('animation');
    if(!is_checkout()) {
      if($animation == 'Smoke'){ 
          get_template_part('template-parts/components/smoke'); 

        } 
      }; 
    ?>
    <!-- /Smoke -->
    <!-- Fixed bottom actions menu -->
    <div class="position-fixed bottom-actions-menu">

      <!-- To top button -->
      <a href="#" class="d-flex justify-content-center align-items-center shadow to-top-button bottom-actions-btn sub-menu-btn" aria-label="Return to top">
        <i class="fa-solid fa-chevron-up"></i>
      </a>
      <!-- /To top button -->
      
       <!-- full-screen button -->
       <!-- <a href="javascript:void(0)" id="goFS" class="d-flex justify-content-center align-items-center shadow full-screen-button bottom-actions-btn sub-menu-btn" >
        <i class="fa-solid fa-expand expand"></i>
        <i class="fa-solid fa-compress compress d-none"></i>
       </a> -->
      <!--  full-screen button -->

       <!-- telegram button -->
       <a href="https://t.me/topclassClub" class="d-flex justify-content-center align-items-center shadow telegram-button bottom-actions-btn sub-menu-btn" target="_blank">
       <i class="fa-brands fa-telegram"></i>
       </a>
      <!--  telegram button -->

       <!-- X button -->
      <a href="https://x.com/topclassclub" class="d-flex justify-content-center align-items-center shadow x-button bottom-actions-btn sub-menu-btn" target="_blank">
        <i class="fa-brands fa-x-twitter"></i>
      </a>
      <!-- x button -->

      <!-- WA button -->
      <a href="https://api.whatsapp.com/send?phone=56977253992&text=Hola%20TopclassClub%20quiero%20saber..." class="d-flex justify-content-center align-items-center shadow wa-button bottom-actions-btn sub-menu-btn" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
      </a>
      <!-- WA button -->

        <!-- Country selector -->
      <!-- <a href="javascript:void(0)" class="d-flex justify-content-center align-items-center shadow country-selector bottom-actions-btn sub-menu-btn" >
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/chile.png" alt="chile" class="d-inline-block" style="height: 18px; width:auto">
      </a> -->
      <!-- /Country selector -->
      <!-- Open button -->
      <span href="javascript:void(0)" class="d-flex justify-content-center align-items-center shadow open-button bottom-actions-btn shakeY" >
        <i class="fa-solid fa-plus"></i>
    </span>
      <!-- /Open button -->
    </div>
    <?php wp_footer(); ?>
    <div id="home-page-map">
      <div class="acf-map" data-zoom="14">
          <div class="marker" data-lat="" data-lng=""></div>
      </div>
    </div> 
    <!-- Bubbles -->
    <?php if(!is_checkout()) {
        if($animation == 'Bubbles'){
          ?>
          <div id="global-bubbles" class="footer-bubbles bubbles-wrapper" >
            <?php get_template_part('template-parts/components/bubbles'); ?>
          </div>
    <?php } 
        }; 
    ?>
    <!-- /Bubbles -->

  </body>
</html>
