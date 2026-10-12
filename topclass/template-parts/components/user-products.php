<?php

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
            $GLOBALS['vendor_price'] = $product_price;
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

?>
<div class="col-md-4 text-center px-md-5 mt-5">
            <h2 class="mb-4">
                <span class="atributos"><i class="fa-solid fa-tag me-2" style="color:#6ecdff"></i>CLP$
                    <span class="precio" id="main-price">
                        <?php echo number_format($GLOBALS['vendor_price'], 0, ',', '.'); ?>
                    </span> /hr
                </span>
            </h2>

            <a href="https://api.whatsapp.com/send?phone=<?php echo $GLOBALS['vendor_phone']; ?>&text=Hola <?php echo $vendor_name; ?>, ¿cómo estás? me podrías contar de tu servicio porfavor" class="btn btn-primary rounded-pill d-block btn-whatsapp mb-4 mx-5" target="_blank" >
                <i class="fa-brands fa-whatsapp"></i> 
                <span id="main-phone"><?php echo split_phone($GLOBALS['vendor_phone']); ?></span>
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