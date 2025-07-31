<?php

/**
 * Preloader template.
 *
 * This template can be overriden by copying this file to your-theme/bs-preloader/preloader.php
 *
 * @author   Bootscore
 * @package  bs Preloader
 * @version  5.5.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
function get_logos($logo){      
      if ( have_rows( 'branding_branding' ,'options') ) : 
       //$logos = array();
        while ( have_rows( 'branding_branding','options' ) ) : the_row();
          $logo_url = get_sub_field($logo); 
        endwhile;
      endif;
      return $logo_url;
    }
?>
<style>
   @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Six+Caps&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap");    
  .gradient-preloader-top{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(0deg,rgba(0, 0, 0, 0)0%, rgba(255, 255, 255, .2)50%, rgba(0, 0, 0, 0)100%);
    opacity: 0.25;
    z-index: 0;
    animation: gradient-animation 3s ease-in-out infinite;
  }
  .gradient-preloader-bottom{
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50%;
    background: linear-gradient(0deg,rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0));
    opacity: 0.8;
    z-index: 1;
    animation: gradient-animation 3s ease-in-out infinite;
  }
</style>
<div id="preloader" class="<?= apply_filters('bootscore/class/preloader/bg', 'tc-preloader bg-body align-items-center justify-content-center position-fixed top-0 end-0 bottom-0 start-0 zi-1070'); ?>">
  <div id="status" class="<?= apply_filters('bootscore/class/preloader/spinner', 'spinner-border'); ?>" role="status">
    <?php /* do_action( 'bootscore_preloader_status' ); */ ?>   
   <img src="<?php echo get_logos('iso_loading'); ?>" alt="Brillo Logo" class="d-td-none opacity-50 w-100">
  </div>
  
  <div class="gradient-preloader-top"></div>
  <div class="gradient-preloader-bottom"></div>
</div>
