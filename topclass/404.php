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

              <h1 class="entry-title mb-0 <?= apply_filters('bootscore/class/entry/title', '', '404'); ?>">404</h1>

              <!-- Remove this line and place some widgets -->
              <p class="alert alert-info my-5"><i class="fa-solid fa-circle-info></i> <?php esc_html_e('Page not found.', 'bootscore'); ?></p>
              <!-- 404 Widget -->

              <a class="btn btn-primary rounded-pill px-4" href="<?= esc_url(home_url()); ?>" role="button"><?php esc_html_e('Back Home', 'bootscore'); ?> <i class="fa-solid fa-arrow-right"></i></a>
            
            </div>
          </div>
        </section><!-- .error-404 -->



<?php
get_footer();
