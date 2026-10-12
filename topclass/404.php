<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
 

        <section class="container error-404 not-found">
          <div class="row page-404 justify-content-center">
            <div class="col-md-8 text-center text-light">

              <h1 class="entry-title mb-0 <?= apply_filters('bootscore/class/entry/title', '', '404'); ?>">Ooops!</h1>

              
            
            </div>
          </div>
        </section><!-- .error-404 -->

              <?php get_template_part('template-parts/components/moods'); ?>


<?php
get_footer();
