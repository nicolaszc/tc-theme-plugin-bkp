
<!-- Main Slider -->
          
<div id="caption-placeholder" class="position-absolute w-100 pe-none"></div>
<section id="main-slider" class="vh-100 overflow-hidden show">
<?php  get_template_part('template-parts/components/strip'); ?>
<?php

// Banner
if ( have_rows( 'main_banner' ) ) : 
  while ( have_rows( 'main_banner' ) ) : the_row(); 
    $banner_use_global = get_sub_field('use_global');
    $logo = get_sub_field('logo');
    // Overlay
    if ( have_rows( 'overlay' ) ) : 
      while ( have_rows( 'overlay' ) ) : the_row();
        if(get_sub_field('show')): 
          $gradient_use_global = get_sub_field('use_global');
          $color_1 = get_sub_field('color_1');
          $color_2 = get_sub_field('color_2');
          $width = get_sub_field('width');
          $height = get_sub_field('height');
          $position_x = get_sub_field('position_x');
          $position_y = get_sub_field('position_y');
          $blend_mode = get_sub_field('blend_mode');
        ?>
        <style>
          #main-slider .overlay{
            width: <?php echo $width ?>;
            height: <?php echo $height ?>;
            top: <?php echo $position_y ?>;
            left: <?php echo $position_x ?>;
          }
          <?php if(($banner_use_global && !$gradient_use_global) || (!$banner_use_global && !$gradient_use_global)): ?>
            #main-slider .overlay.colors{
              background: -moz-linear-gradient(0deg, <?php echo $color_1 ?> 0%, <?php echo $color_2 ?> 80%);
              background: -webkit-linear-gradient(0deg, <?php echo $color_1 ?> 0%, <?php echo $color_2 ?> 80%);
              background: linear-gradient(0deg, <?php echo $color_1 ?> 0%, <?php echo $color_2 ?> 80%);
              filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $color_1 ?>', endColorstr='<?php echo $color_2 ?>', GradientType=1);
            }
          <?php endif; ?>
          #main-slider .overlay.z-6{
            mix-blend-mode: <?php echo $blend_mode ?>;
          }
        </style>
        <?php
        endif; 
      endwhile;
    endif; 
?>


  <div class="owl-carousel owl-main-slider">

    <!-- Slides -->
    <?php 
      $random_slides = get_sub_field( 'slides' );
      if ( is_array( $random_slides ) ) : 
        shuffle( $random_slides );
        $random_slides = array_slice($random_slides, 0, 3);
        $count = 1;
        foreach ( $random_slides as $slide ) :

          // Media
          $desktop_media_array = $slide['media']['desktop']['desktop_media_repeater'][0];
          $desktop_media_type = $desktop_media_array['type'];
          $desktop_media_image = $desktop_media_array['image'];
          $desktop_media_video = $desktop_media_array['video'];
          $mobile_media_array = $slide['media']['mobile']['mobile_media_repeater'][0];
          $mobile_media_type = $mobile_media_array['type'];
          $mobile_media_image = $mobile_media_array['image'];
          $mobile_media_video = $mobile_media_array['video'];
          //var_dump($mobile_media_image);
          // Text
          $text_array = $slide['text'];
          $text_shadow = 'text-'.$text_array['shadow']['value'];
          //var_dump($text_shadow);
          $title_color = $text_array['title_color'];
          $title = $text_array['title'];
          $description_color = $text_array['description_color'];
          $description = $text_array['description'];           

          $link_array = $slide['link'][0];
          $link_attr_array = $link_array['link_attr'];
          $link_url = $link_attr_array['url'];
          $link_title = $link_attr_array['title'];
          $link_text_color = $link_array['text_color'];
          $link_background_color = $link_array['background_color'];
          $link_border_color = $link_array['border_color'];
          $link_shadow = $link_array['shadow'];
          $device = $GLOBALS['device'];
    ?>
      <!-- Slide -->
      <div class="item position-relative vh-100 overflow-hidden" data-dot="<span>0<?php echo $count ?> </span>">
      
        <div class="media position-absolute vh-100">
            <?php        
            if($desktop_media_type == 'image' && $desktop_media_image){
            ?>
              <!-- Image -->
              <?php if($device): ?>
              <!-- Mobile -->
                <img src="<?php echo $desktop_media_image; ?>" class="object-fit-cover vw-100 mw-none vh-100 position-absolute top-00" alt="<?php echo $title ?>"/>
              <?php else: ?>
              <!-- Desktop -->
                <img src="<?php echo $desktop_media_image; ?>" class="vw-100" alt="<?php echo $title ?>"/>
              <?php endif; ?>
              <!-- /Image -->
            <?php } 
            if($desktop_media_type == 'video' && $desktop_media_video){ ?>
              <!-- Video -->
              <!-- Desktop -->
              <video class="vw-100 d-none d-md-block" autoplay muted loop>
                <source src="<?php echo $desktop_media_video ?>" type="video/mp4">
              </video>
              <!-- Mobile -->
              <video class="object-fit-cover d-flex d-md-none w-100 vh-100 justify-content-center" autoplay muted loop>
                <source src="<?php echo $mobile_media_video ? $mobile_media_video : $desktop_media_video; ?>" type="video/mp4">
              </video>
              <!-- /Video -->
            <?php }
            ?>
        </div>
        <!-- /Media -->
        <div class="overlay colors z-6 position-absolute"></div>
        <div class="overlay colors z-7 position-absolute"></div>     
        <!-- Text -->
        <div class="text-captions-container">
          <div class="text-captions position-relative z-9">
            <div class="caption container">                   
              <div class="row px-5 align-items-center justify-content-center"> 

                <div class="<?php if($slide['text_align'] == "left"){ echo 'col-md-5';}?><?php if($slide['text_align'] == "center"){ echo 'col-md-6 text-center';}?><?php if($slide['text_align'] == "right"){ echo 'col-md-5 offset-md-7 text-end';}?>">
                  <?php if ($logo) { ?><img src="<?php echo $logo; ?>" alt="topclass" class="banner-logo" /><?php } ?>
                  <h1 style="color: <?php echo $title_color ?>" class="mt-4 <?php echo $text_shadow; ?>" ><?php echo $title ?></h1>
                  <div class="description <?php echo $text_shadow; ?>" style="color: <?php echo $description_color ?>"><?php echo $description ?></div>
                  <a href="<?php echo $link_url ?>" class="btn btn-outline-primary rounded-pill pe-auto px-5 text-uppercase fs-1 <?php echo $link_shadow; ?>" style="color: <?php echo $link_text_color; ?>!important;background-color: <?php echo $link_background_color; ?>!important;border-color:<?php echo $link_border_color; ?>!important;"><?php echo $link_title ?></a>
                  <a href="#masthead" class="mouse-container d-none d-md-block pt-5 pe-auto mx-auto w-25"><span class="mouse bounce"><i class="fa-solid fa-computer-mouse"></i></span></a>
                </div>

              </div>
              

            </div>      
          </div>
        </div>
        <!-- /Text -->
      </div>

      <!-- /Slide -->
    <?php 
        $count++;   
        endforeach;             
      endif; ?> 
  </div>
  <!-- /Slides --> 
<?php 
  endwhile;
endif; 
?>
</section>
<!-- /Main Slider-->
