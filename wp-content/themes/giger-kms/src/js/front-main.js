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
	FontFaceOnload("Tavolga Free", {
		success: function() {
			$('html').addClass('tavolga');
		},
		timeout: 5000
	});
	FontFaceOnload("BloggerSans-Bold", {
		success: function() {
			$('html').addClass('bloggersans-bold');
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

jQuery(document).ready(function($){
	
	$('#tstl_phone').mask("+7(999) 999-9999");

	$('.tstl-join-group').on('focus', '#tstl_phone', function(e){
		$('.tstl-join-group').find('.field__error').removeClass('show');
	});

	$('.tstl-join-group').on('submit', function(e){

		var $_form = $(this),
			$_input = $('#tstl_phone').val();

			$_form.find('.field__error').removeClass('show');

			//validate
			var reg = /^\+?7?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
			if(!$_input.match(reg)){
				$_form.find('.field__error').addClass('show');
			}
			else {
				//submit

				$.ajax({
					type : "post",
					dataType : "json",
					url : frontend.ajaxurl,
					data : {
						'action'	: 'tst_join_whatsapp_group',
						'nonce' 	: $_form.find('#_tstl_nonce').val(),
						'phone'  	: $_input
					},
					beforeSend : function () {
						$_form.addClass('loading');
						$('#form-response').empty();
					},
					success: function(response) {
						$_form.removeClass('loading');
						$(response.msg).appendTo('#form-response');

						if(response.type == 'ok') {
							$_form.hide();
						}

					}
				});
			}


			//stop
			e.preventDefault();
	});
	
}); //jQuery


jQuery(document).ready(function($){
	
	/** == Load more button == **/
	var loadingPosts = false;

	$(document).on('click', '.load-more-btn', function(e){

		e.preventDefault();
		var target 		= $(e.target),
			nav 		= target.parents('.load-more'),
			loadTarget	= target.attr('data-target'),
			page 		= target.attr('data-pagenum');

		$.ajax({
			type : "post",
			dataType : "json",
			url : frontend.ajaxurl,
			data : {
				'action'	: 'load_more_posts',
				'nonce' 	: target.attr('data-nonce'),
				'page'  	: target.attr('data-pagenum'),
				'query' 	: target.attr('data-query'),
				'template'	: target.attr('data-template')
			},
			beforeSend : function () {
				nav.addClass('loading');
				loadingPosts = true;
			},
			success: function(response) {

				if (response.type == 'ok') {
					$(response.data).appendTo('#'+loadTarget);

					$('#'+loadTarget).addClass('loadmore-has-results');
					nav.removeClass('loading');

					if (!response.has_more) {
						nav.find('.load-more-btn').remove();
					}
					else {
						page++;
						nav.find('.load-more-btn').attr('data-pagenum', page);
					}

					loadingPosts = false;
				}
			}
		});
	});

	//loadmore on scroll
	$(window).scroll(function() {

		var $load_more = $('.load-more-btn').last();

		if($load_more.length > 0 ) {
			var $_load_offset = $load_more.offset().top,
				$_window_height =  $(window).height(),
				$_widow_scroll = $(this).scrollTop();

			if(($_window_height + $_widow_scroll + 900) > $_load_offset ) {
				if(!loadingPosts) {
					console.log('start loading ' + $_widow_scroll);
					$load_more.trigger("click");
				}
			}
		}
	});
	
}); //jQuery

