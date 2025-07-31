<?php

/**
 * @package Bootscore Child
 *
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Enqueue scripts and styles
 */

 

add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles');
function bootscore_child_enqueue_styles() {
  //owl.css
  wp_enqueue_style('owl', get_stylesheet_directory_uri() . '/assets/css/owl.carousel.min.css');
  
  //animate.css
  wp_enqueue_style('animate', get_stylesheet_directory_uri() . '/assets/css/animate.min.css');

  // Compiled main.css
  // Get modification time. Enqueue file with modification date to prevent browser from loading cached scripts when file content changes. 
  $modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/main.css'));
  wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/main.css', array('parent-style'), $modified_bootscoreChildCss);

  //glightbox.css
  wp_enqueue_style('glightbox', get_stylesheet_directory_uri() . '/assets/css/glightbox.min.css');
  
  // CSS de Swiper
  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null);

  // style.css
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  
  //owl.js
  wp_enqueue_script('owl-script', get_stylesheet_directory_uri() . '/assets/js/owl.carousel.js', array(), null, true);

  //jquery-ui.js
  wp_enqueue_script('jquery-ui-script', get_stylesheet_directory_uri() . '/assets/js/jquery-ui.min.js', array(), null, true);
  
  //glightbox.js
  wp_enqueue_script('glightbox-script', get_stylesheet_directory_uri() . '/assets/js/glightbox.min.js', array(), null, true);

  // JS de Swiper (después de jQuery)
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), null, true);

  //gmaps.js
  $modificated_gmapsJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/gmaps.js'));
  wp_enqueue_script('gmaps-script', get_stylesheet_directory_uri() . '/assets/js/gmaps.js', array('jquery'), $modificated_gmapsJS, true);

  // custom.js
  // Get modification time. Enqueue file with modification date to prevent browser from loading cached scripts when file content changes. 
  $modificated_CustomJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/custom.js'));
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $modificated_CustomJS, true);
  
  wp_enqueue_script('resize-listener', get_stylesheet_directory_uri() . '/assets/js/resize_loader.js', array(), null, true);

}

add_action('wp_enqueue_scripts', 'add_js_per_page');
function add_js_per_page() {

    if ( is_front_page() ) { // Replace 'about-us' with the slug of your page
      
      if(is_mobile_device()){

        //swiper init()
        $modificated_sliderJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/home-card-slider.js'));
        wp_enqueue_script('home-card-slider-script', get_stylesheet_directory_uri() . '/assets/js/home-card-slider.js', array('jquery'), $modificated_sliderJS, true);

      }else{

        //isotope.js
        wp_enqueue_script('isitope-script', get_stylesheet_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), null, true);
        
        //isotope init()
         $modificated_isotopeJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/home-card-isotope.js'));
        wp_enqueue_script('home-card-isotope-script', get_stylesheet_directory_uri() . '/assets/js/home-card-isotope.js', array('jquery'), $modificated_isotopeJS, true);

        // is card zone
        $modificated_is_card_zoneJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/is-card-zone.js'));
        wp_enqueue_script('is-card-zone-script', get_stylesheet_directory_uri() . '/assets/js/is-card-zone.js', array('jquery'), $modificated_is_card_zoneJS, true);
      
      }

    }
    //if ( is_page('modelo') ) { // Replace 'about-us' with the slug of your page
      //if(!is_mobile_device()){
        //$modificated_modelJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/model-card-slider.js'));
        //wp_enqueue_script('model-card-slider-script', get_stylesheet_directory_uri() . '/assets/js/home-model-slider.js', array('jquery'), $modificated_modelJS, true);
      //}
    //}
}

function is_mobile_device() {

  // Get the user agent string
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  
  // Check if the user agent matches any common patterns for mobile devices
  if (preg_match('/android|iphone|ipod|blackberry|iemobile|opera mobile|palmos|webos/i', $userAgent)) {
    return true;
  } else {
    return false;
  }

}


// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
$mimes['svg'] = 'image/svg+xml';
return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

define('ALLOW_UNFILTERED_UPLOADS', true);

add_filter('wpcf7_autop_or_not', '__return_false');
add_filter('wpcf7_form_elements', function($content) {
  $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
  return $content;
});

add_image_size( 'custom-vendor-desktop-size', 360, 640, true );
add_image_size( 'custom-avatar', 255, 350, true );
add_image_size( 'custom-card-avatar', 350, 350, true );
add_image_size( 'mobile-main-slider', 206, 458, true );

require_once('inc/comments.php'); 

function my_login_logo() { ?>

  <style type="text/css">
    html{
      background: rgb(157, 14, 73)!important;
    }

    body.login{
      font-family: Montserrat, system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
      background: var(--body-bg-color);
      background: -moz-linear-gradient(rgb(191, 21, 91) 0%, rgb(122, 6, 54) 50%, rgb(89, 0, 37) 100%);
      background: -webkit-linear-gradient(rgb(191, 21, 91) 0%, rgb(122, 6, 54) 50%, rgb(89, 0, 37) 100%);
      background: linear-gradient(rgb(191, 21, 91) 0%, rgb(122, 6, 54) 50%, rgb(89, 0, 37) 100%);
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='rgb(191,21,91))', endColorstr=' rgb(89,0,37)', GradientType=1);
      
    }
    
    #login h1 a, .login h1 a, .login h1 a:focus {
      background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo/topclass-logo-white.svg);
      height:120px;
      width:320px;
      background-size: 320px 120px;
      background-repeat: no-repeat;
      box-shadow:none;
      pointer-events:none;
    }

    .login form{
      border-radius:6px;
      background: transparent !important;
      border: none !important;
      color: #FFF !important;
    }

    .login-action-lostpassword form, .login-action-lostpassword form p.submit, .login-action-registe form p.submit, #registerform p.submit{
      text-align: center !important;
    }

    .login.login-action-lostpassword form .button-primary, .login.login-action-register form .button-primary{
      float: none!important;
    }

    .login form input[type=text], .login form input[type=password], .login form input[type=email]{
      border-radius:6px;
      border: none !important;
    }

    #reg_passmail{
      text-align: center !important;
    }

    .login form input:focus{
      box-shadow: 0 0 0 2px rgb(235, 0, 99) !important;
      outline: solid 2px #FFF !important;
      outline-offset: 2px !important;
    }
    
    .login form .button-primary{
      background: rgb(235, 0, 99) !important;
      border: none !important;
      border-radius:800px;
      padding: .375rem 2rem!important;
    }

    .login form .button-primary:focus, .login form .button-primary:focus-visible{
      box-shadow: none !important;
      outline: none !important;
      outline-offset: 0 !important;
    }

    .login #nav, .login #nav a, .login #backtoblog, .login #backtoblog a, .login #language-switcher span, .login a{
      color: #FFF!important;
      text-align: center!important;
    }

    .login #language-switcher{
      display: none;
    }

  </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
//Admin area customization
function custom_admin_styles() { 
?>
  <style>
    /* Custom Admin General Styles */
    
    
    #wpadminbar #wp-admin-bar-wp-logo{
      display:none;
    }
    .wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{
      content:"";
      background-image:url(<?php echo get_stylesheet_directory_uri().'/assets/img/logo/favicon.png' ?> )!important;
      background-size:contain;
      background-repeat:no-repeat;
      background-position:center;
      width: 20px;
      height: 20px;
      display: block;
    }
    .postbox{
      border-radius:6px;
      overflow: hidden
    }
    .postbox-header{
      border-bottom-color: #dfdfdf!important;
      background: #f6f7f7;
    }
    #adminmenu li.current a.menu-top{
      background-color: rgb(237,26,115)!important;
    }
    /* #screen-meta {
      margin-top: -17px !important;
    }

    #screen-meta-links:has(button[aria-expanded='false']){
      margin-top: -18px !important;
    }
    #screen-meta-links:has(button[aria-expanded='true']){
      margin-top: 0px !important;
    } */
    
    #wcpv-vendor-settings .form-field:first-child,
    #wcpv-vendor-settings .form-field:nth-child(2),
    form#your-profile h2:nth-child(10),
    form#your-profile h2:nth-child(10)+table/* ,
    #application-passwords-section */
    {
      display:none;
    }
    .border-bottom{
      border-bottom: 1px solid #eee;
    }
    .border-right{
      border-right: 1px solid #eee;
    }
    
  </style>
  <?php
}
add_action('admin_head', 'custom_admin_styles');

function get_vendor_users(){
  global $mis_vendors;
  global $users;
  global $used_districts;
  global $all_users_ids;
  global $vendor_users;

  // WP_User_Query to get all vendors (product vendors)
  $user_query = new WP_User_Query( array( 'role' => 'wc_product_vendors_admin_vendor' ) );
  $users = $user_query->get_results();
  $used_districts = []; // Initialize the used districts array
  $all_users_ids = [];
  $vendor_users = [];
  shuffle($users);

  if(!empty($users)):
    foreach ($users as $user) :

      $vendor_name = $user->first_name . ' ' . $user->last_name;
      $vendor_id = $user->ID;
      $vendor_user_id = 'wcpv_product_vendors_' . $vendor_id;

      $vendor_users[] = ['vendor_id' => $vendor_id, 'vendor_user_id' => $vendor_user_id, 'vendor_name' => $vendor_name /* , 'vendor_term' => $vendor_term */];

    endforeach;
  endif;

  global $vendor_users_slices;
  $vendor_users_slices = [
      //'slider' => array_slice($vendor_users, 0, 2),
      'moods'  => array_slice($vendor_users, 0, 5),
  ];

}
get_vendor_users();

function get_site(){

  global $current_site_cat;
  global $other_site_cat;
  global $venus_link;
  global $trans_link;
  global $venus_class;
  global $trans_class;

  $host = $_SERVER['HTTP_HOST'];
  $enviorment = ($host == 'topclass-girls.com' || $host == 'topclass-trans.com')? 'com' : 'local';

  $current_site_cat = ($host == 'topclass-girls.com' || $host == 'topclass-girls.local')? 'Girls' : 'Trans';
  $other_site_cat = ($current_site_cat == 'Girls')? 'Trans' : 'Girls';
  $venus_link = ($current_site_cat == 'Girls')? 'javascript: void(0)' : 'https://topclass-girls.'.$enviorment;
  $trans_link = ($current_site_cat == 'Trans')? 'javascript: void(0)' : 'https://topclass-trans.'.$enviorment;
  $venus_class = ($current_site_cat == 'Girls')? 'btn-primary' : 'btn-outline-light';
  $trans_class = ($current_site_cat == 'Trans')? 'btn-primary' : 'btn-outline-light'; 
}
get_site();

function get_vendor_slug_by_name($vendor_name) {
    $term = get_term_by('name', $vendor_name, 'wcpv_product_vendors');
    $slug = $term ? $term->slug : null;
    $vendor_page_url = esc_url(home_url('/modelo/' . $slug));
    return $vendor_page_url;
}

function custom_modelo_query_vars($vars) {
  $vars[] = 'modelo_slug';
  return $vars;
}
add_filter('query_vars', 'custom_modelo_query_vars');

function my_acf_google_map_api( $api ){
    
  $api['key'] = 'AIzaSyCYAd2QZcznCstR389YkVI3Kjwkiq5SfFQ';
  
  return $api;
  
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function split_phone($phone) {
  // Split the phone number into parts
  $phone_number = substr($phone, -8);
  $phone_number = str_split($phone_number, 4);
  $phone_number = implode(' ', $phone_number);
  // Get the first part (the country code)
  $country_code = substr($phone, 0, 2);
  // Get the second part (celular prefix)
  $celular_prefix_number = substr($phone, 2, 1);
  // Get the second part (the area code)
  if ($country_code != 56) {
    $area_code = substr($phone, 3, 2);
  } else {
    $area_code = '';
  }
 
  // Final number
  $phone_number = '+(' .$country_code . ') ' . $celular_prefix_number. ' ' .$area_code . ' ' . $phone_number;

  // Return the formatted phone number
  return $phone_number;
}

add_action('comment_form', 'inject_modelo_slug_hidden_field_failsafe');
function inject_modelo_slug_hidden_field_failsafe() {
    // Try to grab from query var or URL
    $modelo_slug = get_query_var('modelo_slug');

    if (!$modelo_slug && isset($_SERVER['REQUEST_URI'])) {
        if (preg_match('#/modelo/([^/]+)/?#', $_SERVER['REQUEST_URI'], $matches)) {
            $modelo_slug = sanitize_title($matches[1]);
        }
    }

    if ($modelo_slug) {
        echo '<input type="hidden" name="modelo_slug" value="' . esc_attr($modelo_slug) . '">';
    }
} 

add_filter('comment_post_redirect', 'custom_comment_redirect_force_slug', 999, 2);
function custom_comment_redirect_force_slug($location, $comment) {
    if (
        isset($_POST['modelo_slug']) &&
        !empty($_POST['modelo_slug']) &&
        $comment instanceof WP_Comment
    ) {
        $modelo_slug = sanitize_title($_POST['modelo_slug']);
        $redirect_url = home_url("/modelo/$modelo_slug/#comment-{$comment->comment_ID}");

        return $redirect_url;
    }

    return $location;
}

add_action('comment_post', 'save_modelo_slug_with_comment');
function save_modelo_slug_with_comment($comment_id) {
    if (isset($_POST['modelo_slug'])) {
        add_comment_meta($comment_id, 'modelo_slug', sanitize_title($_POST['modelo_slug']));
    }
}

add_action('init', function () {
  add_rewrite_tag('%cpage%', '([0-9]+)');
  add_rewrite_rule('^modelo/([^/]+)/page/([0-9]+)/?$', 'index.php?pagename=modelo&modelo_slug=$matches[1]&cpage=$matches[2]', 'top');
  add_rewrite_rule('^modelo/([^/]+)/?$', 'index.php?pagename=modelo&modelo_slug=$matches[1]', 'top');
});
















