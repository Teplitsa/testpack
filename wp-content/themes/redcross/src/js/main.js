/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width();
	
	/** Newsletter **/
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
	var nlEmail = $('.nl-form').find('.rdc-textfield__input');
	
	nlEmail
	.on('focus', function(e){
		$(this).parents('fieldset').addClass('focus');
	})
	.on('blur', function(e){
		$(this).parents('fieldset').removeClass('focus');
	});
	
	
	/** Sticky elements **/
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($('body').hasClass('adminbar')) ? 65+32 : 65,
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 86+32 : 86,
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767; //medium screen break point
	
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || rdc_scroll_outOfBounds(scroll))
			return true;
		
		//stick header - to do
		
		//sticky sharing
		if (winW >= breakPointMedium && $('#rdc_sharing').length > 0) {
			stickInParent('#rdc_sharing .social-likes-wrapper', '#rdc_sharing', position, fixedTopPosition);
		}
		
		//sticky sidebar
		if (winW >= breakPointMedium && $('#rdc_sidebar').length > 0) {
			stickInParent('#rdc_sidebar .related-widget', '#rdc_sidebar', position, fixedTopPosition);
		}
		
		position = scroll; //upd scroll position
		return true;
	});
	
	//stick element on scroll
	function stickInParent(el, el_parent, el_position, el_fixedTopPosition) {
		var scroll = $(window).scrollTop(),
			$_el = $(el),
			$_el_parent = $(el_parent),
			topPos = $_el_parent.offset().top,
			height = $_el_parent.outerHeight();	
		
		
		if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //stick on bottom
			if (scroll > el_position) //scroll down
				$_el.addClass('fixed-bottom').removeClass('fixed-top');
		}
		else if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //unstick on bottom
			if (scroll < el_position)
				$_el.removeClass('fixed-bottom').addClass('fixed-top');						
		}
		else if (scroll > topPos - el_fixedTopPosition) { //stick on top
			$_el.removeClass('fixed-bottom').addClass('fixed-top');						
		}
		else {
			$_el.removeClass('fixed-bottom').removeClass('fixed-top'); //normal position		
		}
	}
	
	
	//determines if the scroll position is outside of document boundaries
	function rdc_scroll_outOfBounds(scroll) { 
		var	documentH = $(document).height(),
			winH = $(window).height();		
		
		if (scroll < 0 || scroll > (documentH+winH)) 
			return true;
		
		return false;
	}
	
	/** Equal height **/
	imagesLoaded('.eqh-container', function(){
		$('.eqh-el').responsiveEqualHeightGrid();
		
	});

	$(window).resize(function(){
		$('.eqh-container .eqh-el').responsiveEqualHeightGrid();
	});
	
	
	/* Center logos  */
	function logo_vertical_center() {
		
		var logos = $('.logo-frame'),
			logoH = logos.eq(0).parents('.logo').height() - 3;
			
		logos.find('span').css({'line-height' : logoH + 'px'});
	}

	imagesLoaded('.orgs-gallery', function(){
		logo_vertical_center();
	});

	$(window).resize(function(){
		logo_vertical_center();
	});
	
	
	/* Scroll */
	$('.local-scroll, .inpage-menu a').on('click', function(e){
		e.preventDefault();
		
		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();
					
		if (target.top) {			
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}
		
	});
	
}); //jQuery
