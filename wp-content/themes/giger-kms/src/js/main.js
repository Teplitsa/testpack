/* Scripts */
jQuery(document).ready(function($){
			
    /** Window width **/
	var windowWidth = $('#top').width(),		
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 1024; //full screen break point
	
		
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
	
	
	/** scroll **/
	$('.local-scroll, .menu-scroll a').on('click', function(e){
		e.preventDefault();
		
		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();
					
		if (target.top) {			
			$('html, body').animate({scrollTop:target.top - 120}, 900);
		}
		
	});
	
	
	
	$('.social-menu a').each(function(){
	    $(this).attr("target", "_blank");
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

    $('body').on('click','.leyka-custom-confirmation-trigger', function(e){

        $('#leyka-agree-text').trigger('openModal');
        e.preventDefault();
    });

    $('body').on('click', '.leyka-modal-close', function(e){
        $('#leyka-agree-text').trigger('closeModal');
        $('.lean-overlay').css('z-index', '1000');
    });
	
	//no validate 
	$('.novalidate').attr('novalidate', 'novalidate');
	
	
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
	
	/* volunteers filed in focus **/
	var volunteerForm = $('#form_volunteers');
		
	if (volunteerForm.length > 0 ) {
		var volunteerFormField = volunteerForm.find('input.tst-textfield__input').eq(0),
			scrollPosition = $(window).scrollTop();
		
		$(window).scroll(function () {
			var scroll = $(window).scrollTop(),
				fieldPos = volunteerFormField.offset().top;
				
			if (scroll > scrollPosition && (fieldPos - scroll) < 250 && windowWidth > 767) { //down
				if (!volunteerForm.hasClass('seen')) {
					volunteerFormField.focus();
					volunteerForm.addClass('seen');
				}				
			}
			
			scrollPosition = scroll; //upd scroll position
			return true;
		});
	}
	
	/* form on small horizontal screens **/
		
	$('input').on('focus', function(e){
		var windowHeight = $(window).height();
		
		if (windowHeight < 400 && !$site_header.hasClass('invisible')) {
			$site_header.addClass('invisible');
		}
	});
	
	$('input').on('blur', function(e){
		var windowHeight = $(window).height();
		
		if ($site_header.hasClass('invisible')) {
			$site_header.removeClass('invisible');
		}
	});
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width(),
			windowHeight = $(window).height(),
			someFocus = $('input:focus');
		
		resize_embed_media();
		
		if (windowHeight < 400 && someFocus.length > 0 && !$site_header.hasClass('invisible')) {
			$site_header.addClass('invisible');
		}
		else {
			$site_header.removeClass('invisible');
		}
	});
	
}); //jQuery
