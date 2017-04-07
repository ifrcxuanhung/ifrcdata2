function mainmenu(){
	jQuery("#navigation a").removeAttr("title");
	jQuery("#navigation ul:first").css({display: "none"}); // Opera Fix
	jQuery("#navigation > li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible", display: "none"}).slideDown(400);
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
}


jQuery(document).ready(function($) {
	
	mainmenu(); // Navigation
	
	$("#expand-button").toggleClass('collapse').parent('nav').find('ul').slideToggle();


/* ---------------------------------------------------
	Share button animation
-------------------------------------------------- */

	$('#share-box a, .social-button-holder a').hover(function(){
		$(this).stop().animate({ opacity : 0.5 }, 200 );
		},
		function () {
			$(this).stop().animate({ opacity : 1 }, 200 );
		}
	);


/* ---------------------------------------------------
	Toggle Content
-------------------------------------------------- */

	$(".expanding .expand-button").click(function () {
		$(this).toggleClass('close').parent('div').find('.expand').slideToggle('slow');
	});


/* ---------------------------------------------------
	Login / Signup Buttons
-------------------------------------------------- */
	
	$(".quick-form").css({ display: "none" });
	
	$(".login-link a").click(function () {
		$('#register-form').fadeOut();
		$('#login-form').slideToggle('slow');
		return false
	});
	
	$(".comment-login a").click(function () {
		$('#register-form').fadeOut();
		$('#login-form').slideToggle('slow');
	});
	
	$(".register-link a").click(function () {
		$('#login-form').fadeOut();
		$('#register-form').slideToggle('slow');
		return false
	});
	
/* ---------------------------------------------------
	Image Gallery Hover
-------------------------------------------------- */
	
	$('.gallery-item span').css({visibility: "visible", opacity: 0 });
	
	$('.gallery-item').hover(function(){
		$('span', this).stop().animate({ opacity : 0.8 }, 200 );
		},
		function () {
			$('span', this).stop().animate({ opacity : 0 }, 200 );
		}
	);		

	
/* ---------------------------------------------------
	Contact Form Validation 
-------------------------------------------------- */
	
	if(jQuery.isFunction(jQuery.fn.validate)){
		$("#contactForm").validate();
	}
	
/* ---------------------------------------------------
	NIVO SLIDER
 ----------------------------------------------------- */

	if(jQuery.isFunction(jQuery.fn.nivoSlider)){
		$('#slider').nivoSlider({
			pauseTime: 6000,
			controlNavThumbs: true,
			controlNavThumbsFromRel: true,
			captionOpacity: 1,
			directionNav: false		
		});
	}
	
/* ---------------------------------------------------
	Video JS
-------------------------------------------------- */		
	
	if(jQuery.isFunction(jQuery.fn.VideoJS)){
		VideoJS.setupAllWhenReady();
	}
	
	if( !$('#skin').length ) {
		if( $('.vjs-fullscreen').length ) {
			$('header').css("z-index", "0");
		} else {
			$('header').css("z-index", "auto");
		}	
	}
	
});	