/* Scripts */
jQuery(document).ready(function($){
			
    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 1024; //full screen break point
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width();
		
		
	});
		
		
	
	/** Main menu **/
	$('#trigger_menu').on('click', function(e){
		
		var trigger = $(this);
		
		if ($site_header.hasClass('menu-open')) {
			$site_header.find('#site_nav_mobile').slideUp(500, function(){
				$site_header.removeClass('menu-open');
				trigger.removeClass('is-active');
				$site_header.find('#site_nav_mobile').removeAttr('style');
			});
		}
		else {
			$site_header.find('#site_nav_mobile').slideDown(500, function(){
				$site_header.addClass('menu-open');
				trigger.addClass('is-active');
				$site_header.find('#site_nav_mobile').removeAttr('style');
			});		
		}
       		
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();
		
	});
	
	
	/** quote slider **/
	$(".quote-slider").responsiveSlides({
		auto : false,
		pager: false,
		nav : true,
		prevText: "<",   
		nextText: ">",  
	});
	
	
	/** == OLD === **/
	
	//submenu on about page
	$('.about-local').on('click', 'a', function(e){
		
		if($('body').hasClass('slug-about')) {  //allow local scroll
			e.preventDefault();
			
			var full_url = $(this).attr('href'),
				trgt = full_url.split("#")[1],
				target = $("#"+trgt).offset();
			
			$site_header.removeClass('menu-open');
				
			if (target.top) {			
				$('html, body').animate({scrollTop:target.top - 50}, 900);
			}
		}
	});
	
	//no validate no autocomplete
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
	
	
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
	
	// Search forcus on search page 
	function tst_search_focus_position(SearchInput) {
		if (SearchInput.length > 0) {
			var strLength= SearchInput.val().length * 2;
		
			SearchInput.focus();
			SearchInput[0].setSelectionRange(strLength, strLength); //this put cursor in last position
		}
	}
	
	tst_search_focus_position($('#sr_form').find('.search-field'));
	
	
	/** Sticky elements **/
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($('body').hasClass('adminbar')) ? 62+32 + 280 : 62 + 280,
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 95 + 32 + 280 : 95 + 280;
		
	
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//no scroll when menu is open - but not at about page
		if ($site_header.hasClass('menu-open') && !('body').hasClass('slug-about')) {
			$(window).scrollTop(position);			
			return;
		}		
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || tst_scroll_outOfBounds(scroll))
			return true;
		
		//stick header
		/*if (scroll < position) { //upword
			$site_header.removeClass('invisible').addClass('fixed-header');
		}
		else if(scroll >= scrollTopLimit) {
			$site_header.removeClass('fixed-header').addClass('invisible');
		}
		else {
			$site_header.removeClass('fixed-header').removeClass('invisible');
		}*/
		
		//sticky sharing
		if (winW >= breakPointMedium && $('#tst_sharing').length > 0) {
			stickInParent('#tst_sharing .social-likes-wrapper', '#tst_sharing', position, fixedTopPosition);
		}
		
		//sticky sidebar
		if (winW >= breakPointMedium && $('#tst_sidebar').length > 0) {
			stickInParent('#tst_sidebar .related-widget', '#tst_sidebar', position, fixedTopPosition);
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
	function tst_scroll_outOfBounds(scroll) { 
		var	documentH = $(document).height(),
			winH = $(window).height();		
		
		if (scroll < 0 || scroll > (documentH+winH)) 
			return true;
		
		return false;
	}
	
	
	/** == Responsive media == **/
    var resize_embed_media = function(){

        $('iframe, embed, object').each(function(){

            var $iframe = $(this),
                $parent = $iframe.parent(),
                do_resize = false;
				
            if($parent.hasClass('embed-content')){
                do_resize = true;            
            }
			else if($iframe.parents('.so-panel').length) {
				$parent = $iframe.parents('.so-panel');
                do_resize = true;	
			}
            else {                
                
                $parent = $iframe.parents('.entry-content, .player');
                if($parent.length)
                    do_resize = true;				
            }
			
            if(do_resize) {
                var change_ratio = $parent.width()/$iframe.attr('width');
                $iframe.width(change_ratio*$iframe.attr('width'));
                $iframe.height(change_ratio*$iframe.attr('height'));
            }
        });
    };
	
    resize_embed_media(); // Initial page rendering
    $(window).resize(function(){		
		resize_embed_media();	
	});


    /** Leyka custom modal **/
    var leykaTopPad = (windowWidth > 940) ? 120 : 66;

    try {
        $('#leyka-agree-text').easyModal({
            hasVariableWidth : true,
            top : leykaTopPad,
            //transitionIn: 'animated zoomIn',
            //transitionOut: 'animated zoomOut',
            onClose : function(){  }
        });
    }
    catch(ex) {}
	
	
	
	/* Center logos  */
	function logo_vertical_center() {
				
		$('.logo-gallery').each(function(){
			
			var logoH = $(this).find('.logo').eq(0).parents('.bit').height() - 3;
			console.log(logoH);
			
			$(this).find('.logo-frame').find('span').css({'line-height' : logoH + 'px'})
		});		
	}

	imagesLoaded('#site_content', function(){
		logo_vertical_center();
	});

	$(window).resize(function(){
		logo_vertical_center();
	});
	
	
	/* Scroll */
	$('.local-scroll, .menu-scroll a').on('click', function(e){
		e.preventDefault();
		
		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();
					
		if (target.top) {			
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}
		
	});
	
	
	
	$('.social-menu a').each(function(){
	    $(this).attr("target", "_blank");
	});
	
	
}); //jQuery
