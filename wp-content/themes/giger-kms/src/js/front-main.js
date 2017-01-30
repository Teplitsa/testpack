/* Scripts */
jQuery(document).ready(function($){

    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 1024; //full screen break point

	/** Font Load events **/
	FontFaceOnload( "Roboto", {
		weight: 400,
		success: function() {
			$('html').addClass('roboto');
		},
		timeout: 5000
	});

	FontFaceOnload("Tavolga", {
		weight: 400,
		success: function() {
			$('html').addClass('tavolga');
		},
		timeout: 5000
	});

	FontFaceOnload("BloggerSans", {
		weight: 600,
		success: function() {
			$('html').addClass('bloggersans');
		},
		timeout: 5000
	});


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

	/** Local scroll **/
	$('.local-scroll').on('click', function(e){ 
		e.preventDefault();

		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();


		if (target.top) {
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}

	});

	/** Focus on search **/
	$('.searchform__field')
	.on('focus', function(){
		$(this).parents('form').addClass('focus');

	})
	.on('blur', function(){
		$(this).parents('form').removeClass('focus');
	});


	/** to test **/
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

                $parent = $iframe.parents('.entry-content, .player, .single-body--entry');
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




	/** Scroll event **/
	var position = $(window).scrollTop(), //store intitial scroll position
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 145 + 40 + 32 : 145 + 40;

	$(window).scroll(function(){

		var scroll = $(window).scrollTop(),
			winW = $('#top').width();


		if(scroll > 200) {
			if(!$('body').hasClass('sharing-fixed')){
				$('body').addClass('sharing-fixed');
			}
		}
		else if($('body').hasClass('sharing-fixed')) {
			$('body').removeClass('sharing-fixed');
		}

		//fixed
		if (winW  > breakPointMedium) { //large screens

			if($('#outer_sharing').length > 0)	{
				stickInParent('#outer_sharing', '.sharing-frame', position, fixedTopPosition);
			}

		}
		else {
			//#single_sidebar - removed
			$('#outer_sharing').removeClass('fixed-bottom').removeClass('fixed-top');
		}

		position = scroll; //upd scroll position
		return true;
	});

	//stick element on scroll
	function stickInParent(el, el_parent, el_position, el_fixedTopPosition) {
		var $_el = $(el),
			$_el_parent = $(el_parent);


		if (!($_el.length > 0 && $_el_parent.length > 0)) {
			return; //no elements
		}

		//calcs
		var scroll = $(window).scrollTop(),
			topPos = $_el_parent.offset().top,
			height = $_el_parent.height() ;



		if ($_el.outerHeight() >= height) {
			$_el.removeClass('fixed-bottom').removeClass('fixed-top');
			return;
		}

		if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //stick on bottom
			if (scroll > el_position) //scroll down
				$_el.addClass('fixed-bottom').removeClass('fixed-top');
		}
		else if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //unstick on bottom
			if (scroll < el_position)
				$_el.removeClass('fixed-bottom').addClass('fixed-top');
		}
		else if (scroll > el_fixedTopPosition - topPos) { //stick on top
			$_el.removeClass('fixed-bottom').addClass('fixed-top');
		}
		else {
			$_el.removeClass('fixed-bottom').removeClass('fixed-top'); //normal position
		}
	}

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
    // mobile menu show/hide
    $(document).on('click','.inner-menu',function(){
        if($(window).width() < 768){
            var elem = $(this);
            if( elem.is('.active')){
                $('.single-item-list').slideUp(150);
                elem.removeClass('active');
            } else {
                elem.addClass('active');
                $('.single-item-list').slideDown(150);
            }
        }
    });
    $(window).resize(function(){
        var winwidth = $(window).width();
        if(winwidth > 768){
            $('.inner-menu').removeClass('active');
            $('.single-item-list').show();
        } else {
            $('.single-item-list').hide();
        }
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
						nav.remove();
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
