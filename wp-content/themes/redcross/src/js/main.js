/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767; //medium screen break point
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width();
		
		if (winW < breakPointMedium && $site_header.hasClass('newsletter-open')) {
			$site_header.removeClass('newsletter-open');
		}
	});
		
	
	
	
	/** == Header states == **/
	
	/** Drawer **/
	$('#trigger_menu').on('click', function(e){
				
		if ($site_header.hasClass('newsletter-open')) { //close newsletter if any
			$site_header.removeClass('newsletter-open');
		}
		$site_header.addClass('menu-open');
		
		return false;
	});
	
	$('#trigger_menu_close').on('click', function(e){
				
		$site_header.removeClass('menu-open');
		
		return false;
	});
	
	/** Submenu toggle **/
	$site_header.on('click', '.submenu-trigger', function(e){
		
		var li = $(this).parents('.menu-item-has-children');
		if (li.hasClass('open')) {
			li.find('.sub-menu').slideUp(300, function(){				
				li.removeClass('open');
				$(this).removeAttr('style');
			});
		}
		else {		
			
			li.find('.sub-menu').slideDown(300, function(){				
				li.addClass('open');
				$(this).removeAttr('style');
			});
		}
	});
	
	/** Newsletter **/
	$('#trigger_newsletter').on('click', function(e){
		
		var winW = $('#top').width();
				
		if (winW > breakPointMedium && !$site_header.hasClass('newsletter-open')) {
			e.preventDefault();
			e.stopPropagation();
			
			$site_header.find('#newsletter_panel').slideDown(150, function(){				
				$site_header.addClass('newsletter-open');
				$(this).removeAttr('style');
			});
		}
		else if($site_header.hasClass('newsletter-open')) {
			
			$site_header.find('#newsletter_panel').slideUp(150, function(){				
				$site_header.removeClass('newsletter-open');
				$(this).removeAttr('style');
			});
		}
	});
	
	//no autocomplete
	$('.nl-field').find('input').attr('autocomplete', 'off');
	
	
	/** Close by key and click **/
	$(document).on('click', function(e){
		
		var $etarget = $(e.target);
		
				
		if ($site_header.hasClass('menu-open')) {
			if(!$etarget.is('#site_nav, #trigger_menu') && !$etarget.closest('#site_nav, #trigger_menu').length)
				$site_header.removeClass('menu-open');
		}
		else if ($site_header.hasClass('newsletter-open')) {
			if(!$etarget.is('#newsletter_panel, #trigger_newsletter') && !$etarget.closest('#newsletter_panel, #trigger_newsletter').length)
				$site_header.removeClass('newsletter-open');
		}
		
	})
	.on('keyup', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($site_header.hasClass('newsletter-open')) {
				$site_header.removeClass('newsletter-open');
			}
		}
	}).on('keydown', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($site_header.hasClass('newsletter-open')) {
				$site_header.removeClass('newsletter-open');
			}
		}
	});
	
	
	
	/** Sticky elements **/
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($('body').hasClass('adminbar')) ? 62+32 : 62,
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 86+32 : 86;
		
	
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//no scroll when menu is open
		if ($site_header.hasClass('menu-open')) {
			$(window).scrollTop(position);			
			return;
		}		
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || rdc_scroll_outOfBounds(scroll))
			return true;
		
		//stick header
		if (scroll < position) { //upword
			$site_header.removeClass('invisible').addClass('fixed-header');
		}
		else if(scroll => scrollTopLimit) {
			$site_header.removeClass('fixed-header').addClass('invisible');
		}
		else {
			$site_header.removeClass('fixed-header').removeClass('invisible');
		}
		
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
