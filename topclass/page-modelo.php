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

// Get the user slug from URL
$modelo_slug = get_query_var('modelo_slug');
// Get the user by slug
$user = get_user_by_slug($modelo_slug);
$user_id = $user['user_id'];
$user_slug = $user['user_slug'];
$user_name = $user['user_name'];
$user_image = get_field('vendor_image', $user_id);
$user_state = get_field('vendor_state', $user_id);
$user_district = get_field('vendor_districts', $user_id);

$user_google_map = get_field('vendor_google_map', $user_id);
$user_shows_map = get_field('vendor_shows_map', $user_id);
$lat = !empty($user_google_map['lat']) ? esc_attr($user_google_map['lat']) : null;
$lng = !empty($user_google_map['lng']) ? esc_attr($user_google_map['lng']) : null;
$show_map = ($user_shows_map && $lat && $lng);

$user_phone = get_field('vendor_phone', $user_id);
$user_price = get_field('vendor_price', $user_id);
$user_description = get_field('vendor_description', $user_id);
$user_country = get_field('vendor_country', $user_id);
$user_hair = get_field('vendor_hair', $user_id);
$user_breast = get_field('vendor_breast', $user_id);
$user_back = get_field('vendor_back', $user_id);
$user_dotation = get_field('vendor_dotation', $user_id);
$user_age = get_field('vendor_age', $user_id);
$user_height = get_field('vendor_height', $user_id);
$user_measures = get_field('vendor_measures', $user_id);
$user_gallery = get_field('vendor_gallery', $user_id);
$user_availability = get_field('vendor_availability', $user_id);
$user_schedule = get_field('vendor_schedule', $user_id);
if(!$user_schedule){
    $user_schedule_from = get_field('vendor_schedule_from', $user_id);
    $user_schedule_to = get_field('vendor_schedule_to', $user_id);
    $user_schedule = 'de ' . $user_schedule_from . ' a ' . $user_schedule_to; 
}else{
    $user_schedule = 'Full-time';
}

$user_twitter = get_field('vendor_twitter', $user_id);
$user_instagram = get_field('vendor_instagram', $user_id);
$user_facebook = get_field('vendor_facebook', $user_id);
$user_youtube = get_field('vendor_youtube', $user_id);
$user_image_url = $user_image['sizes']['custom-avatar'];

$user_gallery_enable = ($user_gallery && count($user_gallery) > 3)? true : false;

function rrss($twitter, $instagram, $facebook, $youtube) {
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
    
    return $user_rrss; 

}

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

$user_service = array();
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
            $user_service[] = array(
                'service_id' => $product_id,
                'service_price' => $product_price,
                'service_name' => $product_name,
            );
            $service_done = true;
            $user_price = $product_price;
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

$user_service_length = count($user_service);
$product_array_length = count($product_array);


?>

<style>
.img-vendor{
    object-fit: cover!important;
    object-position: center center!important;
    max-width: unset!important;
    min-width: 100%;
    min-height: 100%;
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
.glightbox-container .gslide-media {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    background: transparent!important;
}

.glightbox-container .ginner-container {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
}
.plyr__video-wrapper{
    background: none!important
}
.plyr__video-wrapper--fixed-ratio video, .plyr--video{
    background: transparent!important;
    height: 100vh !important;
}
/* Asegurar visibilidad de los controles y botón de cierre */
.glightbox-container .gclose,
.glightbox-container .gnext,
.glightbox-container .gprev {
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ajustar posición en pantallas pequeñas si es necesario */
@media (max-width: 768px) {
    .glightbox-container .gclose {
        top: 15px;
        right: 15px;
    }
    .glightbox-container .gnext,
    .glightbox-container .gprev {
        top:50%;
    }
    .plyr__video-wrapper--fixed-ratio video, .plyr--video{
        width: 100vw !important;
        height: 90vh !important;
}
}
@media (max-width: 575.98px) {
    html body .slider-container {
        height: 60vh;
    }
    .item.swiper-slide-active i.type{
    display:inline!important;
    pointer-events: none;
}
}
.glightbox-container .gnext{
    right:15px;
}
.glightbox-container .gprev{
    right:15px;
}
.gallery-counter{
    color: var(--light-color)!important;
    background-image: -moz-linear-gradient(90deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;
    background-image: -webkit-linear-gradient(90deg,  rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;
    background-image: linear-gradient(90deg,  rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;

}
.gallery-counter a{
    color: var(--light-color)!important;
}
.gallery-counter a.swiper-active, .gallery-counter a.swiper-active i{
    color: var(--secondary-color)!important;
}
.item i.type{
    display:none;
    font-size:3rem;
}

.item.swiper-slide-next i.type, .profile-swiper .item:hover i.type{
    display:inline!important;
    pointer-events: none;
}
html body.page-template-page-modelo .top-box-footer {
    height: 40px !important;
    background-image: -moz-linear-gradient(180deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 100%) !important;
    background-image: -webkit-linear-gradient(180deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 100%) !important;
    background-image: linear-gradient(180deg, var(--header-primary-gradient-color) 0%, var(--header-secondary-gradient-color) 100%) !important;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="var(--header-primary-gradient-color )", endColorstr="var(--header-secondary-gradient-color)", GradientType=1) !important;
}

.profile-swiper-button-prev, .profile-swiper-button-next{
    display: inline-block;
    font-size:1rem;
    color: var(--light-color)!important;
}
.swiper-horizontal>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic, 
.swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic{
    transform: none!important;
}
.profile-swiper-pagination{
    margin-left: -3rem;
}
</style>
<div class="text-light" style="margin-top: -20px;">
    <!-- Slider -->
    <div class="row slider-row">
        <div id="<?php echo ($user_gallery_enable)? 'profile-swiper':'profile-swiper-disabled' ; ?>" class="col-md-<?php echo ($show_map)? '8' : '12'; ?> p-0 position-relative overflow-hidden slider-container profile-swiper">
          
        <div class="<?php echo ($user_gallery_enable)? 'swiper-wrapper':'row' ; ?>">
                <?php 
                $i=0; 
                $img=0;
                $video=0;
                if($user_gallery){
                foreach ( $user_gallery as $item ) :                                 
                    if(($item['type'] == 'image') && $item['image'] != ''): 
                        $url = ($item)? $item['image']['sizes']['large'] : get_stylesheet_directory_uri() . '/assets/img/svg/cs-siluettes.svg';
                        $class = 'image';
                        $icon = '<i class="type fa-solid fa-image text-shadow-sm"></i>';
                        $cta = '<i class="cta fa-solid fa-circle-plus"></i>';
                        $img++;
                    else:
                        $url = esc_attr($item['video']);
                        $class = 'video';
                        $icon = '<i class="type fa-solid fa-video text-shadow-sm"></i>';
                        $cta = '<i class="cta fa-solid fa-circle-play"></i>';
                        $video++;
                    endif;
                ?>               
                    <div class="<?php echo ($user_gallery_enable)? 'swiper-slide':'col-md-4 px-0' ; ?> item <?php echo $class ?>" data-gallery="<?php echo($item['type']); ?>">
                        <a href="<?php echo $url; ?>" class="position-relative glightbox d-inline-flex align-items-center justify-content-center <?php echo($item['type']); ?>" data-glightbox="width: 100vw; height: 100vh;" data-gallery="<?php echo($item['type']); ?>">
                            <img src="<?php echo esc_attr($item['image']['sizes']['custom-vendor-desktop-size']); ?>" alt="<?php echo esc_attr($user_slug).'_'.$i+1; ?>" class="img-vendor position-absolute" loading="lazy"/>
                            <?php if($user_gallery_enable){ ?><div class="swiper-lazy-preloader"></div><?php } ?>
                            <?php echo $icon; ?>
                            <?php //echo $cta; ?>
                        </a>
                    </div>
                <?php 
                $i++;
                endforeach; 
                }?>
            </div>
            
            <div class="gallery-counter position-absolute text-center d-flex align-items-center justify-content-between" style="bottom: 0; left: 50%; transform: translateX(-50%); padding: 0; z-index: 9; font-size: .75rem; width: 100%; min-width: 66%; max-width: 100%">
                <div class="owl-counter px-2">
                    <a data-filter="image" href="#" style="padding: .7rem .5rem; display: inline-block"><i class="fa-solid fa-image text-shadow-sm me-1" ></i> <?php echo $img ; ?></a>
                    <a data-filter="video" href="#"style="padding: .7rem .5em; display: inline-block"><i class="fa-solid fa-video text-shadow-sm me-1" ></i> <?php echo $video ; ?></a>
                </div>

                <div class="profile-swiper-pagination"></div>

                <div class="navigation">
                    <div class="profile-swiper-button-prev me-1" style="padding: .7rem 0 .7rem 1rem;"><i class="fa-solid fa-circle-arrow-left text-shadow-sm"></i></div>
                    <div class="profile-swiper-button-next ms-1" style="padding: .7rem 1rem .7rem 0;"><i class="fa-solid fa-circle-arrow-right text-shadow-sm"></i></div>
                </div>
                
            </div>
        </div>
        <?php if($show_map){ ?>
        <div class="col-md-4 p-0">
            <div id="vendor-page-map" class="acf-map" data-zoom="14">
                <div class="marker" data-lat="<?php echo $lat; ?>" data-lng="<?php echo $lng; ?>"></div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="container text-light mt-5 text-center text-md-start">
    <div class="row justify-content-center fixed-row">
        <!-- Datos -->
        <div class="col-md-6 vendor-about">
            <h2 class="mb-3">Sobre mí</h2>
            <?php echo $user_description; ?>
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="row justify-content-end vendor-info">
                <div class="col-md-5" id="vendor_avatar">
                    <img src="<?php echo ($user_image_url )? $user_image_url : get_stylesheet_directory_uri() . '/assets/img/svg/cs-siluettes.svg' ; ?>" class="size-custom-avatar left" loading="lazy" alt="<?php if($user_name) echo $user_name; ?>" />                  
                               
                </div>
                <div class="col-md-5 data mt-4 mt-md-0">
                    <h4 class="mb-1">Mis datos</h4>
                    <p class="d-none" id="main-name"><?php echo $user_name; ?></p>
                    <p class="d-none" id="availability"><span class="d-inline-block rounded-circle shadow ms-md-3 availability <?php if($user_availability) echo esc_attr($user_availability['value']); ?>" ></span><span class="d-none d-md-inline-block text-light ms-2 availability-label"><?php if($user_availability){ echo esc_attr($user_availability['label']); }else{ echo "Disponible"; } ?></span></p>
                    <p class="mb-1"><span class="me-1">Edad: </span> <?php  echo $user_age; ?></p>
                    <?php if($user_country){ ?><p class="mb-1"><span class="me-1">Nacionalidad: </span> <?php echo esc_attr($user_country['label']); ?></p><?php } ?> 
                    <p class="d-none" id="main-location"><?php echo esc_attr($user_district['label']); ?></p> 
                    <p class="mb-4"><span class="me-1">Horario: </span> <?php echo $user_schedule; ?></p>   

                    <h4 class="mt-md-4 mb-md-1">Mis Carácteristicas</h4>
                    <p class="mb-1"><span class="me-1">Altura: </span> <?php  echo $user_height; ?>cm</p>
                    <p class="mb-1"><span class="me-1">Medidas: </span> <?php  echo $user_measures; ?></p>
                    <p class="mb-1"><span class="me-1">Pelo: </span> <?php  echo esc_attr($user_hair); ?></p>
                    <p class="mb-1"><span class="me-1">Busto: </span> <?php  echo esc_attr($user_breast); ?></p>
                    <p class="mb-1"><span class="me-1">Cola: </span> <?php  echo esc_attr($user_back); ?></p>
                       <?php if($_SERVER['HTTP_HOST'] == 'topclass-trans.com'){ ?><p class="mb-4"><span class="me-1">Dotación: </span> <?php  echo esc_attr($user_dotation); ?>cm</p><?php } ?>
                    <div class="rrss fs-5 mt-4">
                        <?php
                            echo rrss($user_twitter, $user_instagram, $user_facebook, $user_youtube);                           
                        ?>
                    </div>
                </div>              
            </div>          
        </div>
        <div class="col-md-4 text-center px-md-5 mt-5">
            <h2 class="mb-4">
                <span class="atributos"><i class="fa-solid fa-tag me-2" style="color:#6ecdff"></i>CLP$
                    <span class="precio" id="main-price">
                        <?php echo number_format($user_price, 0, ',', '.'); ?>
                    </span> /hr
                </span>
            </h2>

            <a href="https://api.whatsapp.com/send?phone=<?php echo $user_phone; ?>&text=Hola <?php echo $user_name; ?>, ¿cómo estás? te ví en https://trans.topclassclub.com/modelo/<?php echo $user_slug; ?> y me podrías contar de tu servicio porfavor" class="btn btn-primary rounded-pill d-block btn-whatsapp mb-4 mx-5" target="_blank" >
                <i class="fa-brands fa-whatsapp"></i> 
                <span id="main-phone"><?php echo split_phone($user_phone); ?></span>
            </a>
            
            <?php if($user_service_length == 1): ?>
                <div class="add-to-cart-container mt-auto">
                    <a href="?add-to-cart=<?php echo $user_service[0]['service_id']; ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo $user_service[0]['service_id']; ?>" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart btn btn-primary d-block rounded-pill mx-5" data-product_id="<?php echo $user_service[0]['service_id']; ?>" data-product_sku="" aria-label="Add to cart: <?php echo $user_service[0]['service_name']; ?>”" rel="nofollow" data-success_message="“<?php echo $user_service[0]['service_name']; ?> has been added to your cart" product-title="<?php echo $user_service[0]['service_name']; ?>">
                        <div class="btn-loader">
                            <span class="spinner-border spinner-border-sm"></span>
                        </div>
                        + Servicio 1hr / PAGA AQUÍ
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
        $pRight = ($product_array_length < 2) ? ' pe-3' : ' pe-0';
        $device = is_mobile_device();
        $device_helper = ($device)? ' row fixed-row' : '';
        $slider_class = ($slider || $device) ? 'product-swiper' : 'row';
        $slider_id = ($slider || $device) ? 'product-swiper' : 'products';
        echo  '<h3 class="mb-3 mt-5 ' . $align .'">Mi contenido('. $product_array_length .')</h3>';

        echo '<div id="'.$slider_id.'" class="justify-content-center align-items-stretch '. $slider_class . $device_helper . $pRight . '" >';
        echo ($slider || $device) ? '<div class="swiper-wrapper">': '' ;  // open slider   
        
            foreach ( $product_array as $product ) :
                ?>
                <div class="<?php echo ($product_array_length <= 6) ? 'card-col col-12 col-md-2' : 'swiper-slide item' ?>">
                    <div class="card border-0 mb-4 mb-md-0">
                        <?php
                        if(get_the_post_thumbnail_url($product['product_id'])):
                            echo get_the_post_thumbnail( $product['product_id'], 'thumbnail', array( 'class' => 'w-100', 'loading' => 'lazy', 'alt' =>  $product['product_name']) );
                            //echo '<img src="'.get_the_post_thumbnail_url($product['product_id'], 'medium').'" class="w-100 wp-post-image" loading="lazy" />';
                        else:
                            echo '<div class="p-4" style="background:rgba(0, 0, 0, .3)"><img src="'.wp_get_attachment_image($product['product_id'],).'" class="card-img-top" loading="lazy" alt="no image" style="aspect-ratio: 1"></div>';
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
            echo ($slider || $device) ? '</div><div class="swiper-pagination"></div>' : '' ; // close slider
        echo '</div>';
    endif;
    wp_reset_postdata(); 
   
    ?>  
</div>
     

        <?php
       
get_footer();