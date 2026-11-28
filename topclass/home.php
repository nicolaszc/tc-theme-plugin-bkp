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
            <div id="<?php echo $user_slug; ?>" class="card-wrapper <?php echo (is_mobile_device()) ?  'swiper-slide' :  'profile-item' ; ?> <?php if($user_district['value']) echo esc_attr($user_district['value']) ?>">                    
              <div class="card border-0 position-relative">

                <div class="card-header position-relative p-0 border border-0">

                  <a href="<?php if($user_url) echo $user_url ;?>"><img src="<?php echo $user_image_url; ?>" class="card-img-top" loading="lazy" alt="<?php if($user_name) echo $user_name; ?>"></a>
                  <div id="card-availability" class="position-absolute"><i class="availability fa-solid fa-circle <?php if($user_availability) echo esc_attr($user_availability['value']); ?>" ></i></div>
                
                </div>
               
                <div class="card-body position-absolute w-100 d-flex align-items-start justify-content-between flex-column">
                  
                    <div class="card-text d-flex align-items-center">

                      <p class="card-district text-light mb-0 me-2 text-shadow-sm"><?php if($user_district) echo esc_attr($user_district['label']); ?></p>
                      
                      <div class="card-actions position-relative d-flex">   

                        <a href="https://api.whatsapp.com/send?phone=<?php echo $user_phone; ?>&text=Hola <?php if($user_name) echo $user_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" data-bs-toggle="tooltip" data-bs-title="LLámame" data-bs-placement="right" data-bs-custom-class="custom-tooltip"  data-bs-trigger="hover">
                          <span class="atribute-icon position-relative text-shadow-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                            <i class="fa-solid fa-phone"></i>
                          </span>
                          <span class="d-none"><?php if($user_phone) echo split_phone($user_phone); ?></span>
                        </a>

                        <a href="javascript:void(0)" class="price-btn">
                          <span class="atribute-icon">
                            <i class="fa-solid fa-tag text-shadow-sm"></i>
                          </span>
                          <span class="precio d-none">CLP$ <?php if($user_price) echo ' '.number_format($user_price, 0, ',', '.').' '; ?> /hr</span>
                        </a>
 
                        <?php if($user_shows_map) { ?>
                          <a href="#home-page-map" class="glightbox card-map-trigger" data-bs-toggle="tooltip" data-bs-title="Ver ubicación" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-trigger="hover" data-lat="<?php if ($user_google_map['lat']) echo esc_attr($user_google_map['lat']); ?>" data-lng="<?php if ($user_google_map['lng']) echo esc_attr($user_google_map['lng']); ?>" data-type="inline">
                            <span class="atribute-icon">  
                              <i class="fa-solid fa-location-dot text-shadow-sm"></i>
                            </span>
                          </a> 
                        <?php } ?>

                        <div class="share-container position-relative">

                          <a href="javascript:void(0)" class="share-btn">
                            <i class="fa-solid fa-share text-shadow-sm iconOpen"></i>
                            <i class="fa-solid fa-xmark iconClose d-none"></i>
                          </a>

                          <div class="invisible share-rrss d-flex position-absolute shadow-sm px-1">
                            
                            <a href="<?php echo 'https://twitter.com/intent/tweet?text=' .$share_text. '&url='. $user_url; ?>" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Twitter" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                              <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php if($user_url) echo $user_url; ?>" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Facebook" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                              <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php if($user_url) echo $share_text . '%20' . $user_url; ?>" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir por Whatsapp" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                              <span class="atribute-icon position-relative"><i class="fa-brands fa-whatsapp"></i><i class="fa-solid fa-phone"></i></span>
                            </a>

                          </div>
                        </div>

                      </div>
                    </div>

                        
                    <h5 class="card-title nombre text-white mb-0 text-shadow-sm"><?php if($user_name) echo $user_name; ?></h5>

                    <!-- <a href="<?php if($user_url) echo $user_url ;?>" class="btn btn-primary rounded-pill px-4 cta">CONÓCEME</a> -->

                    
                    
                  
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
      <div class="<?php echo (is_mobile_device()) ?  'swiper-button-next opacity-50':'d-none' ?>"><i class="fa-solid fa-chevron-right"></i></div>
      <div class="<?php echo (is_mobile_device()) ?  'swiper-button-prev ms-3 opacity-50':'d-none' ?>"><i class="fa-solid fa-chevron-left"></i></div>     
    </div>
   
<?php
asort($used_districts); 

// Create a select dropdown for the used districts 
echo '<div id="district-filter-group">';                            
echo '<i class="fa-solid fa-location-dot"></i>';  
echo  '<button class="btn btn-primary rounded-pill mx-2" type="input" data-value="*">Todas</button>';
foreach ($used_districts as $value => $label) {
    echo '<button class="btn btn-primary rounded-pill me-2" type="input" data-value=".'. esc_attr($value) .'">' . esc_html($label) . '</button>';
}
echo '</div>';

get_footer();