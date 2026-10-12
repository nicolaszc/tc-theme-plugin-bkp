<?php

/**
 * Template Name: Home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
        
?>
    <div class="text-light <?php echo (is_mobile_device()) ?  'swiper overflow-x-hidden' :  'container' ; ?>">    
      <div class="<?php echo (is_mobile_device()) ?  'swiper-wrapper' :  'profiles-grid ' ; ?> mt-4">
        <?php 
        // Bring the users allready queried only with the necesary data
        $users = $GLOBALS['queried_users'];    
        if(!empty($users)):
          foreach ($users as $user) :
            // Store data in more friendly variables
            $user_name = $user['user_name'];           
            $user_id = $user['user_id'];
            $user_slug = $user['user_slug'];                       
            
            // Get user ACF fileds
            $user_image = get_field('vendor_image', $user_id);
            $user_district = get_field('vendor_districts', $user_id);
            $user_price = get_field('vendor_price', $user_id);
            $user_phone = get_field('vendor_phone', $user_id);
            $user_availability = get_field('vendor_availability', $user_id);
            $user_shows_map = get_field('vendor_shows_map', $user_id);
            $user_google_map = get_field('vendor_google_map', $user_id);   

            // User URL structure
            $user_url = esc_url(home_url('/modelo/' . $user_slug));
            
            // Conditionally draw cards upon the existance of basic data
            if( $user_name/*  && $user_image */  && $user_slug  && $user_price && $user_district['value'] ):

              $user_image_url = $user_image['sizes']['custom-card-avatar'];

              $used_districts[$user_district['value']] = $user_district['label']; // Add the district to the used districts array

              $share_text = urlencode('Conoce a ' . $user_name . ' en TopClass 👀');
      
          ?>
            <div id="<?php echo $user_slug; ?>" class="<?php echo (is_mobile_device()) ?  'swiper-slide' :  'profile-item' ; ?> <?php if($user_district['value']) echo esc_attr($user_district['value']) ?>">                    
              <div class="card border-0 mb-md-4">

                <div class="card-header position-relative p-0 border border-0">

                  <a href="<?php if($user_url) echo $user_url ;?>"><img src="<?php echo $user_image_url; ?>" class="card-img-top" loading="lazy" alt="<?php if($user_name) echo $user_name; ?>"></a>
                  <div class="card-district d-flex justify-content-start align-items-center position-absolute text-light bottom-0 w-100 ps-3"><?php if($user_district) echo esc_attr($user_district['label']); ?></div>
                  <i class="fa-solid fa-circle position-absolute <?php if($user_availability) echo esc_attr($user_availability['value']); ?>" ></i>
                
                </div>
               
                <div class="card-body">
                  <div class="card-body-header d-flex align-items-start align-items-center justify-content-between">

                    <h5 class="card-title nombre"><?php if($user_name) echo $user_name; ?></h5>

                  </div>
                  <p class="card-text mb-0 mt-1 mb-md-1">
                    <span class="atributos"><a href="https://api.whatsapp.com/send?phone=<?php echo $user_phone; ?>&text=Hola <?php if($user_name) echo $user_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" data-bs-toggle="tooltip" data-bs-title="LLámame" data-bs-placement="right" data-bs-custom-class="custom-tooltip"  data-bs-trigger="hover"><i class="fa-brands fa-whatsapp"></i><?php if($user_phone) echo split_phone($user_phone); ?><!-- <i class="fa-solid fa-eye-slash"></i> --></a></span>
                    <span class="atributos"><i class="fa-solid fa-tag" style="color:var(--secondary-color)"></i>CLP$ <span class="precio"><?php if($user_price) echo ' '.number_format($user_price, 0, ',', '.').' '; ?></span> /hr</span>
                     
                  </p>
                  <div class="card-footer-actions d-flex align-items-end justify-content-between mt-3 mt-md-4">

                    <a href="<?php if($user_url) echo $user_url ;?>" class="btn btn-primary rounded-pill px-4 cta">CONÓCEME</a>

                    <div class="contact-actions position-relative">                    

                      <div class="d-flex">
                        <?php if($user_shows_map) { ?>
                          <a href="#home-page-map" class="glightbox card-map-trigger me-3" data-bs-toggle="tooltip" data-bs-title="Ver ubicación" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-trigger="hover" data-lat="<?php if ($user_google_map['lat']) echo esc_attr($user_google_map['lat']); ?>" data-lng="<?php if ($user_google_map['lng']) echo esc_attr($user_google_map['lng']); ?>" data-type="inline">
                            <i class="fa-solid fa-location-dot"></i>
                          </a> 
                        <?php } ?>
                        <a href="javascript:void(0)" class="share-btn">
                          <i class="fa-solid fa-share-nodes iconOpen"></i>
                          <i class="fa-solid fa-xmark iconClose d-none"></i>
                        </a>
                      </div>
                      
                      <div class="invisible position-absolute share-rrss">
                        <a href="<?php echo 'https://twitter.com/intent/tweet?text=' .$share_text. '&url='. $user_url; ?>" class="d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Twitter" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                          <i class="fa-brands fa-square-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php if($user_url) echo $user_url; ?>" class="mt-2 d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Facebook" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                          <i class="fa-brands fa-square-facebook"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php if($user_url) echo $share_text . '%20' . $user_url; ?>" class="my-2 d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir por Whatsapp" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                          <i class="fa-brands fa-square-whatsapp"></i>
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
              </div> 
            </div>              
            <?php
            endif; // Check if all fields are set                  
          endforeach;         
        else:
          echo '<p>No users found</p>';
      endif;      
      ?>  
      </div>  
      <div class="<?php echo (is_mobile_device()) ?  'swiper-button-next':'d-none' ?>"><i class="fa-solid fa-circle-right"></i></div>
      <div class="<?php echo (is_mobile_device()) ?  'swiper-button-prev ms-3':'d-none' ?>"><i class="fa-solid fa-circle-left"></i></div>     
    </div>
   
<?php
asort($used_districts); 

// Create a select dropdown for the used districts 
echo '<div id="district-dropdown">';     
echo '<div class="btn-group district-filter-dropdown">';
echo '<button class="btn btn-secondary dropdown-toggle px-0 px-md-4 py-2 rounded-pill" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">';
echo  '<span data-bs-toggle="tooltip" data-bs-title="Filtrar" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"><i class="fa-solid fa-filter"></i> Filtrar comuna</span> ';
echo  '</button>';
echo  '<ul class="dropdown-menu" id="district-filter-dropdown">';
echo  '<li><button class="dropdown-item" type="input" data-value="*">Todas</button></li>';
foreach ($used_districts as $value => $label) {
    echo '<li><button class="dropdown-item" type="input" data-value=".'. esc_attr($value) .'">' . esc_html($label) . '</button></li>';
}
echo '<li class="drop-down-last-item" style="border-bottom-left-radius: 6px !important; border-bottom-right-radius: 6px !important; height: 5px; background-color: rgb(from var(--light-color) r g b / .9);"></li>';
echo '</ul>';
echo '</div>';
echo '</div>';

get_footer();