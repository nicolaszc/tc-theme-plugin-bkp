<div class="moods d-flex justify-content-center align-items-center position-absolute w-100 show" >
  <div id="<?php echo (is_mobile_device()) ?  'slider-moods' :  'container-moods' ; ?>" class="moods-slider <?php echo (is_mobile_device()) ?  'owl-carousel' :  'd-flex justify-content-center align-items-center' ; ?>">
   
    <?php 
      global $vendor_users_slices;
      if(!empty($vendor_users_slices)):
        $vendors_moods = $vendor_users_slices['moods'];
        foreach ($vendors_moods as $vendor_moods) :
          $vendor_url = get_vendor_slug_by_name($vendor_moods['vendor_name']);
          if (function_exists('wpupa_get_url')): 
            $imgURL = wpupa_get_url($vendor_moods['vendor_id'], ['size' => 'custom-card-avatar']);
          endif;
          //echo 'name: '.$vendor_moods['vendor_name'].'/ vendor_ID: '.$vendor_moods['vendor_id'].'/ vendor_user_ID: '.$vendor_moods['vendor_user_id'].'/ vendor_slug: '.$vendor_slug;
          if($imgURL):
          ?>
            <div class="mood item text-center">
              <a href="<?php echo $vendor_url ?>">
                <div class="mood-bubble rounded-circle overflow-hidden mx-3 shadow">
                  <img src="<?php if($imgURL) echo $imgURL; ?>" class="mood-img" loading="lazy" alt="<?php if($vendor_moods['vendor_name']) echo $vendor_moods['vendor_name']; ?>">
                </div>
                <?php echo $vendor_moods['vendor_name'] ?>
              </a>
            </div>
          <?php
          endif;
        endforeach;
      endif;
    ?>
    
  </div>
</div>

              