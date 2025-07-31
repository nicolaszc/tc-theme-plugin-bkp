<style>
/* animations */
@keyframes infiniteScroll {
    from {transform: translateX(0) }
    to {transform: translateX(calc(0px - 50%));}
}
.strip{
    top: 0;
    width: 100vw;
    z-index: 10;
    letter-spacing:0.675rem;
    opacity: .75;
}

.horizontal-scrolling-items {
    width: 300%;
    animation-name: infiniteScroll;
    animation-duration: 40s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    padding: 5px 0 50px;
    background: linear-gradient(0deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, .75) 100%);
}
.horizontal-scrolling-items.reverse{
    animation-direction: reverse;
}
@media screen and (max-width: 768px) {
    .horizontal-scrolling-items {
        width: 1300%;
    }
    
}
</style>

<div class="strip position-fixed ">
    <div class="horizontal-scrolling-items d-block d-md-flex position-absolute justify-content-around tags notranslatex" translate="no">
        
            <span>TopClass Club</span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass <?php echo $GLOBALS['other_site_cat'] ?></span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass Club</span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass <?php echo $GLOBALS['other_site_cat'] ?></span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass Club</span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass <?php echo $GLOBALS['other_site_cat'] ?></span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass Club</span><span>·</span>
            <span class="current_site"x>TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
            <span>TopClass <?php echo $GLOBALS['other_site_cat'] ?></span><span>·</span>
            <span class="current_site">TopClass <?php echo $GLOBALS['current_site_cat'] ?></span><span>·</span>
        
    </div>
</div>