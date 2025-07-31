<?php

/**
 * Comments
 *
 * @package Bootscore 
 * @version 6.0.4
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Comment reply
 */
add_action('wp_enqueue_scripts', 'bootscore_reply');


/**
 * Comments
 */
if (!function_exists('bootscore_comment')) :
  /**
   * Template for comments and pingbacks.
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   */
  function bootscore_comment($comment, $args, $depth) {
    // $GLOBALS['comment'] = $comment;

    if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) : ?>

      <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media alert alert-info'); ?>>
      <div class="comment-body">
        <?php _e('Pingback:', 'bootscore'); ?><?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'bootscore'), '<span class="edit-link">', '</span>'); ?>
      </div>

    <?php else : ?>

      <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>

        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body d-flex">

          <div class="flex-shrink-0 me-3">
            <?php /* if (get_avatar() == false): 
            echo 'no';
             
            else: */
            $avatar = get_avatar($comment, 80, 'mystery', '', array('class' => apply_filters('bootscore/class/comment/avatar', 'img-thumbnail rounded-circle')));
            if($avatar) {
              echo $avatar ; 
            }else{
              echo '<img alt="" src="https://topclass50.com/wp-content/uploads/2025/04/topclass-logo.svg" srcset="https://topclass50.com/wp-content/themes/topclass/assets/img/profiles/anonymous.webp" class="avatar avatar-80 photo img-thumbnail rounded-circle" height="80" width="80" loading="lazy" decoding="async">';
            }
            
            //get_avatar($comment, 80, 'mystery', '', array('class' => apply_filters('bootscore/class/comment/avatar', 'img-thumbnail rounded-circle'))); 
            //endif;
            ?>
            </div>

          <div class="comment-content">
            <div class="comment-card">
              <div class="comment-card-body text-light">

                <?php printf('<h3 class="h5">%s</h3>', get_comment_author()); ?>

                <p class="small comment-meta">
                  <time datetime="<?php comment_time('c'); ?>">
                    <?php printf(_x('%1$s a las %2$s', '1: date, 2: time', 'bootscore'), get_comment_date(), get_comment_time()); ?>
                  </time>
                  <?php edit_comment_link(__('Edit', 'bootscore'), '<span class="edit-link">', '</span>'); ?>
                </p>


                <?php if ('0' == $comment->comment_approved) : ?>
                  <p class="comment-awaiting-moderation alert alert-info"><?php _e('Your comment is awaiting moderationxxx.', 'bootscore'); ?></p>
                <?php endif; ?>

                <?php comment_text(); ?>

                <?php comment_reply_link(
                  array_merge(
                    $args,
                    array(
                      'add_below' => 'div-comment',
                      'depth'     => $depth,
                      'max_depth' => $args['max_depth'],
                      'before'    => '<p class="reply comment-reply">',
                      'after'     => '</p>'
                    )
                  )
                ); ?>
              </div> <!-- card-body -->
            </div><!-- card -->
          </div><!-- .comment-content -->

        </article><!-- .comment-body -->
      </li><!-- #comment -->

    <?php
    endif;
  }
endif;


/**
 * h2 Reply Title
 */
add_filter('comment_form_defaults', 'custom_reply_title');


/**
 * Comment Cookie Checkbox
 * See https://github.com/bootscore/bootscore/issues/921
 */

add_filter('comment_form_default_fields', 'bootscore_change_comment_form_cookies_consent');


/**
 * Open comment author link in new tab
 */
add_filter('get_comment_author_link', 'open_comment_author_link_in_new_window');


/**
 * Open links in comments in new tab
 */
if (!function_exists('bs_comment_links_in_new_tab')) :
  add_filter('comment_text', 'bs_comment_links_in_new_tab');
endif;


/**
 * Comment Button
 */
if (!function_exists('bootscore_comment_button')) :
  add_filter('comment_form_defaults', 'bootscore_comment_button');
endif;
