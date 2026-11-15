<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 * @version 6.0.4
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

  <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $GLOBALS['gtag'] ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '<?php echo $GLOBALS['gtag'] ?>');
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYAd2QZcznCstR389YkVI3Kjwkiq5SfFQ"></script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="height=device-height, width=device-width, minimum-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    
  </head>

  <body <?php body_class(); ?> data-bs-spy="scroll" data-bs-target="#comments">

    <?php wp_body_open(); ?>
    
     <?php get_template_part('template-parts/header/theme-styles'); ?>
      
      <a class="skip-link visually-hidden-focusable" href="#primary"><?php esc_html_e( 'Skip to content', 'bootscore' ); ?></a>
      <a class="skip-link visually-hidden-focusable" href="#footer"><?php esc_html_e( 'Skip to footer', 'bootscore' ); ?></a>
      
      <?php if (is_active_sidebar('top-bar')) : 
        dynamic_sidebar('top-bar'); 
      endif; ?>  

      <?php if(is_front_page()):                                                                            
        get_template_part('template-parts/components/main-swiper');
      endif; ?>

      <div class="overlay-header-top"></div>

      <header id="masthead" class="site-header">

        <nav id="nav-main" class="navbar <?= apply_filters('bootscore/class/header/navbar/breakpoint', 'navbar-expand-lg tc-nav'); ?>">
          <div class="<?= apply_filters('bootscore/class/container', 'container-fluid main-nav', 'header'); ?>">             
            <?php get_template_part('template-parts/components/logo-tc');  ?>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
              
              <div class="offcanvas-header">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-circle-xmark"></i></button>
              </div>

              <div class="offcanvas-body">
                <?php get_template_part('template-parts/components/search'); ?>
                <div class="nav-container">
                <?php if(!is_checkout()) { ?>   

                    <?php get_template_part('template-parts/header/main-menu'); ?>
                    
                    <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-light border-0 ms-md-0', 'contact-toggler'); ?> contact-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-contact" aria-controls="offcanvas-contact">
                      <i class="fa-solid fa-envelope"></i>
                    </button>
                    
                    <?php
                    if (class_exists('WooCommerce')) :
                      get_template_part('template-parts/header/actions', 'woocommerce');
                    else :
                      get_template_part('template-parts/header/actions');
                    endif;
                    ?>
                    <!-- /WooCommerce -->
                    <!-- Gender selector -->
                    <div class="d-flex me-3 me-md-0 ms-md-3 gender-toggler <?php echo ($GLOBALS['current_site_cat'] == 'Trans')? 'flex-row-reverse':'' ?>">                          
                        <a href="<?php echo $GLOBALS['trans_link'] ?>" class="btn <?php echo $GLOBALS['trans_class'] ?> border-0  trans" aria-label="Acompañantes transgenero"><i class="fa-solid fa-transgender"></i></a>
                        <a href="<?php echo $GLOBALS['venus_link'] ?>" class="btn <?php echo $GLOBALS['venus_class'] ?> border-0 venus" aria-label="Acompañantes mujeres"><i class="fa-solid fa-venus"></i></a>                 
                    </div>
                    <!-- Gender selector -->
                </div>
                  <!-- Offcanvas Logo -->
                  <?php get_template_part('template-parts/components/logo-tc');  ?>
                  <!-- /Offcanvas Logo -->
                  <div class="d-block d-md-none text-center">
                    <div class="d-flex align-items-center justify-content-center mb-5">
                      Síguenos en:
                      <a href="https://x.com/topclassclub" target="_blank" class="ps-2 pe-1"><i class="fa-brands fa-x-twitter"></i></a>
                      <a href="https://t.me/topclassClub" target="_blank" class="px-1 px-1"><i class="fa-brands fa-telegram"></i></a>
                      <a href="https://api.whatsapp.com/send?phone=56977253992&text=Hola%20TopclassClub%20quiero%20saber..." target="_blank" class="px-1 ps-2"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                    <div class="small copyright ">
                      <span class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> <?php bloginfo('name'); ?> - <?php echo $_SERVER['HTTP_HOST'] ?>
                    </div>
                  </div>
                <?php } ?> 
              </div>
            </div>
 
          </div>

          <?php if(!is_checkout()) { ?>
            <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-light border-0 position-absolute end-0', 'nav-toggler'); ?> <?= apply_filters('bootscore/class/header/navbar/toggler/breakpoint', 'd-lg-none'); ?> ms-1 ms-md-2 nav-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/menu.svg"  alt="..." class="menu-toggle-btn">           
            <span class="visually-hidden-focusable">Menu</span>
            </button>
          <?php } ?> 

        </nav>

        <?php
        if (class_exists('WooCommerce')) :
          get_template_part('template-parts/header/collapse-search', 'woocommerce');
        else :
          get_template_part('template-parts/header/collapse-search');
        endif;
        ?>

        <?php
        if (class_exists('WooCommerce')) :
          get_template_part('template-parts/header/offcanvas', 'woocommerce');
        endif;
        ?>

        <div class="offcanvas offcanvas-end p-4" tabindex="-1" id="offcanvas-contact"> 
          <div class="offcanvas-header mb-3">
            <h3><?= apply_filters('bootscore/offcanvas/navbar/title', __('Contáctanos', 'bootscore')); ?></h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-circle-xmark"></i></button>
          </div>
          <div class="offcanvas-body">
            <?php echo do_shortcode('[contact-form-7 id="'.get_field('contact_id','options').'"]') ?>
          </div>
        </div>
        
      </header>

      <?php
        $classes = get_body_class();
        if(in_array('page-template-page-modelo',$classes)):
          get_template_part('template-parts/components/top-profile-info');                  
        endif; 
      ?> 
      
      <div class="top-box-footer text-center"></div>

      <?php if(!is_front_page()): ?>
        <div class="position-fixed w-100 bkg-gradient top-0 z-2 pe-none" style="height: 50vh"></div> 
      <?php endif; ?> 

      <div id="main-container">  

        <?php if(is_front_page()):                                                                            
          get_template_part('template-parts/components/moods');
          get_template_part('template-parts/components/filters');
        endif; ?>

        <main id="main" class="main z-2 position-relative <?php if(!is_checkout()) { ?> pb-md-3 mb-5<?php } ?>"> 
      
        