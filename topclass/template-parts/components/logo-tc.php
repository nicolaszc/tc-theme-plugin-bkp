<div class="brand-container d-flex notranslate <?php echo 'site-' . strtolower($GLOBALS['current_site_cat']) ?>" translate="no">
    <a class="brand d-flex align-items-center" href="<?= esc_url(home_url()); ?>" >
    <span>TopClass</span>
    <iso><img src="<?php echo get_logos('iso'); ?>" alt="<?php bloginfo('name'); ?> Logo" class="d-block logo"></iso>
    <span><?php echo $GLOBALS['current_site_cat'] ?></span>
    <sup>&copy;</sup>
    <img src="<?php echo get_logos('sparkle'); ?>" alt="Brillo Logo" class="sparkle">
    </a>
</div>