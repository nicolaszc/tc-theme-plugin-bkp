<?php
/*
Plugin Name:  Topclass MP
Plugin URI:   http:/topclass.club
Description:  Topclass MP WordPress Plugin
Version:      1.0
Author:       Nicolás Zakrzewicz
Author URI:   http:/topclass.club
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/


// Add vendors fieds to vendors settings
// 1. Ensure acf_form_head runs early
add_action('admin_head', 'acf_form_head');
add_action('admin_menu', function() {
$vendor_id = get_current_user_id();
$vendor_term = get_user_meta($vendor_id, '_wcpv_active_vendor', true);

if ($vendor_term) {
    add_menu_page(
        __('Tu espacio','text-domain'), // Page title
        __('Tu espacio','text-domain'), // Menu title
        'wc_product_vendors_admin_vendor', // Capability (vendors should have access)
        'datos-imagenes', // Menu slug
        'custom_vendor_settings_page', // Callback function
    'dashicons-edit-large', // Icon
    63 // Position
    );
}
});

function custom_vendor_settings_page() {
    //$host = $_SERVER['HTTP_HOST'];
	$vendor_id = get_current_user_id();
	$vendor_term = get_user_meta($vendor_id, '_wcpv_active_vendor', true);
	//var_dump($vendor_term);
	if (!$vendor_term) {
        echo '<p>' . __('No tienes creado un perfil de modelo aún.', 'text-domain') . '</p>';
        return;
    }

    add_filter('gettext', function($translated_text, $text, $domain) {
    if (is_user_logged_in() && $translated_text === 'Perfil') {
        $user_id = get_current_user_id();
        $is_vendor = get_user_meta($user_id, '_wcpv_active_vendor', true);
        if ($is_vendor) {
            return 'Cuenta';
        }
    }
    return $translated_text;
    }, 10, 3);

    ?>
    <div class="wrap">
        <h1><?php _e('Tu espacio', 'text-domain'); ?></h1>
        <form method="post">
            <?php
            $fields = array('profile_image', 'vendor_availability','vendor_schedule', 'show_map','vendor_price','vendor_phone','vendor_country','vendor_districts','vendor_age','vendor_height','vendor_measures','vendor_hair','vendor_breast','vendor_back','main_screen', 'vendor_slider','vendor_description','vendor_google_map');

            if ($GLOBALS['current_site_cat'] == 'Trans') {
                
                array_splice($fields, 14, 0, 'vendor_dotation');
            }

            //$fields = array_merge($fields, array('main_slider', 'moods', 'vendor_slider', 'vendor_description', 'vendor_google_map'));

            acf_form(array(
                'post_id' => 'term_' . $vendor_id,
                'fields' => $fields,
                'submit_value' => __('Save Settings', 'text-domain')
            ));
            ?>
        </form>
    </div>
    <?php
}