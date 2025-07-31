
<style>
    #global-smoke{
        display: var(--bkg-video-display);
        pointer-events: none;
        position: fixed;
        bottom: 0;
        z-index: 0;
        mix-blend-mode: var(--bkg-video-blend-mode);
        -webkit-filter: blur(10px); /* Safari 6.0 - 9.0 */
        filter: blur(10px);
        width: 100%;
        height: 100%;
        mask-image: linear-gradient(0deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 1));
    }
    #smoke.bkg-video{   
        object-fit: cover; 
        opacity: var(--bkg-video-opacity);
        height:100%;
        width: 100%;
        margin-bottom:50px;   
    }
    #main-slider .bkg-video{
        display: var(--bkg-video-display); 
        position: absolute;
        object-fit: cover;  
        mix-blend-mode: var(--slider-video-blend-mode);
        height: 100%;
        width: 100%;
        z-index: 5;
        top: 0;
        opacity: var(--slider-video-opacity);
    }
   
</style>
<div id="global-smoke" class="smoke-wrapper">
    <video id="smoke" class="bkg-video" autoplay loop muted src="<?php echo get_stylesheet_directory_uri() ?>/assets/video/smoke<?php echo (is_mobile_device())? '-mobile' : '' ?>.mp4"></video>
</div>
