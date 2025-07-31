<?php

/**
 * Template Name: Modelo
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
$modelo_slug = get_query_var('modelo_slug');
$vendor_term = get_term_by('slug', $modelo_slug, 'wcpv_product_vendors');
$vendor_id = $vendor_term->term_id;
$vendor_user_id = 'wcpv_product_vendors_' . get_term_meta($vendor_id, '_wcpv_admin_id', true);
$user_id = get_term_meta($vendor_id, '_wcpv_admin_id', true);

$vendor_state = get_field('vendor_state', $vendor_user_id);
$vendor_district = get_field('vendor_districts', $vendor_user_id);
$vendor_google_map = get_field('vendor_google_map', $vendor_user_id);
$vendor_phone = get_field('vendor_phone', $vendor_user_id);
$vendor_price = get_field('vendor_price', $vendor_user_id);
$vendor_description = get_field('vendor_description', $vendor_user_id);
$vendor_country = get_field('vendor_country', $vendor_user_id);
$vendor_hair = get_field('vendor_hair', $vendor_user_id);
$vendor_breast = get_field('vendor_breast', $vendor_user_id);
$vendor_back = get_field('vendor_back', $vendor_user_id);
$vendor_dotation = get_field('vendor_dotation', $vendor_user_id);
$vendor_age = get_field('vendor_age', $vendor_user_id);
$vendor_height = get_field('vendor_height', $vendor_user_id);
$vendor_measures = get_field('vendor_measures', $vendor_user_id);
$vendor_slider = get_field('vendor_slider', $vendor_user_id);
$vendor_desktop_media = $vendor_slider['vendor_media_responsive_uploader']['vendor_media_uploader'];
$vendor_availability = get_field('vendor_availability', $vendor_user_id);
$vendor_user_object = get_user_by( 'id', $user_id );
$vendor_name = $vendor_user_object->first_name . ' ' . $vendor_user_object->last_name;
    
function rrss($twitter, $instagram, $facebook, $youtube, $skype, $google, $pinterest, $linkedin, $github) {
    $user_rrss = '';

    if ( $twitter  ) {
        $user_rrss .= '<a href="'. esc_url($twitter) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-square-x-twitter"></i></a>';
    }
    if( $instagram ){
        $user_rrss .= '<a href="'. esc_url($instagram) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-instagram"></i></a>';
    }
    if( $facebook ){
        $user_rrss .= '<a href="'. esc_url($facebook) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-facebook-f"></i></a>';
    }
    if( $youtube ){
        $user_rrss .= '<a href="'. esc_url($youtube) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-youtube"></i></a>';
    }
    if( $skype ){
        $user_rrss .= '<a href="'. esc_url($skype) .'" target="_blank" class="d-inline-block me-4" ><i class="fa-brands fa-skype"></i></a>';
    }
    if( $google ){
        $user_rrss .= '<a href="'. esc_url($google) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-google-plus-g"></i></a>';
    }
    if( $pinterest ){
        $user_rrss .= '<a href="'. esc_url($pinterest) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-pinterest"></i></a>';
    }
    if( $linkedin ){
        $user_rrss .= '<a href="'. esc_url($linkedin) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-linkedin-in"></i></a>';
    }
    if( $github ){
        $user_rrss .= '<a href="'. esc_url($github) .'" target="_blank" class="d-inline-block" ><i class="fa-brands fa-github"></i></a>';
    }
    return $user_rrss; 

}

$vendor_user_twitter = get_user_meta( $user_id, 'twitter' , true );
$vendor_user_instagram = get_user_meta( $user_id, 'instagram' , true );
$vendor_user_facebook = get_user_meta( $user_id, 'facebook' , true );
$vendor_user_youtube = get_user_meta( $user_id, 'youtube' , true );
$vendor_user_skype = get_user_meta( $user_id, 'skype' , true );
$vendor_user_google = get_user_meta( $user_id, 'google' , true );
$vendor_user_pinterest = get_user_meta( $user_id, 'pinterest' , true );
$vendor_user_linkedin = get_user_meta( $user_id, 'linkedin' , true );
$vendor_user_github = get_user_meta( $user_id, 'github' , true );

$product_args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    'tax_query' => array(
        array(
            'taxonomy' => 'wcpv_product_vendors',
            'field'    => 'slug',
            'terms'    => $modelo_slug,
        ), 
    ),
);
$loop = new WP_Query( $product_args );

$vendor_service = array();
$product_array = array();
$service_done = false;
if ( $loop->have_posts() ) {
    while ( $loop->have_posts() ) : $loop->the_post();
        $product_parent_cat = get_object_vars(wp_get_post_terms( get_the_ID(), 'product_cat' )[0]);
        $product_id = get_the_ID();
        $product_name = get_the_title();
        $product_price = $product->get_price();
        $product_price_html = $product->get_price_html();
        $product_link = get_permalink( $product_id );
        $product_image = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
        if($product_parent_cat['slug'] == 'servicio' && !$service_done):
            $vendor_service[] = array(
                'service_id' => $product_id,
                'service_price' => $product_price,
                'service_name' => $product_name,
            );
            $service_done = true;
            $vendor_price = $product_price;
        else:
        $product_array[] = array(
            'product_category' => $product_parent_cat['slug'],
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_price_html' => $product_price_html,
        );
        endif;
    endwhile;
}
$vendor_service_length = count($vendor_service);
$product_array_length = count($product_array);
$php_device = '';

function isMobileDevice() {
    // Get the user agent string
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    // Check if the user agent matches any common patterns for mobile devices
    if (preg_match('/android|iphone|ipod|blackberry|iemobile|opera mobile|palmos|webos/i', $userAgent)) {
        return true;
        } else {
        return false;
        }
}
?>

<style>
.img-vendor{
    object-fit: cover!important;
    object-position: center center!important;
    max-width: unset!important
}
.vendor-about p{
    line-height: 1.65;
}
.vendor-about p:last-child{
    margin-bottom: 0; 
}
.btn-whatsapp{
    background-color: #25D366;
    border-color: #25D366;
    color: #fff;
}
.btn-whatsapp:hover{
    background-color: #199045;
    border-color: #199045;
    color: #fff;
}
.vendor-about h3{
    font-size:1.75rem;
}
h2 .precio{
    font-size:1.75rem;
}
.vendor-info{
    position: sticky;
    top: 270px;
}
</style>
<div class="text-light" style="margin-top: -50px;">
    <!-- Slider -->
    <div class="row slider-row">
        <div class="col-md-8 p-0 position-relative overflow-hidden slider-container">
            <div class="owl-carousel owl-profile-slider">
                <?php 
                $i=0; 
                $img=0;
                $video=0;
                foreach ( $vendor_desktop_media as $item ) :                                 
                    if(($item['type'] == 'image')): 
                        $url = $item['image']['sizes']['large'];
                        $icon = '<i class="fa-solid fa-image position-absolute fs-4 shadow-lg"></i>';
                        $img++;
                    else:
                        $url = esc_attr($item['video']);
                        $icon = '<i class="fa-solid fa-video position-absolute fs-4 shadow-lg"></i>';
                        $video++;
                    endif;
                ?>               
                    <div class="item">
                        <a href="<?php echo $url; ?>" class="position-relative glightbox d-inline-block <?php echo($item['type']); ?>" style="">
                            <img src="<?php echo esc_attr($item['image']['sizes']['custom-vendor-desktop-size']); ?>" alt="<?php echo esc_attr($vendor_name).$i; ?>" class="img-vendor" loading="lazy"/>
                            <?php echo $icon; ?>
                        </a>
                    </div>
                <?php 
                $i++;
                endforeach; ?>
            </div>
            <div class="galery-counter position-absolute text-center" style="bottom: 0; left: 0; background: rgba(0, 0, 0, .5); padding: .7rem 1rem; color: #fff; z-index: 9; font-size: .75rem;">
                <div class="owl-counter">
                    <i class="fa-solid fa-image shadow-lg me-1" ></i> <?php echo $img ; ?>
                    <i class="fa-solid fa-video shadow-lg ms-3 me-1" ></i> <?php echo $video ; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-0">
            <div id="vendor-page-map" class="acf-map" data-zoom="14">
                <div class="marker" data-lat="<?php echo esc_attr($vendor_google_map['lat']); ?>" data-lng="<?php echo esc_attr($vendor_google_map['lng']); ?>"></div>
            </div>
        </div>
    </div>
</div>
<div class="container text-light mt-5 text-center text-md-start">
    <div class="row justify-content-center">
        <!-- Datos -->
        <div class="col-md-6 vendor-about">
            <h2 class="mb-3">Sobre mí</h2>
            <?php echo $vendor_description; ?>
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="row justify-content-end vendor-info">
                <div class="col-md-5" id="vendor_avatar">                  
                    <?php echo do_shortcode('[user_profile_avatar user_id="'. $user_id. '" size="custom-avatar"]'); ?>             
                </div>
                <div class="col-md-5 data mt-4 mt-md-0">
                    <h4 class="mb-1">Mis datos</h4>
                    <p class="d-none" id="main-name"><?php echo $vendor_name; ?></p>
                    <p class="d-none" id="availability"><span class="d-inline-block rounded-circle shadow ms-md-3 availability <?php if($vendor_availability) echo esc_attr($vendor_availability['value']); ?>" ></span><span class="d-none d-md-inline-block text-light ms-2 availability-label"><?php if($vendor_availability){ echo esc_attr($vendor_availability['label']); }else{ echo "Disponible"; } ?></span></p>
                    <p class="mb-1"><span class="me-1">Edad: </span> <?php  echo $vendor_age; ?></p>
                    <p class="mb-4"><span class="me-1">País: </span> <?php echo $vendor_country; ?></p>
                    <p class="d-none" id="main-location"><?php echo esc_attr($vendor_district['label']); ?></p> 
                    <p class="d-none"><?php echo $vendor_state; ?></p>   

                    <h4 class="mt-md-4 mb-md-1">Mis Carácteristicas</h4>
                    <p class="mb-1"><span class="me-1">Altura: </span> <?php  echo $vendor_height; ?>cm</p>
                    <p class="mb-1"><span class="me-1">Medidas: </span> <?php  echo $vendor_measures; ?></p>
                    <p class="mb-1"><span class="me-1">Pelo: </span> <?php  echo esc_attr($vendor_hair); ?></p>
                    <p class="mb-1"><span class="me-1">Busto: </span> <?php  echo esc_attr($vendor_breast); ?></p>
                    <p class="mb-1"><span class="me-1">Cola: </span> <?php  echo esc_attr($vendor_back); ?></p>
                       <?php if($_SERVER['HTTP_HOST'] == 'topclass-trans.com'){ ?><p class="mb-4"><span class="me-1">Dotación: </span> <?php  echo esc_attr($vendor_dotation); ?>cm</p><?php } ?>
                    <div class="rrss fs-5">
                        <?php
                            echo rrss($vendor_user_twitter, $vendor_user_instagram, $vendor_user_facebook, $vendor_user_youtube, $vendor_user_skype, $vendor_user_google, $vendor_user_pinterest, $vendor_user_linkedin, $vendor_user_github);                           
                        ?>
                    </div>
                </div>              
            </div>          
        </div>
        <div class="col-md-4 text-center px-md-5 mt-5">
            <h2 class="mb-4">
                <span class="atributos"><i class="fa-solid fa-tag me-2" style="color:#6ecdff"></i>CLP$
                    <span class="precio" id="main-price">
                        <?php echo number_format($vendor_price, 0, ',', '.'); ?>
                    </span> /hr
                </span>
            </h2>

            <a href="https://api.whatsapp.com/send?phone=<?php echo $vendor_phone; ?>&text=Hola <?php echo $vendor_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" class="btn btn-primary rounded-pill d-block btn-whatsapp mb-4 mx-5" target="_blank" >
                <i class="fa-brands fa-whatsapp"></i> 
                <span id="main-phone"><?php echo split_phone($vendor_phone); ?></span>
            </a>
            
            <?php if($vendor_service_length == 1): ?>
                <div class="add-to-cart-container mt-auto">
                    <a href="?add-to-cart=<?php echo $vendor_service[0]['service_id']; ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo $vendor_service[0]['service_id']; ?>" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart btn btn-primary d-block rounded-pill mx-5" data-product_id="<?php echo $vendor_service[0]['service_id']; ?>" data-product_sku="" aria-label="Add to cart: <?php echo $vendor_service[0]['service_name']; ?>”" rel="nofollow" data-success_message="“<?php echo $vendor_service[0]['service_name']; ?> has been added to your cart" product-title="<?php echo $vendor_service[0]['service_name']; ?>">
                        <div class="btn-loader">
                            <span class="spinner-border spinner-border-sm"></span>
                        </div>
                        + Servicio 1hr / Paga aquí
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
       
    <?php
    // Products
    if ( $product_array_length > 0 ) :
        $align = ($product_array_length >= 6) ? 'text-start' : 'text-center';
        $slider = ($product_array_length > 6) ? true : false;
        $device = isMobileDevice();
        $slider_class = ($slider || $device) ? 'slider' : '';
        echo  '<h3 class="mb-3 mt-5 ' . $align .'">Mi contenido('. $product_array_length .')</h3>';

        echo '<div id="products" class="row justify-content-center align-items-stretch '. $slider_class .'" >';
        echo ($slider || $device) ? '<div class="owl-carousel owl-product-slider">': '' ;  // open slider   
        
            foreach ( $product_array as $product ) :
                ?>
                <div class="card-col <?php echo ($product_array_length <= 6) ? 'col-12 col-md-2' : 'item' ?>">
                    <div class="card border-0 mb-4 mb-md-0">
                        <?php
                        if(get_the_post_thumbnail_url($product['product_id'])):
                            echo get_the_post_thumbnail( $product['product_id'], 'thumbnail', array( 'class' => 'w-100', 'loading' => 'lazy', 'alt' =>  $product['product_name']) );
                            //echo '<img src="'.get_the_post_thumbnail_url($product['product_id'], 'medium').'" class="w-100 wp-post-image" loading="lazy" />';
                        else:
                            echo '<div class="p-4" style="background:rgba(0, 0, 0, .3)"><img src="'.get_stylesheet_directory_uri().'/assets/img/svg/placeholder.svg" class="card-img-top" loading="lazy" alt="no image" ></div>';
                        endif;  
                        ?>
                        <div class="card-body d-flex align-items-between flex-column">
                            <h6 class="text-center"><?php echo $product['product_name']; ?></h6>
                            
                            <p class="text-center">CLP$<span class="precio"><?php echo number_format($product['product_price'], 0, ',', '.'); ?></span></p>
                            <div class="add-to-cart-container mt-auto">
                                <a href="?add-to-cart=<?php echo $product['product_id']; ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo $product['product_id']; ?>" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart btn btn-primary d-block rounded-pill" data-product_id="<?php echo $product['product_id']; ?>" data-product_sku="" aria-label="Add to cart: <?php echo $product['product_name']; ?>”" rel="nofollow" data-success_message="“<?php echo $product['product_name']; ?> has been added to your cart" product-title="<?php echo $product['product_name']; ?>">
                                    + Lo quiero
                                </a>
                            </div>
                        </div>
                    </div>
                </div>              
                <?php
            endforeach;
            echo ($slider || $device) ? '</div>' : '' ; // close slider
        echo '</div>';
    endif;
    wp_reset_postdata(); 
    
    ?>  
</div>
     

        <?php
       
get_footer();