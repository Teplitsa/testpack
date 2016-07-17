/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		$search_toggle = $('#search_toggle'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 984; //large screen break point
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width();
		
		if (winW < breakPointMedium && $site_header.hasClass('newsletter-open')) {
			$site_header.removeClass('newsletter-open');
		}
	});
		
	
	
	
	/** == Header states == **/
	/** Hover **/
	var $_mainmenu = $('#full_main_menu');
	
	$_mainmenu.on('mouseenter', '.menu-item-has-children', function(e){
		
		var currentItem = $(this),
			shouldAnimate = true;
		
		if (currentItem.hasClass('current-menu-item') || currentItem.hasClass('current-menu-parent')) {
			if(currentItem.find('.sub-menu').css('z-index') == 50){
				shouldAnimate = false;
			}
		}
		
		if (shouldAnimate) {
			$_mainmenu.find('.sub-menu').finish().css({
				'opacity' : 0,
				'z-index' : 10
			});
			
			$('#site_subnav').css({
				'opacity' : 0,
				'z-index' : 10
			});
			
			currentItem.find('.sub-menu').animate({
				'opacity' : 1,
				'z-index' : 50
			}, 300);
		}	
		
	})
	.on('mouseleave', '.menu-item-has-children', function(e){
		var currentItem = $(this);
		
		$_mainmenu.find('.sub-menu').finish().css({
			'opacity' : 0,
			'z-index' : 10
		});
		
	})
	.on('mouseleave', function(e){
		
		$_mainmenu.find('.sub-menu').finish().css({
			'opacity' : 0,
			'z-index' : 10
		});
		
		var $_currentMenu = $_mainmenu.find('.current-menu-item .sub-menu, .current-menu-parent .sub-menu');
		
		if ($_currentMenu.length > 0) {
			$_currentMenu.css({
				'opacity' : 1,
				'z-index' : 50
			});
		}
		else {
			$('#site_subnav').css({
				'opacity' : 1,
				'z-index' : 50
			});
		}	
	});
	
	/** Drawer **/
	$('#trigger_menu').on('click', function(e){
				

		$('#site_nav_mobile').find('.current-menu-item.menu-item-has-children, .current-menu-parent.menu-item-has-children').addClass('open');
		$site_header.addClass('menu-open');
		
		
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();
		
	});
	
	$('#trigger_menu_close').on('click', function(e){
		
		$site_header.removeClass('menu-open');
		
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();		
	});
	
	/** Submenu toggle  **/
	$('.submenu-trigger').on('click', function(e){
		
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
	
	/** Search toggle **/
	$('#trigger_search').on('click', function(e){
				
		var winW = $('#top').width();
				
		if (winW > breakPointLarge && !$search_toggle.hasClass('toggled')) {
			e.preventDefault();
			
			$(this).hide();			
			$search_toggle.find('.searchform').animate({'width' : '100%'}, 400, function(){
				$search_toggle.addClass('toggled').find('.searchform').removeAttr('style');
			});
						
			$(this).removeAttr('style');			
		}
	});
	
	$('.search-field')
		.on('focus', function(e){
			$(this).parents('form').addClass('focus');
		})
		.on('blur', function(e){
			$(this).parents('form').removeClass('focus');
		});
	
	//no validate no autocomplete
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
		
	/** Close by key and click **/
	$(document).on('click', function(e){
		
		var $etarget = $(e.target);
		
				
		if ($site_header.hasClass('menu-open')) {
			if(!$etarget.is('#site_nav_mobile, #trigger_menu') && !$etarget.closest('#site_nav_mobile, #trigger_menu').length)
				$site_header.removeClass('menu-open');
		}
		else if ($search_toggle.hasClass('toggled')) {
			if(!$etarget.is('#search_toggle') && !$etarget.closest('#search_toggle').length){
				$search_toggle.find('.searchform').animate({'width' : '0'}, 400, function(){
					$search_toggle.removeClass('toggled').find('.searchform').removeAttr('style');
				});				
			}
		}
		
	})
	.on('keyup', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($search_toggle.hasClass('toggled')) {
				$search_toggle.find('.searchform').animate({'width' : '0'}, 400, function(){
					$search_toggle.removeClass('toggled').find('.searchform').removeAttr('style');
				});	
			}
		}
	}).on('keydown', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($search_toggle.hasClass('toggled')) {
				$search_toggle.find('.searchform').animate({'width' : '0'}, 400, function(){
					$search_toggle.removeClass('toggled').find('.searchform').removeAttr('style');
				});	
			}
		}
	});
	
	// Search focus on search page 
	function tst_search_focus_position(SearchInput) {
		if (SearchInput.length > 0) {
			var strLength= SearchInput.val().length * 2;
		
			SearchInput.focus();
			SearchInput[0].setSelectionRange(strLength, strLength); //this put cursor in last position
			SearchInput.parents('form').addClass('focus');
		}
	}
	
	tst_search_focus_position($('#sr_form').find('.search-field'));
	
	
	/** Leyka custom modal **/
	var leykaTopPad = (windowWidth > 940) ? 120 : 66;
	
	$('#leyka-agree-text').easyModal({		
		hasVariableWidth : true,
		top : leykaTopPad,
		updateZIndexOnOpen: false,
		//transitionIn: 'animated zoomIn',
		//transitionOut: 'animated zoomOut',
		//onClose : function(){  $('#leyka-agree-text').css('z-index', '0');},
		//onOpen : function() { $('#leyka-agree-text').css('z-index', '3007483647'); }
	});
	
	$('.leyka-custom-confirmation-trigger').on('click', function(e){
		e.preventDefault(); 
		
		$('#leyka-agree-text').trigger('openModal');				
	});
	
	$('.leyka-modal-close').on('click', function(e){
		
		$('#leyka-agree-text').trigger('closeModal');
	});
	
	
	/** Sticky elements **/
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($('body').hasClass('adminbar')) ? 62+32 + 280 : 62 + 280,
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 95 + 32 + 280 : 95 + 280;
		
	
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//no scroll when menu is open
		if ($site_header.hasClass('menu-open')) {
			$(window).scrollTop(position);			
			return;
		}		
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || tst_scroll_outOfBounds(scroll))
			return true;
		
		//stick header
		if (scroll < position) { //upword
			$site_header.removeClass('invisible').addClass('fixed-header');
		}
		else if(scroll >= scrollTopLimit) {
			$site_header.removeClass('fixed-header').addClass('invisible');
		}
		else {
			$site_header.removeClass('fixed-header').removeClass('invisible');
		}
		
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
