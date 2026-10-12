
<?php

    // Get styles from ACF options page
    // *IMPORTANTE Los logos se cargan en el loader

    $typo = '@import url("https://fonts.googleapis.com/css2?';
    if ( have_rows( 'typography' ,'options') ) : 
      while ( have_rows( 'typography','options' ) ) : the_row(); 
        $typo .= 'family='.get_sub_field('typo');
        $typo .= '&';
      endwhile;
      $typo .= 'display=swap");';
    endif;

    function get_animation( $var ) {
      if ( have_rows( 'bkg_animation' ,'options') ) : 
        while ( have_rows( 'bkg_animation','options' ) ) : the_row(); 
          $animation = get_sub_field('bkg_effect');
          $bkg_blend_mode = get_sub_field('bkg_blend_mode');
          $bkg_animation_opacity = get_sub_field('bkg_animation_opacity');

        endwhile;
      endif;
      if($var == 'bkg_blend_mode'){
        return $bkg_blend_mode;
      }
      if($var == 'bkg_animation_opacity'){
        return $bkg_animation_opacity;
      }
      return $animation;
    }

    if ( have_rows( 'colors' ,'options') ) : 
      while ( have_rows( 'colors','options' ) ) : the_row(); 
        $light_color = get_sub_field('light_color');
        $dark_color = get_sub_field('dark_color');
      endwhile;
    endif;

    if ( have_rows( 'primary_link_styles' ,'options') ) : 
      while ( have_rows( 'primary_link_styles','options' ) ) : the_row(); 
        if ( have_rows( 'events','options') ) : 
          while ( have_rows( 'events','options') ) : the_row(); 

            if ( have_rows( 'regular') ) : 
              while ( have_rows( 'regular') ) : the_row(); 
                $background_primary_color = get_sub_field('background');
                $background_border_color = get_sub_field('border');
                $text_primary_color = get_sub_field('color');
              endwhile;
            endif;

            if ( have_rows( 'hover') ) : 
              while ( have_rows( 'hover') ) : the_row(); 
                $background_hover_primary_color = get_sub_field('background');
                $background_hover__primaryborder_color = get_sub_field('border');
              endwhile;
            endif;

          endwhile;
        endif;
      endwhile;
    endif;

    if ( have_rows( 'secondary_link_styles' ,'options') ) : 
      while ( have_rows( 'secondary_link_styles','options' ) ) : the_row(); 
        if ( have_rows( 'events','options') ) : 
          while ( have_rows( 'events','options') ) : the_row(); 

            if ( have_rows( 'regular') ) : 
              while ( have_rows( 'regular') ) : the_row(); 
                $background_secondary_color = get_sub_field('background');
                $background_secondary_border_color = get_sub_field('border');
              endwhile;
            endif;

            if ( have_rows( 'hover') ) : 
              while ( have_rows( 'hover') ) : the_row(); 
                $background_hover_secondary_color = get_sub_field('background');
                $background_hover_secondary_border_color = get_sub_field('border');
              endwhile;
            endif;

          endwhile;
        endif;
      endwhile;
    endif;

    $options_styles = array();
    // Fields_name array
    $fields_name['header_styles'] = 'header';
    $fields_name['body_styles'] = 'body';
    $fields_name['footer_styles'] = 'footer';

    // Loop through the rows of a clone field
    foreach ( $fields_name as $key => $field ) {
        if ( have_rows( $key ,'options') ) : 
        while ( have_rows( $key,'options' ) ) : the_row(); 

            // Text
            if(have_rows('text','options')):
                while(have_rows('text','options')) : the_row();

                    ${$field.'_main_color'} = get_sub_field('main_color');
                    ${$field.'_secondary_color'} = get_sub_field('secondary_color');
                    ${$field.'_terciary_color'} = get_sub_field('terciary_color');

                endwhile;
            endif;
            
            // Background
            if(have_rows('background','options')):
                while(have_rows('background','options')) : the_row();
                //echo $field.'_main_color:'. get_sub_field('main_color');
                    ${$field.'_bg_color'} = get_sub_field('color');
                    ${$field.'_bg_img'} = get_sub_field('img');
                    ${$field.'_bg_size'} = get_sub_field('size');
                    ${$field.'_bg_position'} = get_sub_field('position');
                    ${$field.'_bg_attachment'} = get_sub_field('attachment');
                
                endwhile;
            endif;

            // Gradient
            if(have_rows('gradient','options')):
                while(have_rows('gradient','options')) : the_row();

                    ${$field.'_gradient_show'} = get_sub_field('show');
                    ${$field.'_gradient_height'} = get_sub_field('height');
                    ${$field.'_gradient_color_1'} = get_sub_field('color_1');
                    ${$field.'_gradient_color_2'} = get_sub_field('color_2');

                endwhile;
            endif;

        endwhile;
        endif;
    }

?>
<!-- Header Styles -->
 
<style>
    <?php echo $typo; ?>
    
    @font-face {
        font-family: 'Font Awesome 6 Free';
        font-style: normal;
        font-weight: 900;
        font-display: block;
        src: url("https://topclass50.com/wp-content/themes/topclass/assets/webfonts/fa-solid-900.woff2");
    }
    :root{
      /* General styles */
      --dark-color: <?php echo $dark_color ?>;
      --light-color: <?php echo $light_color ?>;

      /* Link styles */
      --primary-color: <?php echo $background_primary_color ?>;
      --primary-color-hover: <?php echo $background_hover_primary_color ?>;
      --secondary-color: <?php echo $background_secondary_color ?>;
      --secondary-color-hover: <?php echo $background_hover_secondary_color ?>;
      
      /* Header styles */
      --header-main-color: <?php echo $header_main_color ?>;
      --header-secondary-color: <?php echo $header_secondary_color ?>;
      --header-bg-color: <?php echo $header_bg_color ?>;
      --header-gradient-height: <?php echo $header_gradient_height ?>;
      --header-primary-gradient-color: <?php echo $header_gradient_color_1 ?>;
      --header-secondary-gradient-color: <?php echo $header_gradient_color_2 ?>;
      --header-terciary-color: <?php echo $header_terciary_color ?>;
     
      /* Body styles */
      --body-main-color: <?php echo $body_main_color ?>;
      --body-secondary-color: <?php echo $body_secondary_color ?>;
      --body-bg-color: <?php echo $body_bg_color ?>;
      --body-gradient-height: <?php echo $body_gradient_height ?>;
      --body-primary-gradient-color: <?php echo $body_gradient_color_1 ?>;
      --body-secondary-gradient-color: <?php echo $body_gradient_color_2 ?>;
      --body-terciary-color: <?php echo $body_terciary_color ?>;  

      /* Footer styles */
      --footer-main-color: <?php echo $footer_main_color ?>;
      --footer-secondary-color: <?php echo $footer_secondary_color ?>;
      --footer-bg-color: <?php echo $footer_bg_color ?>;
      --footer-gradient-height: <?php echo $footer_gradient_height ?>;
      --footer-primary-gradient-color: <?php echo $footer_gradient_color_1 ?>;
      --footer-secondary-gradient-color: <?php echo $footer_gradient_color_2 ?>;
      --footer-terciary-color: <?php echo $footer_terciary_color ?>;

      /* Icon styles */
      --fa-style-family-classic: 'Font Awesome 6 Free';
      --fa-font-solid: normal 900 1em/1 'Font Awesome 6 Free';  
      
      /* Video styles */
      --bkg-video-display: block;
      --bkg-video-blend-mode: <?php echo get_animation('bkg_blend_mode'); ?>;
      --bkg-video-opacity: <?php echo get_animation('bkg_animation_opacity'); ?>;
    }
    html, .bg-body{
      background: var(--header-bg-color)!important;     
    }
    /* Body styles */
    body {
      font-family: Montserrat, system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
      background: var(--header-bg-color); 
    }
    
    .offcanvas{
      background: var(--body-bg-color);
      <?php if($body_gradient_show): ?>
      background: -moz-linear-gradient(180deg,  var(--body-primary-gradient-color) 0%, var(--body-bg-color) 50%, var(--body-secondary-gradient-color) 100%); 
      background: -webkit-linear-gradient(180deg, var(--body-primary-gradient-color) 0%, var(--body-bg-color) 50%, var(--body-secondary-gradient-color) 100%); 
      background: linear-gradient(180deg, var(--body-primary-gradient-color) 0%, var(--body-bg-color) 50%, var(--body-secondary-gradient-color) 100%); 
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--body-primary-gradient-color)', endColorstr='var(--body-secondary-gradient-color)', GradientType=1);
      color: var(--header-main-color);
      <?php endif; ?>
    }
    
    .btn-close{
      background:none;
      background-repeat: no-repeat;
      background-position: center center;
      outline: 0;
      margin: 0;
      font-size: 1.5rem;
      line-height: 1;
      opacity: .5;
    }
    .btn-close:focus{
      outline: 0;
      box-shadow: none;
    }
    
    /* Links */
    .btn-primary, .btn-outline-primary {
      --bs-btn-color: var(--light-color)!important;
      --bs-btn-bg: var(--primary-color);
      --bs-btn-border-color:var(--primary-color);
      --bs-btn-hover-color: var(--light-color)!important;
      --bs-btn-hover-bg:var(--primary-color-hover);
      --bs-btn-hover-border-color: var(--primary-color-hover)!important;
      --bs-btn-focus-shadow-rgb: var(--primary-color-hover);
      --bs-btn-active-color: var(--light-color);
      --bs-btn-active-bg: var(--primary-color-hover);
      --bs-btn-active-border-color: var(--primary-color-hover);
      --bs-btn-active-shadow: none;
      --bs-btn-disabled-color: var(--light-color);
      --bs-btn-disabled-bg: rgba(235, 0, 99, 0.4);
      --bs-btn-disabled-border-color: rgba(235, 0, 99, 04);
    } 
    .btn-primary.is-checked{
      background-color: var(--primary-color-hover);
      border-color: var(--primary-color-hover);
      color: var(--light-color);
    } 
    .btn-outline-primary {
      --bs-btn-color: var(--primary-color);
      --bs-btn-bg: var(--light-color);
      --bs-btn-border-color:var(--primary-color);
      --bs-btn-hover-color: var(--primary-color-hover);
      --bs-btn-hover-bg: var(--light-color);
      --bs-btn-hover-border-color: var(--primary-color-hover);
      --bs-btn-focus-shadow-rgb: var(--primary-color-hover);
      --bs-btn-active-color: var(--primary-color-hover);
      --bs-btn-active-bg: var(--light-color);
      --bs-btn-active-border-color: var(--primary-color-hover);
      --bs-btn-active-shadow: none;
      --bs-btn-disabled-color: rgba(235, 0, 99, 04);
      --bs-btn-disabled-bg: var(--light-color);
      --bs-btn-disabled-border-color: rgba(235, 0, 99, 04);
    } 

    .input-group-text{
      background-color:var(--primary-color);
      border-color:var(--primary-color);
    }
    
    .titular-container, .filter-container, .top-banner-box .titular-container{
      background: -moz-linear-gradient(90deg, var(--header-secondary-gradient-color) 5%, var(--body-secondary-gradient-color) 20%, var(--header-terciary-color) 50%, var(--body-secondary-gradient-color) 80%, var(--header-secondary-gradient-color) 95%);
      background: -webkit-linear-gradient(90deg, var(--header-secondary-gradient-color) 5%, var(--body-secondary-gradient-color) 20%, var(--header-terciary-color) 50%, var(--body-secondary-gradient-color) 80%, var(--header-secondary-gradient-color) 95%);
      background: linear-gradient(90deg, var(--header-secondary-gradient-color) 5%, var(--body-secondary-gradient-color) 20%, var(--header-terciary-color) 47.5%, color-mix(in srgb, white 20%, var(--header-terciary-color)) 50%, var(--header-terciary-color) 57.5%, var(--body-secondary-gradient-color) 80%, var(--header-secondary-gradient-color) 95%);
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#5e052a",endColorstr="#5e052a",GradientType=1);
      
    }
    .overlay-header-top{
      pointer-events:none;
      height: 200px;
      background: #000;
      position: sticky;
      top: -200px;
      margin-top: -200px;
      z-index: 1000;
      background: -moz-linear-gradient(0deg, var(--header-primary-gradient-color ) 0%, var(--header-secondary-gradient-color) 100%)!important; 
      background: -webkit-linear-gradient(0deg, var(--header-primary-gradient-color ) 0%, var(--header-secondary-gradient-color) 100%)!important; 
      background: linear-gradient(0deg, var(--header-primary-gradient-color ) 0%, var(--header-primary-gradient-color ) 10%, rgba(204,157,83,0) 100%)!important ; 
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--header-primary-gradient-color, )', endColorstr='var(--header-secondary-gradient-color)', GradientType=1)!important;
    }
    .top-box-footer{
        background: -moz-linear-gradient(180deg, var(--header-primary-gradient-color ) 0%, var(--header-secondary-gradient-color) 100%)!important; 
        background: -webkit-linear-gradient(180deg, var(--header-primary-gradient-color ) 0%, var(--header-secondary-gradient-color) 100%)!important; 
        background: linear-gradient(180deg, var(--header-primary-gradient-color ) 0%, var(--header-primary-gradient-color ) 10%, var(--header-secondary-gradient-color) 100%)!important ; 
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--header-primary-gradient-color )', endColorstr='var(--header-secondary-gradient-color)', GradientType=1)!important;
    }
    .bkg-gradient{
      background: linear-gradient(180deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 100%)!important; 
    }
    <?php if(!is_checkout()) { ?>
    #main-container{
      background: linear-gradient(0deg, var(--body-secondary-gradient-color) 0%, var(--body-primary-gradient-color) 100%)!important ; 
      
    }
    <?php } ?>
    #sticky-comments{
      background: var(--body-secondary-gradient-color)!important ; 
    }
    .search-bar .form-control:focus, .search-bar .form-control:active{
      border-color: var(--light-color, #fff)!important;
      border-right-color:transparent!important;
    }

    .btn-primary.toggle-filters, .btn-primary.toggle-filters:hover{
      border-left:1px solid rgb(from var(--light-color) r g b / .3);
    }
    
    .custom-tooltip {
      --bs-tooltip-bg: var(--secondary-color);
      --bs-tooltip-color: var(--bs-black);
      transition: opacity 1s;
    }
    /* Header Styles */   
    header #nav-main{
      background: var(--header-bg-color)!important;
    }

    <?php if($header_gradient_show): ?>
      .top-box{
        <?php if(is_front_page()) : ?>height:<?php echo $header_gradient_height ?>;<?php endif; ?>
        background: -moz-linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        background: -webkit-linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        background: linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--header-primary-gradient-color )', endColorstr='var(--header-secondary-gradient-color)', GradientType=1)!important;
      }
      /*  */
    <?php endif; ?>

    /* Navbar */
    .brand{
      font-family: Six Caps, system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
    }
    #bootscore-navbar .nav-link {
      color: var(--header-main-color);
    }
    #bootscore-navbar .nav-link:hover {
      color: var(--header-secondary-color);
    }
    #bootscore-navbar .nav-link.active {
      color: var(--header-secondary-color);
    }
    
    /* Banner */
    .owl-main-slider .owl-dots .owl-dot{
      border-color: var(--body-main-color);
      background-color: transparent;
    }
    .owl-main-slider .owl-dots .owl-dot span{
      color: var(--body-main-color);
    }
    .owl-main-slider .owl-dots .owl-dot.active{
      border-color: var(--body-secondary-color);
      background-color: var(--body-secondary-color);
    }
    .owl-main-slider .owl-dots .owl-dot.active span{
      color: var(--body-secondary-color);
    }
    #main-slider .overlay.colors{
      background: -moz-linear-gradient(0deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 80%);
      background: -webkit-linear-gradient(0deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 80%);
      background: linear-gradient(0deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 80%);
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--header-primary-gradient-color)', endColorstr='var(--header-secondary-gradient-color)', GradientType=1);
    }
    #main-slider .caption h1{
      color: var(--body-secondary-color);
    }
    #main-slider .caption .description{
      color: var(--body-main-color);
    }
    .strip{
      font-family: Six Caps, system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
      color: var(--secondary-color);
      font-size: 2rem;
    }
    .strip .current_site{
      color: var(--light-color);
    }

    /* Card */
    .card{
      background: rgb(from var(--light-color) r g b / .85);
      overflow: hidden;
      border-radius: 6px;
    }
    .card-text a{
      text-decoration:none;
      color: #000;
    }
    .card-text a:hover{
      text-decoration:underline;
      color: #000;
    }

    /* Footer Styles */
    <?php if(!is_checkout()) { ?>
    #footer{
      background: -moz-linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%);  
      background: -webkit-linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%); 
      background: linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%); 
    }
    <?php } ?>
    #footer a{
      color:var(--light-color)!important;
      text-decoration:none;
    }
    #footer a:hover{
      color: var(--header_secondary_color)!important;
    }
    .footer-links{
      font-size: 0.875rem;
    }
    <?php if($footer_gradient_show): ?>
    footer .footer-copy{
      position: relative;
      padding-bottom: .5rem;
    }
    footer .footer-gradient{ 
      position: relative;
      <?php if(!is_checkout()) { ?>min-height: <?php echo $footer_gradient_height ?>;<?php } ?>
      background: -moz-linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important;
      background: -webkit-linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important;
      background: linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important; 
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--footer-primary-gradient-color)', endColorstr='var(--footer-secondary-gradient-color)', GradientType=1)!important;     
    }
    <?php 
    endif;
    if(!($footer_bg_color) && !is_checkout()): 
    ?>
    footer .footer-copy{     
      background: var(--footer-bg-color);     
    }
    <?php endif; ?>
    #global-smoke{
      mix-blend-mode: var(--bkg-video-blend-mode);
    }
    #main-slider video.bkg-video{
      mix-blend-mode: var(--slider-video-blend-mode);
    }
    @media screen(max-wdth: 768px){
            .main-nav{
                align-items: center;
                justify-content: center;
                .brand-container{
                    margin: auto;
                    padding-left: 20px;
                }
            }
            
            
    }
</style>

