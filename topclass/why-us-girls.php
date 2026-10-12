<?php

/**
 * Template Name: Why Us Girls
 *
 * @package Bootscore
 * @version 6.0.0
 */

defined('ABSPATH') || exit;

get_header();
// Recibe parámetros desde la URL
$pre_user = isset($_GET['user']) ? esc_attr($_GET['user']) : '';
$pre_pass = isset($_GET['password']) ? esc_attr($_GET['password']) : '';
$pre_slug = isset($_GET['slug']) ? esc_url(home_url()).'/modelo/'.esc_attr($_GET['slug']) : esc_url(home_url());
?>
<style>
:root{
    --main-color-landing-girls: rgba(71, 46, 30, 0);
    --features-gradient-landing-girls: linear-gradient(180deg, rgb(71, 46, 30) 10px, rgba(57, 37, 24, .5) 20%, rgba(71, 46, 30, .65) 50%, rgba(71, 46, 30, 0) 100%);
}
.table > :not(caption) > * > *{
    background-color: transparent;
    color: white;
}
.table thead th{
    background-color: transparent;
    border: none;
}
.table tbody tr:nth-child(odd) th, .table tbody tr:nth-child(odd) td{
    background-color: rgba(255,255,255,.05);
    border: none;
}
.table tbody tr:nth-child(even) th, .table tbody tr:nth-child(even) td{
    background-color: rgba(255,255,255,.1);
    border: none;
}
.top-box-footer{
    height:75px;
}
@media (min-width: 576px) {
    html body .btn-lg{
        font-size: .875rem!important;
    }
}
.table th, .table td {
    white-space: normal; /* permite que el texto haga wrap */
    word-break: break-word; /* evita que se desborde la celda */
}
th, td {
    hyphens: auto;          /* Habilita los guiones automáticos */
    word-break: break-word;  /* Permite cortar palabras largas */
    overflow-wrap: break-word; /* Compatibilidad extra */
    vertical-align: middle;
}
.text-success{
    color: #25D366!important;
}
.text-danger{
    color: #ff0018!important;
}
.arrow-form{
    width: 38px; 
    height: 105px; 
    transform: rotate(0deg) scaleY(1) scaleX(-1); 
    opacity: 1; 
    bottom: -20px; 
    left: 15%;
}
.arrow-features{
    width: 112px; 
    height: 223px; 
    transform: rotate(-35deg)  scaleY(1) scaleX(-1); 
    opacity: 0.25; 
    top: -105px; 
    right: 70px;
}
.arrow-pricing{
    width: 70px; 
    height: 150px; 
    transform: rotate(0deg) scaleY(1) scaleX(-1); 
    opacity: 0.25; 
    bottom: -50px; 
    left: 40px;
}
@media (min-width: 576px){
    .table tbody th{
        padding-left: 1rem;
    }
    .arrow-form{
        top: -65px;
        right: 37%;
        transform: rotate(30deg) scaleY(1.125) scaleX(-1.125);
        left: unset;
    }
    .arrow-features{
        top: -210px;
        left: 29%;
        transform: rotate(30deg)  scaleY(1.125) scaleX(1.125); 
    }
    .arrow-pricing{
        bottom: -120px;
        right: 100px;
        left: unset;
        transform: rotate(0deg) scaleY(1.125) scaleX(1.125);
    }
}
.why-us-top-image-wrapper{
    width: 65%;
}
.why-us-top-image{
    mask-image: radial-gradient(at center left, rgba(0, 0, 0, 0.1) 25%, black 100%);
}
@media (max-width: 576px){
    .why-us-top-image-wrapper{
            width: 90%;
    }
}
/* Spotlight */
.wrapper {
    height: calc(100vh - 135px);
    width: 100vw;
    margin: 0 auto;
    position: absolute;
    top: -45px;
    overflow-y: hidden;
    opacity: .8;
}
.backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-image: linear-gradient(#000000, rgba(25,53,93,1) 50%, rgba(25,53,93,1));
}
.stage_floor {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: calc(100vh - 90px);
    opacity: .75;
}
.stage_highlight {
    position: absolute;
    top: 40px;
    left: 10vw;
    width: 60vw;
    height: 80vh;
    overflow: hidden;
    background-image: radial-gradient(ellipse closest-side at 50% 82%, rgb(84,120,173), rgba(84,120,173,0) 100%);
    animation: move_highlight 3s linear infinite;
    opacity: 1;
}
.spotlight_swivel {
    animation: rotate_spotlight 3s linear infinite;
}
.lamp {
    position: absolute;
    width: 20vw;
    height: 120vh;
    background-image: radial-gradient(ellipse, rgba(82, 116, 168, 1), rgba(82, 116, 168, .75) 25%, rgba(82, 116, 168, 0) 50%);
    top: -65vh;
    left: 23vw;
    transform: perspective(23vh) rotateX(30deg);
}
.spotlight {
    opacity: 0.1;
    transform: scale(1.5,2.25);
    position: absolute;
    top: -20vh;
    left: calc(50% - 75vh);
    width:100vh;
}
.spotlight::after {
    content: '';
    position: absolute;
    top: 0;
    background-image: radial-gradient(ellipse closest-corner at 75% 75%, rgba(55, 89, 138, 0), rgba(55, 89, 138, 0));
    border-radius: 100%;
    width: 110vh;
    height: 60vh;
    clip: rect(55vh, 100vh, 90vh, 55vh);
    transform: rotate(270deg);
    left: 25vw;
}
.spotlight-fix{
    position: absolute;
    bottom: 0;
    height: 50px;
    width: 100vw;
    background: black;
}
@media (max-width: 576px){
    .wrapper {
        overflow: hidden;
        height: 45vh;
        background-image: linear-gradient(0deg, rgb(140 135 135 / 0%) -100%, rgb(255 255 255 / 0%) 100%);
        top: -75px;
    }
    .stage_highlight{
        height: 37.5vh;
        left: 15vw;
    }
    .spotlight_swivel {
    animation: rotate_spotlight 3s linear infinite;
    left: 15%;
    position: absolute;
    }
    .lamp {
        position: absolute;
        width: 35vw;
        height: 60vh;
        background-image: radial-gradient(ellipse, rgba(82, 116, 168, 1), rgba(82, 116, 168, 0.75) 25%, rgba(82, 116, 168, 0) 50%);
        top: -15vh;
        left: 5vw;
        transform: perspective(23vh) rotateX(45deg);
    }
    .stage_floor {
        height: 100%;
    }
}
@keyframes rotate_spotlight {
    0% {
        transform: rotate(0deg) scaleY(1) translateY(0);
    }
    25% {
        transform: rotate(-45deg) scaleY(1.1) translateY(-3vh);
    }
    50% {
        transform: rotate(0deg) scaleY(1) translateY(0);
    }
    75% {
        transform: rotate(15deg) scaleY(1.1) translateY(-3vh);
    }
}

@keyframes move_highlight {
    0% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(55vh);
    }
    50% {
        transform: translateX(0);
    }
    75% {
        transform: translateX(-7.5vh);
    }
}
@keyframes bright_highlight {
    0% {
        opaciyty: 0;
    }
    25% {   
        opaciyty: .8;
    }
    50% {
        opaciyty: .8;
    }
    75% {           
        opaciyty: 0;
    }
}
@media (max-width: 576px){
    @keyframes move_highlight {
        0% {
        transform: translateX(0);
        }
        25% {
            transform: translateX(44.5vw);
        }
        50% {
            transform: translateX(0);
        }
        75% {
            transform: translateX(-35vw);
        }
    }
}
.lamp-fix {
    width: 50%;
    height: 16px;
    left: 25%;
    bottom: unset;
    top: 33.5vh;
    background-image: radial-gradient(ellipse, rgba(84, 120, 173, 0.1) 0%, rgba(84, 120, 173, 0) 50%);
    animation: bright_highlight 3s linear infinite, move_highlight 3s linear infinite;
}
.lamp-fix-glow{
    width: 50%;  
    height: 500px;
    left: 25%;
    bottom: unset;
    top: -18vh;
    background-image: radial-gradient(ellipse, rgba(84, 120, 173, 0.15) 0%, rgba(84, 120, 173, 0) 50%);
    animation: move_highlight 3s linear infinite ;
}
#hero{
    position: relative;
    overflow-y: hidden;
}
#hero:before{
    content: '';
    display: block;
    position: absolute;
    width: 100%;
    height: 13px;
    top: 58.5%;
}
#hero:after{
    content: '';
    display: block;
    position: absolute;
    width: 100%;
    height: 1px;
    bottom: 11px;
}
#features{
    top: 0;
    border-top: none;
}
#main-container{
    margin-top: -60px;
}
.wall{
    opacity: .2;
    top: -21px;
    width: 100%;
    mix-blend-mode: multiply;
}
.left-crack{
    left: 15%;
    width: 1.125%;
    top: -2px;
    transform: scale(.8);
}
.small-left-crack{
    left: 20%;
    width: 1.125%;
    top: -5px;
    transform: scaleY(.6) scaleX(.35);
}
.center-crack{
    right: 50%;
    top: 0px;
    width: .625%;
    transform: scaleY(.875);
}
.small-right-crack{
    right: 35%;
    width: 1.125%;
    top: -5px;
    transform: scaleY(.8) scaleX(.35);
}
.right-crack{
    right: 2.5%;
    top: -2px;
    width: 1.125%;
    transform: scaleX(-.75) scaleY(.75);
}
.plant{
    width: 5%;
    bottom: 2.5rem;
    right: 1%;
    opacity: .5;
}
@media (min-width: 576px){
    .lamp-fix{
        left: 15%;
        top: unset;
        bottom: 4px;
        background-image: radial-gradient(ellipse, rgba(84, 120, 173, 0.1) 0%, rgba(84, 120, 173, 0) 50%);
    }
    .lamp-fix-glow{
        left: 12%;
        top: unset;
        bottom: -110px;
        background-image: radial-gradient(ellipse, rgba(84, 120, 173, 0.15) 20%, rgba(84, 120, 173, 0) 45%);
    }
    #main-container{
        margin-top: -30px;
    }
    #hero{
        height: calc(100vh - 185px);
    }
    #hero:before{
        top: calc(58% + 10px);
    }
    #hero:after{
        bottom: 9px;
    }
    #hero .fs-5{
        font-size: 1.375rem !important;
    }
    #features{
        top: -10px;
    }
}
</style>
<?php the_content() ?>
<section id="hero" class="pb-5 z-3">
    <div class="container pt-md-0 px-4 text-light position-relative z-2"> 
        <div class="row align-items-center g-lg-5 position-relative z-2 mb-5 my-md-0"> 
            <div class="col-lg-7 text-center text-lg-start why-us-top-text"> 
                <h1 class="display-4 fw-bold lh-1 mb-3 text-light mt-4 mt-md-0 pt-5 pt-md-0">
                    TopClass: tu vitrina digital para brillar y ganar más
                </h1>
                <p class="col-lg-10 fs-5 mb-4 mb-md-0 mt-5 mt-md-0 pt-4 pt-md-0">
                    Con TopClass puedes mostrar tu belleza, tus servicios y tus contenidos de manera profesional, directa y segura. Tú decides cómo presentarte, cuánto cobrar y cómo conectar con tus clientes sin intermediarios.
                </p> 
            </div> 
            <div class="col-md-10 mx-auto col-lg-4 offset-lg-1"> 
                <form action="/wp-login.php" method="post" class="p-4 px-md-5 py-md-4 my-md-3 rounded-3 card text-dark">
                    <div class="form-floating mb-3 mt-4">
                        <input type="text" name="log" class="form-control" id="floatingInput" placeholder="Usuario" value="<?php echo $pre_user; ?>">
                        <label for="floatingInput">Usuario</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="pwd" class="form-control" id="floatingPassword" placeholder="Contraseña" value="<?php echo $pre_pass; ?>">
                        <label for="floatingPassword">Contraseña</label>
                    </div>

                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" name="rememberme" value="forever" checked> Recordarme
                        </label>
                    </div>

                    <input type="hidden" name="redirect_to" value="/wp-admin/profile.php">
                    <button type="submit" class="w-100 btn btn-primary btn-lg rounded-pill" title="Accede al panel para editar tu perfil">
                        <i class="fa-solid fa-user-pen me-2"></i> Edita tu perfil
                    </button>

                    <hr class="my-4">

                    <small class="mb-4 d-block alert alert-info">
                        * Tu cuenta ya está lista. Usa las credenciales que recibiste por WhatsApp para empezar.
                    </small>
                </form>
            </div> 
        </div>
        <div class="why-us-top-image-wrapper position-absolute top-0 m-auto z-1">
            <div class="position-relative">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/girls-siluettes-why-us.svg">
            </div>  
        </div>
    </div>
    <div class="plant position-absolute z-1">
        <div class="position-relative">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/plant.svg">
        </div>  
    </div>
    <div class="lamp-fix position-absolute z-3"></div>
    <div class="lamp-fix-glow position-absolute z-3"></div>
</section>

<div class="container position-relative z-3 ">
    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="94" fill="none" class="position-absolute z-10 d-block arrow-form">
        <path fill="#836757" d="M15.62 78.242c-.529-6.6-1.122-13.196-1.575-19.8a437 437 0 0 0-6.7-52.378c-.216-1.13-.522-2.36-.274-3.424.183-.782 1.13-1.796 1.871-1.923.726-.124 1.85.56 2.38 1.224.582.728.772 1.818.984 2.783a337 337 0 0 1 7.105 52.804 176 176 0 0 0 2.31 21.465 19 19 0 0 0 1.707-2.528c1.983-4.208 3.783-8.506 5.907-12.64.466-.906 2.1-1.915 2.992-1.755 1.816.325 1.94 1.987 1.373 3.59-.277.782-.55 1.568-.897 2.32-3.394 7.4-6.722 14.832-10.236 22.174-2.034 4.246-4.35 4.446-6.773.626-4.984-7.858-9.827-15.806-14.715-23.726-.345-.56-.817-1.195-.805-1.786.018-.91.175-2.385.688-2.593a3.97 3.97 0 0 1 3.142.23c.916.553 1.464 1.759 2.098 2.729 2.8 4.282 5.572 8.582 8.355 12.876z"></path>
    </svg>  
</div>
<div class="wall position-relative z-3">
    <div class="position-absolute left-crack">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/side-crack.svg">
    </div>  

    <div class="position-absolute small-left-crack">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/small-crack.svg">
    </div>  

    <div class="position-absolute center-crack">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/center-crack.svg" >
    </div>  

    <div class="position-absolute small-right-crack">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/small-crack.svg">
    </div>  

    <div class="position-absolute right-crack">
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/side-crack.svg" >
    </div>  
</div>
<!-- Features -->
<section id="features" class="pt-4 pb-5 py-md-5 position-relative z-2">
    
    <div class="container px-4 text-light "> 
        <div class="row align-items-md-center mt-md-5"> 
            <div class="col-md-3 offset-md-1 mb-5 mb-md-0"> 
                <h2 class="fw-bold text-light">Todo lo que necesitas para destacar</h2> 
                <p>
                    Tu perfil, tus contenidos y tu comunidad en un solo lugar. Control total, pagos seguros y clientes felices.
                </p> 
                <a href="<?php echo $pre_slug; ?>" class="btn btn-primary btn-lg rounded-pill px-4" target="_blank"><i class="fa-solid fa-user me-2"></i> Visita tu perfil</a>
            </div> 
            <div class="col-md-6  offset-md-1"> 
                <div class="row"> 

                    <div class="col-md-6"> 
                        <div class="feature-icon-small d-inline-flex align-items-start justify-content-start pb-2 fs-4 rounded-3" style="background-color: transparent"> 
                            <i class="fa-solid fa-user fs-1"></i>
                        </div> 
                        <h4 class="fw-semibold mb-0 text-light">Perfil que seduce</h4> 
                        <p class="mt-1 mb-4">
                            Fotos, videos y tu agenda organizada para que tus clientes siempre sepan lo mejor de ti.
                        </p>
                    </div> 

                    <div class="col-md-6"> 
                        <div class="feature-icon-small d-inline-flex align-items-start justify-content-start pb-2 fs-4 rounded-3" style="background-color: transparent"> 
                            <i class="fa-solid fa-credit-card fs-1"></i>
                        </div> 
                        <h4 class="fw-semibold mb-0 text-light">Pagos directos y seguros</h4> 
                        <p class="mt-1 mb-4">
                            Cobra fácilmente por tus servicios sin intermediarios. Más control, más libertad.
                        </p> 
                    </div> 

                    <div class="col-md-6"> 
                        <div class="feature-icon-small d-inline-flex align-items-start justify-content-start pb-2 fs-4 rounded-3" style="background-color: transparent"> 
                            <i class="fa-solid fa-film fs-1"></i> 
                        </div> 
                        <h4 class="fw-semibold mb-0 text-light">Vende tu contenido</h4> 
                        <p class="mt-1 mb-4">
                            Fotos y videos exclusivos que tus seguidores adorarán y estarán dispuestos a pagar.
                        </p> 
                    </div> 

                    <div class="col-md-6"> 
                        <div class="feature-icon-small d-inline-flex align-items-start justify-content-start pb-2 fs-4 rounded-3" style="background-color: transparent"> 
                            <i class="fa-solid fa-hexagon-nodes fs-1"></i>
                        </div> 
                        <h4 class="fw-semibold mb-0 text-light">TopClass Club</h4> 
                        <p class="mt-1 mb-4">
                            Conecta con tus fans, comparte novedades y haz que siempre quieran más.
                        </p> 
                    </div> 

                    <div class="col-12"> 
                        <div class="feature-icon-small d-inline-flex align-items-start justify-content-start pb-2 fs-4 rounded-3" style="background-color: transparent"> 
                            <i class="fa-solid fa-dice fs-1"></i> 
                        </div> 
                        <h4 class="fw-semibold mb-0 text-light">Posicionamiento aleatorio</h4> 
                        <p class="mt-1 mb-0">
                            Cada vez que alguien visita tu perfil o recarga la página, el orden cambia. Esto elimina viejos vicios como autocomentar, revivir fotos usadas o aprovechar trucos de ranking. Así, todas tienen las mismas oportunidades de destacar: <span class="fw-medium">la suerte siempre es justa.</span>
                        </p> 
                    </div>
                </div> 
            </div> 
        </div>    
    </div>
</section>
<div class="container position-relative pt-4 pb-5 py-md-0">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="198" fill="none" class="position-absolute z-10 d-md-block arrow-features">
        <path fill="#FFFFFF" d="M99.13 1.426c.259.723-.518 2.084-1.216 2.8-.32.327-.774.516-1.258.68l-.654.214c-.272.092-.54.192-.784.32-22.305 11.705-39.8 28.204-50.65 51.213-3.095 6.564-4.12 11.915-3.886 17.65l.478-.146 1.397-.414c.61-.182 1.206-.369 1.792-.583 10.085-3.694 20.044-2.759 29.666 1.38 10.303 4.432 14.247 15.026 9.91 25.383-6.33 15.123-22.766 19.096-35.257 8.487-6.159-5.231-9.94-11.972-12.182-19.65l-.644-2.23q-.161-.555-.329-1.111a.5.5 0 0 0-.092-.157l-.063-.074-.175-.197-.114-.134-.064-.078a5.6 5.6 0 0 0-1.544.621c-16.064 12.675-26.084 28.882-27.29 49.55-1.3 22.305 14.74 41.813 36.577 45.506 6.869 1.162 17.919-.554 25.757-4.145l-5.046-2.402-1.43-.684-.474-.228a12 12 0 0 0-.537-.24l-.554-.228-.367-.155c-.606-.263-1.178-.561-1.585-1.005-.655-.715-1.222-2.2-.896-2.928s1.845-1.34 2.776-1.259c.77.067 1.516.398 2.26.763l.608.302c.27.134.541.264.813.379l1.903.805 3.808 1.605a533 533 0 0 1 7.596 3.259c3.687 1.625 4.06 3.463 1.38 6.387a3169 3169 0 0 1-13.536 14.677q-.142.153-.287.32l-.526.608-.188.209-.126.136-.129.133-.13.128-.132.123c-.822.74-1.76 1.115-2.937.106-1.338-1.148-1.092-2.322-.41-3.417l.105-.164.111-.163.116-.161.12-.159.185-.237.126-.156.264-.318.324-.386a263 263 0 0 1 4.06-4.74l2.053-2.35 1.024-1.177-.313-.88-9.622 2.217c-30.65 7.109-53.976-16.939-57.695-39.655-2.05-12.522.433-24.203 5.685-35.393 5.747-12.243 13.722-22.8 25.204-30.32 1.835-1.202 2.62-2.554 2.683-4.699l.004-.175c.116-10.271 3.611-19.57 8.718-28.297 11.375-19.44 27.32-34.055 47.2-44.422l.604-.313A16.7 16.7 0 0 1 96.453.013c.86-.124 2.416.68 2.678 1.413M41.07 80.868c.186 8.937 5.013 18.58 11.856 23.922 9.346 7.296 20.556 4.531 25.56-6.306 3.794-8.215 1.038-15.91-7.368-19.198-10.024-3.922-20.062-4.233-30.048 1.582"></path>
    </svg>
</div> 

<!-- Pricing -->
<div class="container position-relative"> 
    <div class="row text-center align-items-stretch pt-4 pb-5 mt-md-5"> 
        <div class="col-md-4 d-flex align-items-stretch"> 
            <div class="card w-100 mb-4 rounded-3 shadow-sm border-0"> 
                <div class="card-header py-3 border-0"> 
                    <h4 class="my-0 fw-normal text-light">Otros sitios</h4> 
                </div> 
                <div class="card-body d-flex justify-content-center flex-column">
                    <h2 class="card-title pricing-card-title">
                        +$150.000
                        <small class="text-body-secondary fw-light">/mes</small>
                    </h2> 
                    <ul class="list-unstyled mt-3 mb-4"> 
                        <li>Planes caros</li> 
                        <li>Pocas herramientas</li> 
                        <li>Sin guía personalizada</li>
                        <li>Soporte limitado</li> 
                    </ul>  
                </div> 
            </div> 
        </div> 
        <div class="col-md-4 d-flex align-items-stretch"> 
            <div class="card w-100 mb-4 rounded-3 shadow-sm border-0"> 
                <div class="card-header py-3 border-0" > 
                    <h4 class="my-0 fw-normal text-light">Competencia</h4> 
                </div> 
                <div class="card-body d-flex justify-content-center flex-column"> 
                    <h2 class="card-title pricing-card-title">
                        +$200.000
                        <small class="text-body-secondary fw-light">/mes</small>
                    </h2>
                    <ul class="list-unstyled mt-3 mb-4"> 
                        <li>Comisiones ocultas</li> 
                        <li>Restricciones en tu contenido</li> 
                        <li>Perfiles poco personalizados</li> 
                        <li>Sin comunidad real</li> 
                    </ul> 
                </div> 
            </div> 
        </div> 
        <div class="col-md-4 d-flex align-items-stretch"> 
            <div class="card w-100 mb-4 rounded-3 card-topclass border-0"> 
                <div class="card-header py-3 border-0 text-bg-warning"> 
                    <h4 class="my-0 fw-normal">TopClass</h4> 
                </div> 
                <div class="card-body">
                    <h2 class="card-title pricing-card-title">
                        $<span class="text-decoration-line-through">100.000</span>
                        <small class="text-body-secondary fw-light">/mes</small>
                    </h2> 
                    <ul class="list-unstyled mt-3 mb-4"> 
                        <li>Todo lo que necesitas para destacar</li> 
                        <li>Soporte real y cercano</li> 
                        <li>Sin comisiones ocultas</li> 
                        <li>Comunidad activa y fanbase propia</li>
                    </ul>
                    <p class="fw-semibold mb-1">
                        <span class="fw-bold fs-5 d-block">
                            Oferta especial de lanzamiento
                        </span>
                    </p>       
                    <h1 class="card-title pricing-card-title">
                        $75.000
                        <small class="text-body-secondary fw-light">/mes</small>
                    </h1>
                    <small class="d-block mb-2"> 
                        Si te unes antes del día 21 de Septiembre sólo pagas $75.000 hasta finales de Octubre.
                    </small>
                    <small class="d-block mb-2"> 
                        En Noviembre sigues pagando $75.000.
                    </small>
                    <small class="d-block mb-4"> 
                        Recién en Diciembre vuelves a pagar el precio regular de $100.000.
                    </small>
                    <a href="https://mpago.la/2XGxFpW" target="_blank" class="btn btn-warning btn-lg rounded-pill mt-3 px-4">
                        PAGA AHORA AQUÍ
                    </a>
                    <small class="d-block my-4">
                        <strong>* Transferencias a:</strong><br>
                        TC Entertainment Spa<br>
                        RUT: 78.082.852-8<br>
                        Mercado Pago<br>
                        Cuenta Vista<br>
                        Número de cuenta: 1042659160<br><br>
                        Para pago directo o transferencia enviar comprobante a ceo@topclass.club<br>
                    </small>
                </div>
            </div> 
        </div> 
    </div> 
</div>

<div class="container position-relative py-4 py-md-0 ">
    <svg xmlns="http://www.w3.org/2000/svg" width="82" height="102" fill="none" class="position-absolute z-10 d-md-block arrow-pricing">
            <path fill="#FFFFFF" d="M44.975 92.82c2.15-1.717 3.31-2.635 4.462-3.562 28.062-22.594 24.327-65.217-7.304-82.502-3.616-1.977-7.585-3.33-11.439-4.848-1.697-.668-3.435-.481-4.08 1.555-.667 2.1.924 2.914 2.51 3.64 4.368 1.998 9.032 3.546 13.034 6.118 25.979 16.703 27.813 52.68 3.797 71.754-1.269 1.006-2.676 1.846-4.63 3.182.11-1.946.133-3.195.266-4.432.142-1.318.442-2.62.574-3.938.166-1.643-.273-3.31-2.131-3.222-.902.042-2.266 1.44-2.504 2.441a115 115 0 0 0-2.114 11.76 4.63 4.63 0 0 0 3.172 5.12c3.894 1.445 7.85 2.75 11.862 3.8.957.25 2.77-.406 3.257-1.21.962-1.59-.214-2.809-1.795-3.381-1.99-.723-4.024-1.328-6.937-2.276"></path>
    </svg>
</div>
<!-- Table -->
<div class="container">
    <h2 class="text-center mt-0 mb-3 text-light fw-bold mt-md-5">Compara y elige</h2> 
    <div class="table-responsive text-light"> 
        <table class="table text-center"> 
            <thead> 
                <tr> 
                    <th style="width: 34%;"></th> 
                    <th style="width: 20%;">Otros sitios</th> 
                    <th style="width: 24%;">Competencia</th> 
                    <th style="width: 22%;">Top Class</th> 
                </tr> 
            </thead> 
            <tbody> 
                <tr> 
                    <th scope="row" class="text-start">Planes accesibles</th> 
                    <td><i class="fa-solid fa-check opacity-50 fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Venta de contenido <small class="badge bg-warning text-dark ms-md-1">Destacado</small></th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Pago online <small class="badge bg-warning text-dark ms-md-1">Destacado</small></th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr>
                <tr> 
                    <th scope="row" class="text-start">Control total</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check opacity-50 fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Comunidad y fans</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Soporte cercano</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Talleres de capacitación <small class="badge bg-warning text-dark ms-md-1">Destacado</small><br><small class="fw-light">(Edición de video, retoque fotográfico, maquillaje, etc.)</small></th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Apoyo en gestión de arriendos <small class="badge bg-warning text-dark ms-md-1">Destacado</small><br><small class="fw-light">(Disponible al alcanzar suficiente volumen de clientas)</small></th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Video en vivo con seguidores</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check opacity-50 fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Funciones de cine para adulto</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr> 
                <tr> 
                    <th scope="row" class="text-start">Comentarios con respuesta directa</th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr>
                <tr> 
                    <th scope="row" class="text-start">Posicionamiento aleatorio <small class="badge bg-warning text-dark ms-md-1">Destacado</small></th> 
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-xmark text-danger fs-5 text-shadow"></i></td>
                    <td><i class="fa-solid fa-check text-success fs-5 text-shadow"></i></td>
                </tr>  
            </tbody> 
        </table>

    </div> 
</div>

<!-- CTA Final -->
<div class="text-center pt-5 px-4">
    <h2 class="fw-bold text-light mb-3">¿Lista para brillar y ganar más?</h2>
    <p class="fs-5 text-light mb-5">Tu vitrina digital está lista. Comparte tu belleza, tus servicios y contenidos, y comienza a crecer hoy mismo.</p>
    <a href="https://mpago.la/2XGxFpW" targett="_blank" class="btn btn-warning btn-lg rounded-pill px-4">PAGA AHORA AQUÍ</a>
</div>
<div class="wrapper z-0">
    <div class="stage_floor"></div>	
    <div class="stage_highlight"></div>				
    <div class="spotlight_swivel">					
        <div class="lamp"></div>
        <div class="spotlight"></div>					
    </div>
</div> 
<?php
get_footer();
?>
