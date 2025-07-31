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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GDY39XBR13"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-GDY39XBR13');
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYAd2QZcznCstR389YkVI3Kjwkiq5SfFQ&callback=Function.prototype"></script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="height=device-height, width=device-width, minimum-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    
  </head>

  <body <?php body_class(); ?> data-bs-spy="scroll" data-bs-target="#comments">

    <?php wp_body_open(); ?>
    
     <?php get_template_part('template-parts/header/theme-styles'); ?>
      <!-- Skip Links -->
      <a class="skip-link visually-hidden-focusable" href="#primary"><?php esc_html_e( 'Skip to content', 'bootscore' ); ?></a>
      <a class="skip-link visually-hidden-focusable" href="#footer"><?php esc_html_e( 'Skip to footer', 'bootscore' ); ?></a>
      <!-- /Skip Links -->
      

      <!-- Top Bar Widget -->
      <?php if (is_active_sidebar('top-bar')) : ?>
        <?php dynamic_sidebar('top-bar'); ?>
      <?php endif; ?> 
      <!-- /Top Bar Widget --> 

      <?php if(is_front_page()):?>                                                                             
       <?php get_template_part('template-parts/components/main-slider-random'); ?>
       <?php get_template_part('template-parts/components/moods'); ?>
      <?php endif; ?> 
      <div class="overlay-header-top"></div>
        <!-- Header -->
      <header id="masthead" class="site-header">

        <!-- Navbar -->
        <nav id="nav-main" class="navbar <?= apply_filters('bootscore/class/header/navbar/breakpoint', 'navbar-expand-lg tc-nav'); ?>">
          <!-- Container -->
          <div class="<?= apply_filters('bootscore/class/container', 'container-fluid main-nav', 'header'); ?>">             
            <!-- Navbar Brand -->
            <?php get_template_part('template-parts/components/logo-tc');  ?>
            <!-- /Navbar Brand -->
            
            <!-- Offcanvas Navbar -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
              <div class="offcanvas-header">
                <!-- <span class="h5 offcanvas-title"><?= apply_filters('bootscore/offcanvas/navbar/title', __('Menu', 'bootscore')); ?></span> -->
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-circle-xmark"></i></button>
              </div>
              <div class="offcanvas-body">
                <?php get_template_part('template-parts/components/search'); ?>
                <div class="nav-container">
                <?php if(!is_checkout()) { ?>   

                    <!-- Bootstrap 5 Nav Walker Main Menu -->
                    <?php get_template_part('template-parts/header/main-menu'); ?>
                    
                    <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-light border-0 ms-1 ms-md-0', 'contact-toggler'); ?> contact-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-contact" aria-controls="offcanvas-contact">
                      <i class="fa-solid fa-envelope"></i> <span class="d-inline-block d-md-none">Contacto</span>
                    </button>
                    
                    <!-- WooCommerce -->
                    <?php
                    if (class_exists('WooCommerce')) :
                      get_template_part('template-parts/header/actions', 'woocommerce');
                    else :
                      get_template_part('template-parts/header/actions');
                    endif;
                    ?>
                    <!-- /WooCommerce -->
                    <!-- Gender selector -->
                    <div class="d-flex mb-3 mb-md-0 ms-1 ms-md-3 gender-toggler <?php echo ($GLOBALS['current_site_cat'] == 'Trans')? 'flex-row-reverse':'' ?>">                          
                        <a href="<?php echo $GLOBALS['trans_link'] ?>" class="btn <?php echo $GLOBALS['trans_class'] ?> border-0  trans"><i class="fa-solid fa-transgender"></i></a>
                        <a href="<?php echo $GLOBALS['venus_link'] ?>" class="btn <?php echo $GLOBALS['venus_class'] ?> border-0 venus"><i class="fa-solid fa-venus"></i></a>                 
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
            <!-- /Offcanvas Navbar -->
 
          </div>
          <!-- /Container -->
          <!-- Navbar Toggler -->
          <?php if(!is_checkout()) { ?>
            <button class="<?= apply_filters('bootscore/class/header/button', 'btn btn-outline-light border-0 mt-2 position-absolute end-0', 'nav-toggler'); ?> <?= apply_filters('bootscore/class/header/navbar/toggler/breakpoint', 'd-lg-none'); ?> ms-1 ms-md-2 nav-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
                <i class="fa-solid fa-ellipsis-vertical"></i><span class="visually-hidden-focusable">Menu</span>
            </button>
          <?php } ?> 
          <!-- /Navbar Toggler -->
        </nav>
        <!-- /Navbar -->

        <?php
        if (class_exists('WooCommerce')) :
          get_template_part('template-parts/header/collapse-search', 'woocommerce');
        else :
          get_template_part('template-parts/header/collapse-search');
        endif;
        ?>

        <!-- Offcanvas User and Cart -->
        <?php
        if (class_exists('WooCommerce')) :
          get_template_part('template-parts/header/offcanvas', 'woocommerce');
        endif;
        ?>

        <!-- Offcanvas Contact -->
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
      <!-- /Header -->

      <?php
        $classes = get_body_class();
        if(is_front_page() || in_array('page-template-page-modelo',$classes)):
      ?>
        <div class="top-box is-open">         
            <div class="row w-100">                
              <div class="col-md-12 ">   

                <?php 
                if(is_front_page()) :
                  get_template_part('template-parts/components/filters');
                  get_template_part('template-parts/components/top-banner');                 
                endif;

                if (in_array('page-template-page-modelo',$classes)) :             
                  get_template_part('template-parts/components/top-profile-info');
                endif; 
                ?>         
                
            </div> 
            <?php if(is_front_page()) : ?><a href="javascript:void(0)" class="toggle-top-box d-none d-md-block"><i class="fa-solid fa-chevron-down"></i></a><?php endif; ?>       
          </div>                      
        </div>
      <?php
        endif;
      ?> 
      <div class="top-box-footer text-center"></div>

      <?php if(!is_front_page()): ?>
        <div class="position-fixed w-100 bkg-gradient" style="height: 50vh; top: 0; z-index: 2; pointer-events:none;"></div> 
      <?php
        endif;
      ?> 

    <div id="main-container">  
       
      <main id="main" class="main z-2 position-relative <?php if(!is_checkout()) { ?> pb-md-3 mb-5<?php } ?>"> 
      
        