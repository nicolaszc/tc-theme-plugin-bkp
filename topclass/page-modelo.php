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
        $product_preview = get_post_meta( $post->ID, '_topclass_preview_url', true );
        $product_duration = absint( get_post_meta( $post->ID, '_topclass_video_duration_ms', true ) );
        $duration_seconds = (int) round( $product_duration / 1000 );
        $duration_label = floor( $duration_seconds / 60 ) . ':' . str_pad( (string) ( $duration_seconds % 60 ), 2, '0', STR_PAD_LEFT );
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
            'product_preview' => $product_preview,
            'product_duration' => $duration_label,
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
    object-position: center top !important;
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
    /*background-image: -moz-linear-gradient(90deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;
    background-image: -webkit-linear-gradient(90deg,  rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;
    background-image: linear-gradient(90deg,  rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.05) 100%) !important;*/

}
.gallery-counter a{
    color: var(--light-color)!important;
}
.gallery-counter a.swiper-active, .gallery-counter a.swiper-active i{
    color: var(--secondary-color)!important;
}
.item i.type{
    /* display:none; */
    color: var(--light-color)!important;
    font-size:1rem;
    line-height:1rem;
    width:1rem;
    height:1rem;
    position:absolute;
    bottom: 1rem;
    right: 1rem;
}

.item.swiper-slide-next i.type, .profile-swiper .item:hover i.type{
    display:inline!important;
    pointer-events: none;
}

html body.page-template-page-modelo .top-box-footer {
    /*height: 40px !important;*/
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
<!-- <div class="text-light" style="margin-top: -20px;">
    
</div> -->
<div class="text-light mt-5 mt-md-0">

    <div class="container">
        <div class="profile-main row mt-4">
            <div class="col-md-5 pe-md-5">
                <div class="profile-info row justify-content-center position-sticky">
                    <!-- Datos -->
                                     
                    <div class="col-5 col-md-4 d-flex align-items-center justify-content-center flex-column" id="vendor_avatar">
                        <img src="<?php echo ($user_image_url )? $user_image_url : get_stylesheet_directory_uri() . '/assets/img/svg/cs-siluettes.svg' ; ?>" class="size-custom-avatar left shadow mx-5 mx-md-0" loading="lazy" alt="<?php if($user_name) echo $user_name; ?>" />                  
                        <div class="rrss d-flex justify-content-around fs-5 my-4 px-1 w-100">
                            <?php
                                echo rrss($user_twitter, $user_instagram, $user_facebook, $user_youtube);                           
                            ?>
                        </div>  
                    </div>

                    <div class="col-7 col-md-8 data ps-md-4">
                        <h4 class="mb-1">Mis datos</h4>
                        <p class="d-none" id="main-name"><?php echo $user_name; ?></p>
                        <p class="d-none" id="availability"><i class="availability fa-solid fa-circle <?php if($user_availability) echo esc_attr($user_availability['value']); ?>" ></i><span class="d-none d-md-inline-block text-light ms-2 availability-label"><?php if($user_availability){ echo esc_attr($user_availability['label']); }else{ echo "Disponible"; } ?></span></p>
                        <p class="mb-1"><span class="me-1">Edad: </span> <?php  echo $user_age; ?></p>
                        <?php if($user_country){ ?><p class="mb-1"><span class="me-1">Nacionalidad: </span> <?php echo esc_attr($user_country['label']); ?></p><?php } ?> 
                        <p class="d-none" id="main-location"><?php echo esc_attr($user_district['label']); ?></p> 
                        <p class="mb-4"><span class="me-1">Horario: </span> <?php echo $user_schedule; ?></p>   

                        
                        <p class="mt-2 mb-1"><span class="me-1">Altura: </span> <?php  echo $user_height; ?>cm</p>
                        <p class="mb-1"><span class="me-1">Medidas: </span> <?php  echo $user_measures; ?></p>
                        <p class="mb-1"><span class="me-1">Pelo: </span> <?php  echo esc_attr($user_hair); ?></p>
                        <p class="mb-1"><span class="me-1">Busto: </span> <?php  echo esc_attr($user_breast); ?></p>
                        <p class="mb-1"><span class="me-1">Cola: </span> <?php  echo esc_attr($user_back); ?></p>
                        <?php if($_SERVER['HTTP_HOST'] == 'trans.topclassclub.com'){ ?><p class="mb-0"><span class="me-1">Dotación: </span> <?php  echo esc_attr($user_dotation); ?>cm</p><?php } ?>
                        
                    </div>              
                      
                    <div class="col-10 col-md-8 text-center mt-5">

                        <h2 class="mb-4">
                            <span class="atributos"><i class="fa-solid fa-tag me-2" style="color:#6ecdff"></i>CLP$
                                <span class="precio" id="main-price">
                                    <?php echo number_format($user_price, 0, ',', '.'); ?>
                                </span>/hr
                            </span>
                        </h2>

                        <a href="https://api.whatsapp.com/send?phone=<?php echo $user_phone; ?>&text=Hola <?php echo $user_name; ?>, ¿cómo estás? te ví en https://trans.topclassclub.com/modelo/<?php echo $user_slug; ?> y me podrías contar de tu servicio porfavor" class="btn btn-primary rounded-pill d-block btn-whatsapp mb-4" target="_blank" >
                            <i class="fa-brands fa-whatsapp"></i> 
                            <span id="main-phone"><?php echo split_phone($user_phone); ?></span>
                        </a>
                        
                        <?php if($user_service_length == 1): ?>
                            <div class="add-to-cart-container mt-auto">
                                <a href="?add-to-cart=<?php echo $user_service[0]['service_id']; ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo $user_service[0]['service_id']; ?>" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart btn btn-primary d-block rounded-pill" data-product_id="<?php echo $user_service[0]['service_id']; ?>" data-product_sku="" aria-label="Add to cart: <?php echo $user_service[0]['service_name']; ?>”" rel="nofollow" data-success_message="“<?php echo $user_service[0]['service_name']; ?> has been added to your cart" product-title="<?php echo $user_service[0]['service_name']; ?>">
                                    <div class="btn-loader">
                                        <span class="spinner-border spinner-border-sm"></span>
                                    </div>
                                    + Servicio 1hr / PAGA AQUÍ
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 my-5 mb-md-0 text-center text-md-start vendor-about order-5 order-md-4">
                        <h2 class="mb-3">Sobre mí</h2>
                        <?php echo $user_description; ?>
                    </div>

                    <div class="map col-md-12 mt-5 gx-0 gx-md-4 order-4 order-md-5">
                        <div id="vendor-page-map" class="acf-map" data-zoom="14">
                            <div class="marker" data-lat="<?php echo $lat; ?>" data-lng="<?php echo $lng; ?>"></div>
                        </div>
                    </div>

                </div>
            </div> 

            <div class="col-md-7" >

                <div class="media-tabs position-sticky row">
                   
                    <div class="col-12 position-relative gx-0 gx-md-4">
                        <nav>
                            <div class="nav nav-tabs px-2" id="nav-tab" role="tablist">
                                <button class="nav-link d-flex align-items-center active" id="nav-gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery-tab" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Galería</button>
                                <?php if ( $product_array_length > 0 ) : ?><button class="nav-link d-flex align-items-center" id="nav-content-tab" data-bs-toggle="tab" data-bs-target="#media-content-tab" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Contenido <i class="fa-solid fa-money-check-dollar"></i></button><?php endif; ?>
                            </div>
                        </nav>

                        <div class="tab-content" id="profile-media-tab">

                            <!-- Gallerry -->
                            <div class="gallery row gx-0 tab-pane fade show active" id="gallery-tab">
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
                                    <div class="col-6 col-md-4 px-0 item <?php echo $class ?>" data-gallery="<?php echo($item['type']); ?>">
                                        <a href="<?php echo $url; ?>" class="position-relative d-inline-block w-100 overflow-hidden glightbox <?php echo($item['type']); ?>" data-glightbox="width: 100vw; height: 100vh;" data-gallery="<?php echo($item['type']); ?>">
                                            <img src="<?php echo esc_attr($item['image']['sizes']['custom-vendor-desktop-size']); ?>" alt="<?php echo esc_attr($user_slug).'_'.$i+1; ?>" class="img-vendor" loading="lazy"/>
                                            <?php echo $icon; ?>
                                            <?php //echo $cta; ?>
                                        </a>
                                    </div>
                                <?php 
                                $i++;
                                endforeach; 
                                }?>
                                
                            </div>

                            <!-- Content -->
                            <div class="media-content row gx-0 tab-pane fade" id="media-content-tab">
                                <?php
                                $i=0; 
                                $content_video=0;
                                // Products
                                if ( $product_array_length > 0 ) :  
                                    foreach ( $product_array as $product ) :
                                        $content_video++;
                                        ?>
                                        <div class="col-6 col-md-4 px-0 content-col">
                                            <div class="content-card border-0 mb-0 position-relative">
                                                <a href="?add-to-cart=<?php echo $product['product_id']; ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo $product['product_id']; ?>" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart d-block h-100 top-0" data-product_id="<?php echo $product['product_id']; ?>" data-product_sku="" aria-label="Add to cart: <?php echo $product['product_name']; ?>”" rel="nofollow" data-success_message="“<?php echo $product['product_name']; ?> has been added to your cart" product-title="<?php echo $product['product_name']; ?>">
                                                    <?php
                                                    /* if(get_the_post_thumbnail_url($product['product_id'])):
                                                        echo get_the_post_thumbnail( $product['product_id'], 'thumbnail', array( 'class' => 'img-vendor', 'loading' => 'lazy', 'alt' =>  $product['product_name']) );
                                                        //echo '<img src="'.get_the_post_thumbnail_url($product['product_id'], 'medium').'" class="w-100 wp-post-image" loading="lazy" />';
                                                    else:
                                                        echo '<div class="p-4" style="background:rgba(0, 0, 0, .3)"><img src="'.wp_get_attachment_image($product['product_id'],).'" class="card-img-top" loading="lazy" alt="no image" style="aspect-ratio: 1"></div>';
                                                    endif;   */
                                                    if($product['product_preview']){
                                                        echo '<video muted playsinline loop preload="metadata" style="display:block;position: absolute; top: 0; transform: translate(-12.5%,-12.5%); max-width: 125%;" onmouseover="this.play()" onmouseout="this.pause()" class="img-vendor vid-vendor" id="preview-' .$product['product_id'] . '">';
                                                        echo '<source src="' .  $product['product_preview'] . '" type="video/mp4">';
                                                        echo '</video>';
                                                        echo '<button type="button" onclick="playVid(event)" class="d-inline-block d-lg-none position-absolute top-0 end-0 text-white mt-1 btn btn-lg preview-play" id="preview-play-' .$product['product_id'] . '"><i class="fs-5 fa-solid fa-circle-play"></i></button>';
                                                        echo '<button type="button" onclick="pauseVid(event)" class="d-none d-lg-none position-absolute top-0 end-0 text-white mt-1 btn btn-lg preview-pause" id="preview-pause-' .$product['product_id'] . '"><i class="fs-5 fa-solid fa-circle-pause"></i></button>';
                                                    }elseif(get_the_post_thumbnail_url($product['product_id'])){
                                                        echo get_the_post_thumbnail( $product['product_id'], 'thumbnail', array( 'class' => 'img-vendor', 'loading' => 'lazy', 'alt' =>  $product['product_name']) );
                                                    }else{
                                                        echo '<div class="p-4" style="background:rgba(0, 0, 0, .3)"><img src="'.wp_get_attachment_image($product['product_id'],).'" class="card-img-top" loading="lazy" alt="no image" style="aspect-ratio: 1"></div>';
                                                    }                                                
                                                    
                                                    ?>
                                                </a>
                                                <div class="card-body d-flex align-items-between flex-column position-absolute w-100">
                                                    <h6 class="text-center mb-0"><?php echo $product['product_name']; ?></h6>
                                                    <small class="text-center mb-2" style="font-weight: 200; font-size: .75rem"><i class="fa-regular fa-clock"></i> <?php echo $product['product_duration'] ?></small>
                                                    
                                                    <div class="add-to-cart-container mt-auto">
                                                        <span class="btn btn-primary d-block rounded-0">
                                                            + CLP$<span class="precio"><?php echo number_format($product['product_price'], 0, ',', '.'); ?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>              
                                        <?php
                                    endforeach;
                                endif;
                                wp_reset_postdata();                       
                                ?>  
                            </div>
                             <script> 

                                function playVid(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    console.log('play')
                                    
                                    console.log(e.currentTarget);
                                    let vid = e.currentTarget.closest('.add_to_cart_button').querySelector(".vid-vendor"); 
                                    let btnPause = e.currentTarget.closest('.add_to_cart_button').querySelector(".preview-pause"); 

                                    vid.play(); 
                                    vid.classList.add('hover');
                                    e.currentTarget.classList.remove('d-inline-block');
                                    e.currentTarget.classList.add('d-none');
                                    btnPause.classList.remove('d-none');
                                    btnPause.classList.add('d-inline-block');                                                      
                                } 

                                function pauseVid(e) { 
                                    e.preventDefault();
                                    e.stopPropagation();
                                    console.log('pause')
                                    
                                    console.log(e.currentTarget);
                                    let vid = e.currentTarget.closest('.add_to_cart_button').querySelector(".vid-vendor"); 
                                    let btnPlay = e.currentTarget.closest('.add_to_cart_button').querySelector(".preview-play"); 

                                    vid.pause(); 
                                    vid.classList.remove('hover');
                                    e.currentTarget.classList.remove('d-inline-block');
                                    e.currentTarget.classList.add('d-none');
                                    btnPlay.classList.remove('d-none');
                                    btnPlay.classList.add('d-inline-block');  
                                } 

                            </script> 
                        </div>

                        <div class="counter gallery-counter d-flex px-2 me-2 position-absolute top-0 end-0 w-auto">
                            <?php if ( $img > 0 ) : ?><a data-filter="image" href="javascript:void(0)" class="image-filter p-2 lh-1 d-inline-block" ><i class="fa-solid fa-image text-shadow-sm me-1" ></i> <?php echo $img ; ?></a><?php endif; ?>
                            <?php if ( $video > 0 ) : ?><a data-filter="video" href="javascript:void(0)" class="video-filter p-2 lh-1 d-inline-block" ><i class="fa-solid fa-video text-shadow-sm me-1" ></i> <?php echo $video ; ?></a><?php endif; ?>
                        </div>
                        
                        <div class="counter content-counter d-flex px-2 me-2 position-absolute top-0 end-0 w-auto d-none">
                            <?php if ( $content_video > 0 ) : ?><a href="javascript:void(0)" class="video-filter p-2 lh-1 d-inline-block" ><i class="fa-solid fa-video text-shadow-sm me-1" ></i> <?php echo $content_video ; ?></a><?php endif; ?>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>
    </div>

    

    
</div>
     

        <?php
       
get_footer();