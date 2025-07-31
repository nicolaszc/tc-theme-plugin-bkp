<!-- Main banner -->
<?php
function get_shadow(){
  $shadow = get_sub_field('shadow');
  if($shadow=='small'){
    return 'text-shadow-sm';
  }elseif ($shadow=='regular') {
    return 'text-shadow';
  }elseif ($shadow=='large'){
    return 'text-shadow-lg';
  }else{
    return;
  }
}

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

<!-- Main Banner Carousel--> 
<section id="main-slider" class="vh-100 overflow-hidden">
  <div class="owl-carousel owl-main-slider">

  <!-- Slides -->
    <?php 
      //$count = count(get_sub_field('slides')); 
      $count = 1; 
      if ( have_rows( 'slides' ) ) :     
      while ( have_rows( 'slides' ) ) : the_row(); 
      $use_slide_global = get_sub_field('use_global');
    ?>
      
      <div class="item position-relative vh-100 vw-100 overflow-hidden" data-dot="<span>0<?php echo $count ?> </span>">
        <!-- Media -->
        <?php
        if ( have_rows( 'media' ) ) : 
          while ( have_rows( 'media' ) ) : the_row();
        ?>
        <!-- Desktop -->
        <div class="media position-absolute d-none d-md-block w-100">
          
          <?php
            if ( have_rows( 'desktop' ) ) : 
              while ( have_rows( 'desktop' ) ) : the_row();
                if ( have_rows( 'desktop_media_repeater' ) ) : 
                  while ( have_rows( 'desktop_media_repeater' ) ) : the_row();
                  
                 
                    if (get_sub_field( 'type' )== 'image' && get_sub_field( 'image' )){ ?>     
                      <img src="<?php echo esc_url( get_sub_field( 'image' )); ?>" class="w-100"/>
                    <?php }     
                    if (get_sub_field( 'type' )== 'video' && get_sub_field( 'video' )){
                    ?>
                      <video autoplay muted loop class="w-100">
                        <source src="<?php echo esc_url( get_sub_field('video')); ?>" type="video/mp4">
                      </video>
                    <?php }
                endwhile;
              endif;
            endwhile;
          endif; 
          ?>         
        </div>
        <!-- /Desktop -->
        <!-- Mobile -->
        <div class="media position-absolute d-md-none">        
          <?php
            if ( have_rows( 'mobile' ) ) : 
              while ( have_rows( 'mobile' ) ) : the_row();
                if ( have_rows( 'mobile_media_repeater' ) ) : 
                  while ( have_rows( 'mobile_media_repeater' ) ) : the_row();
          ?>     
            <img src="<?php echo esc_url( get_sub_field( 'image' )); ?>" class="w-100"/>
          <?php 
                endwhile;
              endif;
            endwhile;
          endif; 
          ?>         
        </div>
        <!-- /Mobile -->
        <?php 
          endwhile;
        endif; 
        ?>
        <div class="overlay colors z-6 position-absolute"></div>
        <div class="overlay colors z-7 position-absolute"></div>
        <!-- /Media -->
        <!-- Caption -->
        <!-- Desktop -->
        <div class="caption container d-none d-md-block position-relative z-9">           
          <div class="row px-5 ">             
              <div class="<?php if(get_sub_field('text_align')=="left"){ echo 'col-md-5';}?><?php if(get_sub_field('text_align')=="center"){ echo 'col-md-6 offset-md-3 text-center';}?><?php if(get_sub_field('text_align')=="right"){ echo 'col-md-5 offset-md-7 text-end';}?>" style="color:<?php echo get_sub_field('title_color'); ?>">
              <?php if ($logo) { ?><img src="<?php echo $logo; ?>" alt="topclass" class="banner-logo" /><?php } ?>  
              <!-- Text -->
              <?php
                if ( have_rows( 'text' ) ) : while ( have_rows( 'text' ) ) : the_row();
                  $text_shadow = get_shadow();
              ?>

                <?php if (!empty(get_sub_field('title'))) { ?>
                  <h1 <?php echo ($use_slide_global == false) ? 'style="color: '.get_sub_field('title_color').'"' : '' ; ?> class="<?php echo $text_shadow; ?>" ><?php echo get_sub_field('title') ?></h1>
                <?php } ?>
                <?php if (!empty(get_sub_field('description'))) { ?>
                  <div class="description" <?php echo ($use_slide_global == false) ? 'style="color: '.get_sub_field('description_color').'"' : '' ; ?> class="<?php echo $text_shadow; ?>"><?php echo get_sub_field('description') ?></div>
                <?php } ?>  
                                
              <?php endwhile; endif; ?>
              <!-- /Text -->
              <!-- Link -->
              <?php if ( have_rows( 'link' ) ) : while ( have_rows( 'link' ) ) : the_row(); ?>
                <a href="<?php echo get_sub_field('link_attr')['url'] ?>" class="btn btn-outline-primary rounded-pill px-5 text-uppercase fs-1 <?php echo get_sub_field('shadow'); ?>" style="color: <?php echo get_sub_field('text_color'); ?>!important;background-color: <?php echo get_sub_field('background_color'); ?>!important;border-color:<?php echo get_sub_field('border_color'); ?>!important;"><?php echo get_sub_field('link_attr')['title'] ?></a>
              <?php endwhile; endif;?>
              <!-- /Link -->
              </div>
            
          </div> 
        </div> 
        <!-- /Desktop -->
        <!-- Mobile -->
        <div class="caption mobile container d-block d-md-none position-relative z-9">              
          <div class="row px-5 ">              
            <div class="col-md-6 offset-md-3 text-center" style="color:<?php echo get_sub_field('color_texto'); ?>">
              <?php if (!empty(get_field('logo'))) { ?><img src="<?php echo get_field('logo'); ?>" alt="topclass" class="banner-logo"/><?php } ?>   
              <!-- Text -->
              <?php  if ( have_rows( 'text' ) ) :  while ( have_rows( 'text' ) ) : the_row();
                $text_shadow = get_shadow();
              ?>

                <?php if (!empty(get_sub_field('title'))) { ?>
                  <h1 <?php echo ($use_slide_global == false) ? 'style="color: '.get_sub_field('title_color').'"' : '' ; ?>" class="<?php echo $text_shadow; ?>" ><?php echo get_sub_field('title') ?></h1>
                <?php } ?>
                <?php if (!empty(get_sub_field('description'))) { ?>
                  <div class="description" <?php echo ($use_slide_global == false) ? 'style="color: '.get_sub_field('description_color').'"' : '' ; ?> class="<?php echo $text_shadow; ?>"><?php echo get_sub_field('description') ?></div>
                <?php } ?> 
                
              <?php endwhile; endif; ?>
              <!-- /Text -->
              <!-- Link -->
              <?php if ( have_rows( 'link' ) ) : while ( have_rows( 'link' ) ) : the_row(); ?>
                <a href="<?php echo get_sub_field('link_attr')['url'] ?>" class="btn btn-outline-primary rounded-pill px-5 text-uppercase fs-1 <?php echo get_sub_field('shadow'); ?>" style="color: <?php echo get_sub_field('text_color'); ?>!important;background-color: <?php echo get_sub_field('background_color'); ?>!important;border-color:<?php echo get_sub_field('border_color'); ?>!important;"><?php echo get_sub_field('link_attr')['title'] ?></a>
              <?php endwhile; endif;?>
              <!-- /Link -->
              </div>
            
          </div> 
        </div> 
        <!-- /Mobile -->
        <!-- /Caption -->
        
      </div>
     
    <?php 
      $count++;   
      endwhile;                    
    endif; ?> 
  </div>
  <!-- /Slides -->
  <?php  get_template_part('template-parts/components/strip'); ?>
  <a href="#masthead" class="mouse bounce"><i class="fa-solid fa-computer-mouse"></i></a>
</section>
<?php 
  endwhile;
endif; 
?>
<!-- /Main Banner Carousel-->
