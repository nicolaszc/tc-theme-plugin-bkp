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

// WP_User_Query to get all vendors (product vendors)
//$user_query = new WP_User_Query( array( 'role' => 'wc_product_vendors_admin_vendor' ) );
//$users = $user_query->get_results();
//$used_districts = []; // Initialize the used districts array
//$all_users_ids = [];
//shuffle($users);
//$state_users = array_slice($users, 0, 3);

        
?>
    <div class="text-light <?php echo (is_mobile_device()) ?  'swiper' :  'container' ; ?>">    
      <div class="<?php echo (is_mobile_device()) ?  'swiper-wrapper' :  'row profiles-grid ' ; ?> mt-4">
       <!-- Masonry width reference
        <div class="grid-sizer"></div> 
       -->       
        <?php       
        if(!empty($users)):
          foreach ($users as $user) :
            $vendor_name = $user->first_name . ' ' . $user->last_name;
            $vendor_term = get_term_by('name', $vendor_name, 'wcpv_product_vendors');
            $vendor_slug = $vendor_term->slug;
            $vendor_id = $user->ID;
            $vendor_user_id = 'wcpv_product_vendors_' . $vendor_id;

            $all_users_ids[] = $vendor_id;
            $vendor_district = get_field('vendor_districts', $vendor_user_id);
            $vendor_price = get_field('vendor_price', $vendor_user_id);
            $vendor_phone = get_field('vendor_phone', $vendor_user_id);
            $vendor_shedule = get_field('vendor_schedule', $vendor_user_id);
            $vendor_availability = get_field('vendor_availability', $vendor_user_id);
            $vendor_google_map = get_field('vendor_google_map', $vendor_user_id);   

            $size = 'custom-ccard-avatar';
            $imgURL ='';
            $vendor_url = esc_url(home_url('/modelo/' . $vendor_slug));


            if( $vendor_slug && $vendor_name && $vendor_price && $vendor_shedule && $vendor_google_map['lat'] && $vendor_google_map['lng'] && $vendor_district['value']):


              
              if (function_exists('wpupa_get_url')): 
              $imgURL = wpupa_get_url($user->ID, ['size' => $size]);
              endif;

              $used_districts[$vendor_district['value']] = $vendor_district['label']; // Add the district to the used districts array

              if ($vendor_shedule['full_time'] || $vendor_shedule == ''):
                $vendor_shedule = 'Full-time';
              else:
                $vendor_shedule = $vendor_shedule['from'] . ' - ' . $vendor_shedule['to'];
              endif;

              $share_text = urlencode('Conoce a ' . $vendor_name . ' en TopClass 👀');
              

          ?>
            <div class="<?php echo (is_mobile_device()) ?  'swiper-slide' :  'profile-item' ; ?> <?php if($vendor_district['value']) echo esc_attr($vendor_district['value']) ?>">                    
              <div class="card border-0 mb-md-4">

                <div class="card-header position-relative p-0 border border-0">

                  <a href="<?php if($vendor_url) echo $vendor_url ;?>"><img src="<?php if($imgURL) echo $imgURL; ?>" class="card-img-top" loading="lazy" alt="<?php if($vendor_name) echo $vendor_name; ?>"></a>
                  <div class="card-district d-flex justify-content-start align-items-center position-absolute text-light bottom-0 w-100 ps-3"><?php if($vendor_district) echo esc_attr($vendor_district['label']); ?></div>
                  <i class="fa-solid fa-circle position-absolute <?php if($vendor_availability) echo esc_attr($vendor_availability['value']); ?>" ></i>
                
                </div>
               
                <div class="card-body">
                  <div class="card-body-header d-flex align-items-start align-items-center justify-content-between">

                    <h5 class="card-title nombre"><?php if($vendor_name) echo $vendor_name; ?></h5>

                    <!-- <a href="#" data-bs-toggle="tooltip" data-bs-title="Compartir" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"><i class="fa-solid fa-share-nodes"></i></a> -->

                    <!-- <a href="#home-page-map" class="glightbox card-map-trigger ms-2" data-bs-toggle="tooltip" data-bs-title="Ver ubicación" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-trigger="hover" data-lat="<?php if ($vendor_google_map['lat']) echo esc_attr($vendor_google_map['lat']); ?>" data-lng="<?php if ($vendor_google_map['lng']) echo esc_attr($vendor_google_map['lng']); ?>" data-type="inline">
                      <i class="fa-solid fa-location-dot"></i>
                    </a> -->

                  </div>
                  <p class="card-text mb-0 mt-1 mb-md-1">
                    <span class="atributos"><a href="https://api.whatsapp.com/send?phone=<?php echo $vendor_phone; ?>&text=Hola <?php if($vendor_name) echo $vendor_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" data-bs-toggle="tooltip" data-bs-title="LLámame" data-bs-placement="right" data-bs-custom-class="custom-tooltip"  data-bs-trigger="hover"><i class="fa-brands fa-whatsapp"></i><?php if($vendor_phone) echo split_phone($vendor_phone); ?><!-- <i class="fa-solid fa-eye-slash"></i> --></a></span>
                    <span class="atributos"><i class="fa-solid fa-tag" style="color:var(--secondary-color)"></i>CLP$ <span class="precio"><?php if($vendor_price) echo ' '.number_format($vendor_price, 0, ',', '.').' '; ?></span> /hr</span>
                    <!-- <span class="atributos"><i class="fa-solid fa-clock" style="color:#9d0d49"></i><?php if($vendor_shedule) echo $vendor_shedule; ?></span> -->
                  </p>
                  <div class="card-footer-actions d-flex align-items-end justify-content-between mt-3 mt-md-4">

                    <a href="<?php if($vendor_url) echo $vendor_url ;?>" class="btn btn-primary rounded-pill px-4 cta">CONÓCEME</a>

                    <div class="contact-actions position-relative">                    
                     <!--  <p class="mb-1 ">Compartir:</p> -->
                      <div class="d-flex">
                        <a href="#home-page-map" class="glightbox card-map-trigger me-3" data-bs-toggle="tooltip" data-bs-title="Ver ubicación" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-trigger="hover" data-lat="<?php if ($vendor_google_map['lat']) echo esc_attr($vendor_google_map['lat']); ?>" data-lng="<?php if ($vendor_google_map['lng']) echo esc_attr($vendor_google_map['lng']); ?>" data-type="inline">
                          <i class="fa-solid fa-location-dot"></i>
                        </a> 
                        <a href="javascript:void(0)" class="share-btn">
                          <i class="fa-solid fa-share-nodes iconOpen"></i>
                          <i class="fa-solid fa-xmark iconClose d-none"></i>
                        </a>
                      </div>
                      
                      <div class="invisible position-absolute share-rrss">
                        <a href="<?php echo 'https://twitter.com/intent/tweet?text=' .$share_text. '&url='. $vendor_url; ?>" class="d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Twitter" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                          <i class="fa-brands fa-square-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php if($vendor_url) echo $vendor_url; ?>" class="mt-2 d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir en Facebook" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
                          <i class="fa-brands fa-square-facebook"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php if($vendor_url) echo $share_text . '%20' . $vendor_url; ?>" class="my-2 d-block" target="_blank" data-bs-toggle="tooltip" data-bs-title="Compartir por Whatsapp" data-bs-placement="top" data-bs-custom-class="custom-tooltip">
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
echo '</ul>';
echo '</div>';
echo '</div>';

get_footer();