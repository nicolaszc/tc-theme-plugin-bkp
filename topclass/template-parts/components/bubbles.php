<style>
/* Bubbles*/
.bubbles-wrapper {
    pointer-events: none;
	z-index: 1;
	position: absolute;
    top: 0;
    width: 100vw;
	height:90vh;
	overflow: hidden;
}

/* Ketframes */
@-webkit-keyframes animateBubble {
    0% {
        bottom: -200px;
    }
    100% {
        top: -200px;
    }
}
@-moz-keyframes animateBubble {
    0% {
        bottom: -200px;
    }
    100% {
        top: -200px;
    }
}
@keyframes animateBubble {
    0% {
        bottom: -200px;
    }
    100% {
        top: -200px;
    }
}
@-webkit-keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}
@-moz-keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}
@keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}

/* Animations */
.bubble[data-speed]{
	--speed: attr(data-speed s, 4s);
}
.x1 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: -5%;
	top: 90%;
	
	-webkit-transform: scale(0.6);
	-moz-transform: scale(0.6);
	transform: scale(0.6);
}

.x2 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 5%;
	top: 85%;
	
	-webkit-transform: scale(0.4);
	-moz-transform: scale(0.4);
	transform: scale(0.4);
}

.x3 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 10%;
	top: 70%;
	
	-webkit-transform: scale(0.7);
	-moz-transform: scale(0.7);
	transform: scale(0.7);
}

.x4 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	
	left: 20%;
	top: 60%;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

.x5 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 30%;
	top: 50%;
	
	-webkit-transform: scale(0.5);
	-moz-transform: scale(0.5);
	transform: scale(0.5);
}

.x6 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 50%;
	top: 65%;
	
	-webkit-transform: scale(0.8);
	-moz-transform: scale(0.8);
	transform: scale(0.8);
}

.x7 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 65%;
	top: 70%;
	
	-webkit-transform: scale(0.4);
	-moz-transform: scale(0.4);
	transform: scale(0.4);
}

.x8 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 3s ease-in-out infinite alternate;
	
	left: 80%;
	top: 75%;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

.x9 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 90%;
	top: 55%;
	
	-webkit-transform: scale(0.6);
	-moz-transform: scale(0.6);
	transform: scale(0.6);
}

.x10 {
	-webkit-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble var(--speed) infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 80%;
	top: 80%;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

/* Objects */
.bubble {
    -webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	
    -webkit-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15), inset 0px 10px 30px 5px rgba(255, 255, 255, 0.25);
	-moz-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15), inset 0px 10px 30px 5px rgba(255, 255, 255, 0.25);
	box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15), inset 0px 10px 30px 5px rgba(255, 255, 255, 0.25);
	
    height: 200px;
	position: absolute;
	width: 200px;
}

.bubble:after {
    background: -moz-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); /* FF3.6+ */
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,255,255,0.15)), color-stop(70%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
    background: -webkit-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.15) 0%,rgba(255,255,255,0) 70%); /* Chrome10+,Safari5.1+ */
    background: -o-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.15) 0%,rgba(255,255,255,0) 70%); /* Opera 12+ */
    background: -ms-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.15) 0%,rgba(255,255,255,0) 70%); /* IE10+ */
    background: radial-gradient(ellipse at center,  rgba(255,255,255,0.15) 0%,rgba(255,255,255,0) 70%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#80ffffff', endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	
    -webkit-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.15);
	-moz-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.15);
	box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.15);
	
	content: var(--speed);
    height: 180px;
	left: 10px;
	position: absolute;
	width: 180px;
}
</style>


<div class="bubble x1"></div>
<div class="bubble x2"></div>
<div class="bubble x3"></div>
<div class="bubble x4"></div>
<div class="bubble x5"></div>
<div class="bubble x6"></div>
<div class="bubble x7"></div>
<div class="bubble x8"></div>
<div class="bubble x9"></div>
<div class="bubble x10"></div>
