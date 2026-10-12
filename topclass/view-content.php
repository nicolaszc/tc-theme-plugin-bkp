<?php

/**
 * Template Name: View Content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
/* $host=$_SERVER['HTTP_HOST'];
if ($host == 'topclass-trans.com'){
  $bkg_img = get_field('body_bg');
  $bkg_size = get_field('body_bg_size');
  $bkg_position = get_field('body_bg_pos');
  $logo = get_field('logo');
  $bajada = get_field('bajada');
  $form = get_field('form');
}else{
  $bkg_img = get_post_meta($post->ID, 'body_bg', true);
  $bkg_size = get_post_meta($post->ID, 'body_bg_size', true);
  $bkg_position = get_post_meta($post->ID, 'body_bg_pos', true);
  $logo = get_post_meta($post->ID, 'logo', true);
  $bajada = get_post_meta($post->ID, 'bajada', true);
  $form = get_post_meta($post->ID, 'form', true);
} */
?>

 

  <div id="content">
    <?php the_content(); 
              ?>
      
  </div>

<?php
get_footer();