<style>
.moods {
  overflow-x: hidden; /* Evita que contenido ancho fuerce scroll horizontal */
}

.moods-slider {
  max-width: 100vw !important; /* Asegura que no se pase del viewport */
  overflow: hidden; /* Para que no salga scroll */
}

/* Asegura que los slides no se extiendan demasiado */
.moods-slider .swiper-wrapper {
  max-width: 100vw !important;
}
</style>
<div class="moods d-flex justify-content-center align-items-center position-absolute w-100 show" >
  <div id="<?php echo (is_mobile_device()) ?  'moods-swiper' :  'container-moods' ; ?>" class="moods-slider <?php echo (is_mobile_device()) ?  'swiper-moods' :  'd-flex justify-content-center align-items-center' ; ?>"> 
      <?php 
        echo (is_mobile_device()) ?  '<div class="swiper-wrapper">' :  '';
        $queried_users_slices = $GLOBALS['queried_users_slices']; 
        if(!empty($queried_users_slices)):
          //$users_moods = $queried_users_slices['moods'];
          $users_moods = array_filter(
              $queried_users_slices['moods'],
              fn($user) => $user['user_slug'] !== 'angeles-cid'
          );

          $users_moods = array_values($users_moods);

          if (count($users_moods) > 5) {
              unset($users_moods[array_rand($users_moods)]);
          }
          foreach ($users_moods as $user_moods) :
            $user_url = esc_url(home_url('/modelo/' . $user_moods['user_slug']));
            $user_image = get_field('vendor_image', $user_moods['user_id']);
            if($user_image):
            $user_image_url = $user_image['sizes']['custom-card-avatar'];
            ?>
              <div id="<?php echo $user_moods['user_slug'] ?>" class="mood <?php echo (is_mobile_device()) ?  'swiper-slide' :  '';?> item text-center">
                <a href="<?php echo $user_url ?>">
                  <div class="mood-bubble rounded-circle overflow-hidden mx-3 shadow">
                    <img src="<?php if($user_image_url) echo $user_image_url; ?>" class="mood-img" loading="lazy" alt="<?php if($user_moods['user_name']) echo $user_moods['user_name']; ?>">
                  </div>
                    <?php echo $user_moods['user_name'] ?>
                </a>
              </div>
            <?php
            endif;
          endforeach;
        endif;
        echo (is_mobile_device()) ?  '</div>' :  '';
      ?>
    
  </div>
</div>

              