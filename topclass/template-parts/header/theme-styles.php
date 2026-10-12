
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
        font-display: swap;
        src: url("https://<?php echo $_SERVER['HTTP_HOST']; ?>/wp-content/themes/topclass/assets/webfonts/fa-solid-900.woff2");
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
    
    <?php if(!is_checkout()) { ?>
    #main-container{
      background: linear-gradient(0deg, var(--body-secondary-gradient-color) 0%, var(--body-primary-gradient-color) 100%)!important ; 
      
    }
    <?php } ?>
    
    /* Header Styles */   

      .top-box{
        <?php if(is_front_page()) : ?>height:<?php echo $header_gradient_height ?>;<?php endif; ?>
        background: -moz-linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        background: -webkit-linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        background: linear-gradient(180deg, var(--header-primary-gradient-color) 90px, var(--header-secondary-gradient-color) 100%)!important; 
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--header-primary-gradient-color )', endColorstr='var(--header-secondary-gradient-color)', GradientType=1)!important;
      }
    

    /* Footer Styles */
    <?php if(!is_checkout()) { ?>
    #footer{
      background: -moz-linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%);  
      background: -webkit-linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%); 
      background: linear-gradient(0deg,  var(--body-primary-gradient-color) 0%, var(--body-secondary-gradient-color) 100%); 
    }
    <?php } ?>
   

    footer .footer-gradient{ 
      position: relative;
      <?php if(!is_checkout()) { ?>min-height: <?php echo $footer_gradient_height ?>;<?php } ?>
      background: -moz-linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important;
      background: -webkit-linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important;
      background: linear-gradient(0deg, var(--footer-primary-gradient-color) 30px, var(--footer-secondary-gradient-color) 100%)!important; 
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--footer-primary-gradient-color)', endColorstr='var(--footer-secondary-gradient-color)', GradientType=1)!important;     
    }
    <?php 
    if(!($footer_bg_color) && !is_checkout()): 
    ?>
    footer .footer-copy{     
      background: var(--footer-bg-color);     
    }
    <?php endif; ?>
    
</style>

