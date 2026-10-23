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
  

  //jquery-ui.js
  wp_enqueue_script('jquery-script', get_stylesheet_directory_uri() . '/assets/js/jquery-ui.min.js', array(), null, true);
  
  //glightbox.js
  wp_enqueue_script('glightbox-script', get_stylesheet_directory_uri() . '/assets/js/glightbox.min.js', array(), null, true);

  // JS de Swiper (después de jQuery)
  wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), null, true);

  //gmaps.js
  $modificated_gmapsJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/gmaps.js'));
  wp_enqueue_script('gmaps-script', get_stylesheet_directory_uri() . '/assets/js/gmaps.js', array('jquery'), $modificated_gmapsJS, true);

  // custom.js
  // Get modification time. Enqueue file with modification date to prevent browser from loading cached scripts when file content changes. 
  $modificated_CustomJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/custom.js'));
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $modificated_CustomJS, true);
  
  wp_enqueue_script('resize-listener', get_stylesheet_directory_uri() . '/assets/js/resize-loader.js', array(), null, true);

}

add_action('wp_enqueue_scripts', 'add_js_per_page');
function add_js_per_page() {

  if ( is_front_page() ) { // Replace 'about-us' with the slug of your page
    
    //home.js
    wp_enqueue_script('home-script', get_stylesheet_directory_uri() . '/assets/js/home.js', array('jquery'), null, true);

    if(is_mobile_device()){

      //swiper init()
      $modificated_sliderJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/card-swiper.js'));
      wp_enqueue_script('card-slider-script', get_stylesheet_directory_uri() . '/assets/js/card-swiper.js', array('jquery'), $modificated_sliderJS, true);

    }else{

      //isotope.js
      wp_enqueue_script('isotope-script', get_stylesheet_directory_uri() . '/assets/js/isotope.pkgd.min.js', array('jquery'), null, true);
      
      //isotope init()
      $modificated_isotopeJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/card-isotope.js'));
      wp_enqueue_script('card-isotope-script', get_stylesheet_directory_uri() . '/assets/js/card-isotope.js', array('jquery'), $modificated_isotopeJS, true);

      // is card zone
      //$modificated_is_card_zoneJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/is-card-zone.js'));
      //wp_enqueue_script('is-card-zone-script', get_stylesheet_directory_uri() . '/assets/js/is-card-zone.js', array('jquery'), $modificated_is_card_zoneJS, true);
    
    }

  }
  if(is_page_template( 'page-modelo.php' )){

    //profile.js
    wp_enqueue_script('profile-script', get_stylesheet_directory_uri() . '/assets/js/profile.js', array('jquery'), null, true);
  
  }
 
}

function is_mobile_device() {
  global $device;
  // Get the user agent string
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  
  // Check if the user agent matches any common patterns for mobile devices
  if (preg_match('/android|iphone|ipod|blackberry|iemobile|opera mobile|palmos|webos/i', $userAgent)) {
    $device = true;
    return $device;
  } else {
    $device = false;
    return $device;
  }

}
is_mobile_device();

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
add_image_size( 'custom-card-avatar', 306, 325, array( 'left', 'top' ) );
add_image_size( 'mobile-main-slider', 206, 458, true );

require_once('inc/comments.php'); 


add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function get_vendor_users(){

  global $users;
  global $used_districts;
  global $queried_users;
  global $wa_sender_users;
  global $user_type;
  // WP_User_Query to get all vendors (product vendors)
  //$user_type = get_field('user_type', 'option')? 'wc_product_vendors_admin_vendor' : 'wc_product_vendors_manager_vendor';
  $user_type = 'wc_product_vendors_admin_vendor';
  $user_query = new WP_User_Query( array( 'role' => $user_type ) );
  $users = $user_query->get_results();
  $used_districts = []; // Initialize the used districts array
  $queried_users = [];
  

  if(!empty($users)):
    foreach ($users as $user) :

      $user_name = $user->first_name . ' ' . $user->last_name;
      $user_id = 'user_' . $user->ID;
      $user_slug = $user->user_login;
      // Reduce user dta only to necesary
      $queried_users[] = ['user_id' => $user_id, 'user_name' => $user_name, 'user_slug' => $user_slug];
      $wa_sender_users = $queried_users;
      shuffle($queried_users);

    endforeach;
  endif;

  global $queried_users_slices;
  $queried_users_slices = [
      //'slider' => array_slice($queried_users, 0, 5),
      'moods'  => array_slice($queried_users, 0, 6),
  ];

}
get_vendor_users();

function get_user_by_slug($user_slug) {
  if (!isset($GLOBALS['queried_users']) || !is_array($GLOBALS['queried_users'])) {
    return trigger_404();
  }

  foreach ($GLOBALS['queried_users'] as $user) {
    if (isset($user['user_slug']) && $user['user_slug'] === $user_slug) {
      return $user;
    }
  }

  return trigger_404();
}

function trigger_404() {
  global $wp_query;
  $wp_query->set_404();
  status_header(404);
  include(get_query_template('404'));
  exit;
}

/* function add_custom_capabilities_to_vendor() {
    // Get the role object you want to modify (e.g., 'editor').
    $role = get_role( 'wc_product_vendors_admin_vendor' );

    // Check if the role exists before adding capabilities.
    if ( $role ) {
        // Add a new capability to the role.
        $role->add_cap( 'moderate_comments' ); 
        // You can add more capabilities by repeating the add_cap() line.
        //$role->add_cap( 'edit_posts' );
    }
}
add_action( 'admin_init', 'add_custom_capabilities_to_vendor' );  */

function get_site(){

  global $current_site_cat;
  global $other_site_cat;
  global $venus_link;
  global $trans_link;
  global $venus_class;
  global $trans_class;
  global $gtag;

  $host = $_SERVER['SERVER_NAME'];
  $enviorment = ($host == 'girls.topclassclub.com' || $host == 'trans.topclassclub.com')? 'com' : 'local';
  $gtag = ($host == 'girls.topclassclub.com' || $host == 'topclass-girls.local')? 'G-1TW33ZWPRD' : 'G-GDY39XBR13';
  $current_site_cat = ($host == 'girls.topclassclub.com' || $host == 'topclass-girls.local')? 'Girls' : 'Trans';
  $other_site_cat = ($current_site_cat == 'Girls')? 'Trans' : 'Girls';
  $venus_link = ($current_site_cat == 'Girls')? 'javascript: void(0)' : 'https://girls.topclassclub.'.$enviorment;
  $trans_link = ($current_site_cat == 'Trans')? 'javascript: void(0)' : 'https://trans.topclassclub.'.$enviorment;
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

function my_prefix_customize_app_password_availability( $available, $user ) {
    if ( ! user_can( $user, 'manage_options' ) ) {
        $available = false;
    }
    return $available;
}
add_filter( 'wp_is_application_passwords_available_for_user', 'my_prefix_customize_app_password_availability', 10, 2 );

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

add_filter('get_avatar_url', 'custom_avatar_from_acf', 10, 3);
function custom_avatar_from_acf($url, $id_or_email, $args) {
    // Obtenemos el ID del usuario
    $user = false;

    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', (int) $id_or_email);
    } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
        $user = get_user_by('id', (int) $id_or_email->user_id);
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
    }

    if ($user) {
        // Intentamos obtener el ID de la imagen desde el campo ACF
        $custom_avatar_id = get_field('avatar_personalizado', 'user_' . $user->ID);

        if ($custom_avatar_id) {
            $custom_url = wp_get_attachment_image_url($custom_avatar_id, $args['size']);
            if ($custom_url) {
                return $custom_url;
            }
        }
    }

    // Si no hay avatar personalizado, usamos uno por defecto    get_stylesheet_directory_uri() . '/assets/img/svg/cs-siluettes.svg'
    return ;
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

// Simple Query String Login page protection
function example_simple_query_string_protection_for_login_page() {

$QS = '?tcpraccess=true';
$theRequest = 'https://' . $_SERVER['SERVER_NAME'] . '/' . 'wp-login.php' . '?'. $_SERVER['QUERY_STRING'];

// these are for testing
// echo $theRequest . '<br>';
// echo site_url('/wp-login.php').$QS.'<br>';   

    if ( site_url('/wp-login.php').$QS == $theRequest ) {
        header( 'Location: http://' . $_SERVER['SERVER_NAME'] . '/wp-admin/' );
    } else {
        header( 'Location: http://' . $_SERVER['SERVER_NAME'] . '/' );
    }
}
//add_action('login_head', 'example_simple_query_string_protection_for_login_page');

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
      background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo/iso-TC.svg);
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
      
    #wp-admin-bar-wp-logo{
      display:none;
    }
    .wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{
      content:"";
      background-image:url(<?php echo get_stylesheet_directory_uri().'/assets/img/logo/iso-color.svg' ?> )!important;
      background-size:contain;
      background-repeat:no-repeat;
      background-position:center;
      width: 32px;
      height: 22px;
      display: block;
    }
    #adminmenu li a .update-plugins, 
    #adminmenu li a .awaiting-mod,
    #adminmenu li a.wp-has-current-submenu .update-plugins, 
    #adminmenu li.current a .awaiting-mod,
    #adminmenu .awaiting-mod, 
    #adminmenu .menu-counter, 
    #adminmenu .update-plugins{
      background-color: rgb(110, 205, 255);
    }
    #adminmenu .wp-submenu a:hover, 
    #adminmenu a:hover,
    #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item,
    #wpadminbar .quicklinks .menupop ul li a:hover,
    #adminmenu li.menu-top:hover,
    #adminmenu li>a.menu-top:focus,
    #adminmenu li a:focus div.wp-menu-image::before{
      color: rgb(110, 205, 255);
    }
    .postbox{
      border-radius:6px;
      overflow: hidden
    }
    .postbox-header{
      border-bottom-color: #dfdfdf!important;
      background: #f6f7f7;
    }
    #adminmenu li.current a.menu-top,
    #wpadminbar #wp-admin-bar-cmp-admin-notice .toggle_handler,
    #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu{
      background-color: rgb(237,26,115)!important;
    }
    .toplevel_page_wcpv-vendor-settings, .media-sidebar .compat-item{
      display:none;
    }
    .border-bottom{
      border-bottom: 1px solid #eee;
    }
    .border-right{
      border-right: 1px solid #eee;
    }
    @media screen and (max-width: 782px) {
      .wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{
        width: 34px;
        height: 34px;
      }
      .mobile-acf-100{
        width: 100%!important;
        display: block!important;
        max-width: none!important;
        box-sizing: border-box!important;
        padding-left: 0!important;
        padding-right: 0!important;
      }
      .mobile-acf-100 .image-wrap, .mobile-acf-100 img{
        width: 100%!important;
        max-width: none!important;
        box-sizing: border-box!important;
        padding-left: 0!important;
        padding-right: 0!important;
      }
      .mobile-acf-50{
        width: calc(50% - 2px) !important;
        display: inline-block!important;
        max-width: none!important;
        box-sizing: border-box!important;
        padding-right: 8px!important;
        padding-right: 0!important;
      }
      .mobile-acf-50:nth-child(odd){
        padding-left: 0!important;
        padding-right: 8px!important;
      }
      .mobile-acf-100 td.acf-fields .acf-field{
        padding: 8px!important;
      }
    }
  </style>
<?php
}
add_action('admin_head', 'custom_admin_styles');

//// ENPOINT WA SENDER. //////



// Registramos el endpoint
add_action('rest_api_init', function () {
    register_rest_route('topclass/v1', '/modelos', [
        'methods' => 'GET',
        'callback' => 'topclass_modelos_endpoint',
        'permission_callback' => '__return_true', // público
    ]);
});

function topclass_modelos_endpoint() {
    // Asegurarnos de que los usuarios ya están cargados
    if (!isset($GLOBALS['wa_sender_users']) || !is_array($GLOBALS['wa_sender_users'])) {
        get_vendor_users();
    }

    $result = [];

    foreach ($GLOBALS['wa_sender_users'] as $user) {
        $telefono = get_field('vendor_phone', $user['user_id']) ?: ''; // ACF vendor_phone
        if($telefono){
          $result[$user['user_slug']] = [
              'nombre'   => $user['user_name'],
              'telefono' => $telefono
          ];
        }
    }

    return $result;
}
// Disable or clear default breadcrumb output

if ( ! function_exists( 'the_breadcrumb' ) ) {
    function the_breadcrumb() {
        // No hace nada, por lo que no imprime nada en el HTML
        return;
    }
}

// Left column
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
 
// Right column
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Tabs, upsells and related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_product_additional_information', 'wc_display_product_attributes', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
 
// Reviews
remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );
remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10 );
remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
remove_action( 'woocommerce_review_comment_text', 'woocommerce_review_display_comment_text', 10 );

apply_filters('bootscore/breadcrumb/handler', true);













