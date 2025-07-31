<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>


    <div class="container">
      <div class="row">
        <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?> entry-content">



            <!-- <div class="entry-header">
              <?php the_post(); ?>
              <?php do_action( 'bootscore_before_title', 'page' ); ?>
              <?php the_title('<h1 class="entry-title ' . apply_filters('bootscore/class/entry/title', '', 'page') . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'page' ); ?>
              <?php bootscore_post_thumbnail(); ?>
            </div> -->
            
            <?php do_action( 'bootscore_after_featured_image', 'page' ); ?>


              <?php the_content(); ?>
 
            
            <?php do_action( 'bootscore_before_entry_footer', 'page' ); ?>

            
      </div>
    </div>
  </div>

<?php
get_footer();
