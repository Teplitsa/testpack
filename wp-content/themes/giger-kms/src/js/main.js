jQuery(document).ready(function($){
  function isVisible(tag) {
      var t = $(tag);
      var w = $(window);
      var wt = w.scrollTop();
      var tt = t.offset().top;
      var tb = tt + t.height();
      return ((tb <= wt + w.height()) && (tt >= wt));
  }

  $(function () {
      $(window).scroll(function () {
          var b = $(".col3-section .col3");
          var c = $(".city-block-info");
          var d = $(".nf_img_back");
          var e = $(".ftRow .tpl-related-post, .sdRow .tpl-related-post, .thRow .tpl-related-post");
          
          if (!b.prop("shown") && isVisible(b)) {
              b.prop("shown", true);
              b.css('visibility', 'visible');
              b.addClass("animated fadeInUp");
          }
          
          if (!c.prop("shown") && isVisible(c)) {
              c.prop("shown", true);
              c.css('visibility', 'visible');
              c.addClass("animated fadeInRight");
          }
          if (!d.prop("shown") && isVisible(d)) {
              d.prop("shown", true);
              d.css('visibility', 'visible');
              d.addClass("animated fadeInLeft");
          }
          if (!e.prop("shown") && isVisible(e)) {
            e.prop("shown", true);
            e.css('visibility', 'visible');
            // alert("visible");
            e.addClass("animated fadeInUp");
          }
      });
  });

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
	
	/** Newsletter **/
	$('#trigger_newsletter').on('click', function(e){
				
		if ($('body').hasClass('slug-subscribe')){			
			return false;
		}
				
		var winW = $('#top').width();
				
		if (winW > breakPointMedium && !$site_header.hasClass('newsletter-open')) {
			e.preventDefault();
			e.stopPropagation();
			
			$site_header.find('#newsletter_panel').slideDown(150, function(){				
				$site_header.addClass('newsletter-open').find('.rdc-textfield__input').focus();
				$(this).removeAttr('style');
			});			
		}
		else if($site_header.hasClass('newsletter-open')) {
			e.preventDefault();
			e.stopPropagation();
			
			$site_header.find('#newsletter_panel').slideUp(150, function(){				
				$site_header.removeClass('newsletter-open');
				$(this).removeAttr('style');
			});
		}
	});
	
	//no validate no autocomplete
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
	//open panel after submit
	if (!$('body').hasClass('slug-subscribe') && $site_header.find('#newsletter_panel').find('.frm_message, .frm_error_style').length > 0) {
		$site_header.addClass('newsletter-open');
	}
	
	
	
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
	function rdc_search_focus_position(SearchInput) {
		if (SearchInput.length > 0) {
			var strLength= SearchInput.val().length * 2;
		
			SearchInput.focus();
			SearchInput[0].setSelectionRange(strLength, strLength); //this put cursor in last position
		}
	}
	
	rdc_search_focus_position($('#sr_form').find('.search-field'));
	
	
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
		if((Math.abs(scroll-position) < 3) || rdc_scroll_outOfBounds(scroll))
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
	
	
	/** == Responsive media == **/
    var resize_embed_media = function(){

        $('iframe, embed, object').each(function(){

            var $iframe = $(this),
                $parent = $iframe.parent(),
                do_resize = false;
				
            if($parent.hasClass('embed-content')){
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
	
	$('#leyka-agree-text').easyModal({		
		hasVariableWidth : true,
		top : leykaTopPad,
		//transitionIn: 'animated zoomIn',
		//transitionOut: 'animated zoomOut',
		onClose : function(){  }
	});
	
	$('body').on('click','.leyka-custom-confirmation-trigger', function(e){

		$('#leyka-agree-text').trigger('openModal');			
		e.preventDefault();
	});
	
	$('body').on('click', '.leyka-modal-close', function(e){
		
		$('#leyka-agree-text').trigger('closeModal');
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
	
	/** Store **/
	$(document).on('click', '.tpl-storeitem', function(e){
		
		var $_item = $(this),
			$_target_data = $_item.parents('.panel-widget-style').attr('class'),
			$_target_raw = $_target_data.split(' ');
			scroll_target = $('.frm_form_widget').eq(0).offset();
			
		if ($_target_raw[0] && $_target_raw[0].length > 0) {
			//find and check checkbox
			$('#'+ $_target_raw[0]).prop( "checked", true );
			
			//scroll
			$('html, body').animate({scrollTop:scroll_target.top - 50}, 900);
		}
		console.log($_target_raw[0]);
	});
	
}); //jQuery
/*
    Version 1.3.2
    The MIT License (MIT)

    Copyright (c) 2014 Dirk Groenen

    Permission is hereby granted, free of charge, to any person obtaining a copy of
    this software and associated documentation files (the "Software"), to deal in
    the Software without restriction, including without limitation the rights to
    use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
    the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.
*/

!function(t){"use strict";var s=function(s,e){this.el=t(s),this.options=t.extend({},t.fn.typed.defaults,e),this.isInput=this.el.is("input"),this.attr=this.options.attr,this.showCursor=this.isInput?!1:this.options.showCursor,this.elContent=this.attr?this.el.attr(this.attr):this.el.text(),this.contentType=this.options.contentType,this.typeSpeed=this.options.typeSpeed,this.startDelay=this.options.startDelay,this.backSpeed=this.options.backSpeed,this.backDelay=this.options.backDelay,this.stringsElement=this.options.stringsElement,this.strings=this.options.strings,this.strPos=0,this.arrayPos=0,this.stopNum=0,this.loop=this.options.loop,this.loopCount=this.options.loopCount,this.curLoop=0,this.stop=!1,this.cursorChar=this.options.cursorChar,this.shuffle=this.options.shuffle,this.sequence=[],this.build()};s.prototype={constructor:s,init:function(){var t=this;t.timeout=setTimeout(function(){for(var s=0;s<t.strings.length;++s)t.sequence[s]=s;t.shuffle&&(t.sequence=t.shuffleArray(t.sequence)),t.typewrite(t.strings[t.sequence[t.arrayPos]],t.strPos)},t.startDelay)},build:function(){var s=this;if(this.showCursor===!0&&(this.cursor=t('<span class="typed-cursor">'+this.cursorChar+"</span>"),this.el.after(this.cursor)),this.stringsElement){this.strings=[],this.stringsElement.hide(),console.log(this.stringsElement.children());var e=this.stringsElement.children();t.each(e,function(e,i){s.strings.push(t(i).html())})}this.init()},typewrite:function(t,s){if(this.stop!==!0){var e=Math.round(70*Math.random())+this.typeSpeed,i=this;i.timeout=setTimeout(function(){var e=0,r=t.substr(s);if("^"===r.charAt(0)){var o=1;/^\^\d+/.test(r)&&(r=/\d+/.exec(r)[0],o+=r.length,e=parseInt(r)),t=t.substring(0,s)+t.substring(s+o)}if("html"===i.contentType){var n=t.substr(s).charAt(0);if("<"===n||"&"===n){var a="",h="";for(h="<"===n?">":";";t.substr(s+1).charAt(0)!==h&&(a+=t.substr(s).charAt(0),s++,!(s+1>t.length)););s++,a+=h}}i.timeout=setTimeout(function(){if(s===t.length){if(i.options.onStringTyped(i.arrayPos),i.arrayPos===i.strings.length-1&&(i.options.callback(),i.curLoop++,i.loop===!1||i.curLoop===i.loopCount))return;i.timeout=setTimeout(function(){i.backspace(t,s)},i.backDelay)}else{0===s&&i.options.preStringTyped(i.arrayPos);var e=t.substr(0,s+1);i.attr?i.el.attr(i.attr,e):i.isInput?i.el.val(e):"html"===i.contentType?i.el.html(e):i.el.text(e),s++,i.typewrite(t,s)}},e)},e)}},backspace:function(t,s){if(this.stop!==!0){var e=Math.round(70*Math.random())+this.backSpeed,i=this;i.timeout=setTimeout(function(){if("html"===i.contentType&&">"===t.substr(s).charAt(0)){for(var e="";"<"!==t.substr(s-1).charAt(0)&&(e-=t.substr(s).charAt(0),s--,!(0>s)););s--,e+="<"}var r=t.substr(0,s);i.attr?i.el.attr(i.attr,r):i.isInput?i.el.val(r):"html"===i.contentType?i.el.html(r):i.el.text(r),s>i.stopNum?(s--,i.backspace(t,s)):s<=i.stopNum&&(i.arrayPos++,i.arrayPos===i.strings.length?(i.arrayPos=0,i.shuffle&&(i.sequence=i.shuffleArray(i.sequence)),i.init()):i.typewrite(i.strings[i.sequence[i.arrayPos]],s))},e)}},shuffleArray:function(t){var s,e,i=t.length;if(i)for(;--i;)e=Math.floor(Math.random()*(i+1)),s=t[e],t[e]=t[i],t[i]=s;return t},reset:function(){var t=this;clearInterval(t.timeout);this.el.attr("id");this.el.empty(),"undefined"!=typeof this.cursor&&this.cursor.remove(),this.strPos=0,this.arrayPos=0,this.curLoop=0,this.options.resetCallback()}},t.fn.typed=function(e){return this.each(function(){var i=t(this),r=i.data("typed"),o="object"==typeof e&&e;r&&r.reset(),i.data("typed",r=new s(this,o)),"string"==typeof e&&r[e]()})},t.fn.typed.defaults={strings:["These are the default values...","You know what you should do?","Use your own!","Have a great day!"],stringsElement:null,typeSpeed:0,startDelay:0,backSpeed:0,shuffle:!1,backDelay:500,loop:!1,loopCount:!1,showCursor:!0,cursorChar:"|",attr:null,contentType:"html",callback:function(){},preStringTyped:function(){},onStringTyped:function(){},resetCallback:function(){}}}(window.jQuery);

jQuery(document).ready(function($){
    $(".typed_text").typed({
      strings: ["ВИЧ не забирает твои мечты.", "Каждый имеет право на жизнь."],
      typeSpeed: 100, // typing speed
      backDelay: 1000, // pause before backspacing
      startDelay: 2000
    });
});