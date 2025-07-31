<?php

/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;
// Custom vendor comment context
$modelo_slug = get_query_var('modelo_slug');

if ($modelo_slug) {
  
  $comments = get_comments([
    'meta_key'   => 'modelo_slug',
    'meta_value' => $modelo_slug,
    'status'     => ['approve', 'hold'],
    'order'     => 'ASC'
  ]);

  $current_user_email = wp_get_current_user()->user_email;

  // Filter out pending comments not from this user
  $comments = array_filter($comments, function($comment) use ($current_user_email) {
    return $comment->comment_approved == 1 || $comment->comment_hold == 1 ||$comment->comment_author_email === $current_user_email;
  });
}
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
  return;
}
?>
  
  <div id="sticky-comments">
  <div id="comments" class="comments-area">
    <div class="container">
      <div class="row justify-content-center">
        <?php
        // You can start editing here -- including this comment!
        if (have_comments()) : 
          $comments_number = count($comments);
          if ($comments_number > 0) {
        ?>
        <div class="col-md-6 text-start">
          <div class="d-flex align-items-center justify-content-between mb-4">

            <h4 class="comments-title mb-0">
              <?php
              
              if ('1' === $comments_number) {
                /* translators: %s: post title */
                printf(_x('1 Comentarios &ldquo;%s&rdquo;', 'comments title', 'bootscore'), get_the_title());
              } else {
                printf(
                /* translators: 1: number of comments, 2: post title */
                  _nx(
                    '%1$s Comentario',
                    '%1$s Comentarios',
                    $comments_number,
                    'comments title',
                    'bootscore'
                  ),
                  number_format_i18n($comments_number),
                  get_the_title()
                );
              }
              ?>
            </h4><!-- .comments-title -->

            <a href="javascript:void(0)" id="to-commentform" class="btn btn-primary d-block d-md-none rounded-pill" data-scroll="#commentform"><i class="fa-solid fa-comments"></i> <span>Coménta</span></a>
            <a href="javascript:void(0)" id="to-commentform-fixed" class="btn btn-primary d-block d-md-none rounded-pill mb-4 mx-auto" data-scroll="#commentform"><i class="fa-solid fa-comments"></i></a>

          </div>

          

          <ul class="comment-list" id="comment-list">
            <?php
            wp_list_comments(array('callback' => 'bootscore_comment', 'avatar_size' => 16));
            ?>
          </ul><!-- .comment-list -->

          <?php if (get_comment_pages_count() > 1 && get_option('page_comments') && $comments_number > 1) : // Are there comments to navigate through? 
            ?>
            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
              <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'bootscore'); ?></h2>
              <div class="nav-links pagination justify-content-center">
                <?php 
                //echo $comments_number; 
                //echo get_comment_pages_count();

                //paginate_comments_links();
                ?>
                <!-- <div class="nav-previous page-item"><?php previous_comments_link(__('<i class="fa-solid fa-circle-chevron-left"></i> Previous', 'bootscore')); ?></div>
                <div class="nav-next page-item"><?php next_comments_link(__('Next <i class="fa-solid fa-circle-chevron-right"></i>', 'bootscore')); ?></div> -->
               
              </div><!-- .nav-links -->
            </nav><!-- #comment-nav-below -->
          <?php
          endif; // Check for comment navigation.
          ?>
          </div>
          <?php
        }
        endif; // Check for have_comments().
        ?>
      
      <div class="col-md-5 <?php if ($comments_number > 0)  : echo 'offset-md-1'; endif; ?> mb-5 my-md-0" id="commentform-container">
        <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>

          <p class="no-comments alert alert-info"><?php esc_html_e('Comentarios cerrados.', 'bootscore'); ?></p>
        <?php
        endif; ?>

        <?php comment_form($args = array(
          'id_form'           => 'commentform',  // that's the wordpress default value! delete it or edit it ;)
          'id_submit'         => 'commentsubmit',
          'title_reply'       => __('Deja un Comentario', 'bootscore'),  // that's the wordpress default value! delete it or edit it ;)
          'title_reply_to'    => __('Deja un Comentario a %s', 'bootscore'),  // that's the wordpress default value! delete it or edit it ;)
          'cancel_reply_link' => __('Cancelar', 'bootscore'),  // that's the wordpress default value! delete it or edit it ;)
          'label_submit'      => __('Enviar Comentario', 'bootscore'),  // that's the wordpress default value! delete it or edit it ;)

          'comment_field' => '<p><textarea placeholder="' . __('', 'bootscore') . '" id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',

          'comment_notes_before' => '<div class="alert alert-info my-3">' .
                  __( '<i class="fa-solid fa-circle-info"></i> Todos los comentarios están sujetos a aprobacion.', 'bootscore' ) .
                  '</div><p>Los campos obligatorios están marcados con *</p>',

          // So, that was the needed stuff to have bootstrap basic styles for the form elements and buttons

          // Basically you can edit everything here!
          // Checkout the docs for more: http://codex.wordpress.org/Function_Reference/comment_form
          // Another note: some classes are added in the bootstrap-wp.js - ckeck from line 1


          // Custom Bootstrap Formfields
          'fields'        => apply_filters(
            'comment_form_default_fields',
            array(
              'author' => '<p class="comment-form-author">' . '<input id="author" class="py-2 mb-4 form-control" placeholder="' . __('Nombre', 'bootscore') . '" name="author" type="text" value="' .
                          esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req = '' . ' />' .
                                                                                              '</p>',
              'email'  => '<p class="comment-form-email">' . '<input class="form-control "id="email" placeholder="' . __('Email* (no será publicado)', 'bootscore') . '" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
                          '" size="30"' . $aria_req = '' . ' />' .

                                                      '</p>',
              'url'    => '<p class="comment-form-url">' .
                          '<input class="form-control" id="url" name="url" placeholder="' . __('Website', 'bootscore') . '" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /> ' .

                          '</p>'
            )

          ),
          // Custom Formfields End 

        ));

        ?>
        </div><!-- .col-6 -->
      </div>
    </div>
  </div><!-- #comments -->
  </div>