<?php

/**
 * Template Name: Coming soon
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
$host=$_SERVER['HTTP_HOST'];
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
}
?>

  <style>
    body,  .cs-container{
      max-height:100vh;
      min-height:100vh;
      height:10vh;
      max-width: 100vw;
      width: 100vw;
      overflow:hidden
    }
    
    footer , header, .top-box-footer {
      display:none!important;
    }
    #global-smoke{
      mix-blend-mode: plus-lighter !important;
    }
    #smoke.bkg-video{
      opacity: 1!important;
      margin: 0!important;
    }
    .main, #main-container{
      margin: 0 !important;
      padding: 0 !important;
    }
    .footer-copy{
      position: absolute;
      bottom: .75rem;
      width:100%;
    }
    .cs-container{
      background: linear-gradient(0deg, rgb(38, 1, 16) 0%, rgba(38, 1, 16, .5) 100%) !important
    }
    /* .form-container{
      margin-top: 55vh;
    } */
    .cs-image{
      /* top: 80%;
      left: 50%;
      transform: translate(-50%, -50%); */
      opacity: .75;
      mask-image: linear-gradient(0deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 50%);
      mix-blend-mode: multiply;
    }
    .cs-image img{
      object-fit: scale-down;
      width: 127%;
      margin-left: -10%;
      margin-bottom: -100px;
      max-width: 200%;
    }
    .form-container .row{
      height: 80vh;
    }
    .cs-logo{
      margin-bottom: -140px;
      width: 30%;
    }
    small, small a{
      color: rgb(165, 78, 115);
    }
    small a:hover{
      color: #fff;
    }
    .login-access{
      top: 1rem;
      right: 1rem;
    }
    @media(min-width:768px){
       
      .cs-image img{
        width: 90%;
        margin-left: 0;
      }
      .cs-logo{
        width: 12.5%;
        margin-bottom: -220px;
      }
    }
      
    
  </style>

  <div id="content" class="cs-container">
    <small class="position-absolute login-access"><a href="<?php echo $_SERVER['HTTP_HOST'] ?>/wp-admin"><i class="fa-solid fa-arrow-right"></i> Login</a></small>
    <div class="container form-container">  
      <div class="row mt-5 mt-md-0 text-light justify-content-center align-items-center ">

        <div class="col-12 col-md-6 text-center mt-5 pt-5 mt-md-3 pt-md-3 px-md-4 cs-image" >
          <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/cs-siluettes.svg" class="" />
        </div>

        <div class="col-12 col-md-7 text-center pb-5 mb-5 mb-md-0 newsletter px-md-5 ">          
          <p class="mb-0">Muy pronto cerca de ti!</p> 
          <h5 class="mb-3">¿ Quieres ser una de las 50 chicas Topclass ?</h5> 
          <div class="px-3 px-md-5 mx-md-5">
            <div class="input-group mx-auto mb-0 w-100 rounded-pill">
                <input type="text" class="border-white form-control rounded-start-pill search-input quicksearch" placeholder="email y/o whatsapp*" aria-label="email">
                <button class="input-group-text btn btn-primary rounded-end-pill" id="newsletter">
                    Sí Quiero!
                </button>
            </div>
          </div>
          <small class="mt-2 d-block">(email@xxxxx.com - +(56)9 12 345 678)</small>
        </div>

        <div class="col-10 col-md-8 text-center pt-5 mb-5" >
          <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/logo/topclass50-trans-logo-white-v.svg" alt="topclass" class="cs-logo mt-5 mt-md-0" style="opacity: .75"/>
        </div>

      </div>
    </div>  

    <div class="footer-copy">
      <div class="pb-2 text-center footer-info">            
        <div class="container footer-info">
          <div class="small copyright text-light"><span class="cr-symbol">©</span>&nbsp;2025 TopClass Trans - topclass-tr.com<span class="d-none d-md-inline-block"> - TopclassClub 2025</span>.</div>
        </div>
      </div>
    </div>
      
  </div>

<?php
get_footer();