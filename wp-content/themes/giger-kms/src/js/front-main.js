/* Scripts */
jQuery(document).ready(function($){
	
    /** Window width **/
	var windowWidth = $('#top').width(),		
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 1024; //full screen break point
	
	/** Font Load events **/
	FontFaceOnload("Roboto", {
		success: function() {
			$('html').addClass('roboto');
		},
		timeout: 5000
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
    
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width(),
			windowHeight = $(window).height();
		
			resize_embed_media();
			if ($('body').hasClass('drawer-open') && winW > breakPointMedium) {
				$('body').removeClass('drawer-open');
				$('#trigger_menu').removeClass('is-active');
			}
		
	});
	
	/** Scroll event **/
	var position = $(window).scrollTop();
	$(window).scroll(function(){
		//to do no scroll when menu opened
		
	});
	
	/** Main menu **/
	$('#trigger_menu').on('click', function(e){
		
		e.preventDefault();
		var trigger = $(this);
		
		if ($('body').hasClass('drawer-open')) {
			$('body').removeClass('drawer-open');
			trigger.removeClass('is-active');
		}
		else {
			$('body').addClass('drawer-open');
			trigger.addClass('is-active');
		}
			
	});
	
	/* Scroll */
	$('.local-scroll').on('click', function(e){
		e.preventDefault();

		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();


		if (target.top) {
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}

	});
	
}); //jQuery
