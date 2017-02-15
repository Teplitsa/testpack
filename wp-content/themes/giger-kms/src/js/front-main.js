/* Scripts */
jQuery(document).ready(function($){

    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767, //medium screen break point
		breakPointLarge  = 1024; //full screen break point


	/** Font Load events **/
	FontFaceOnload( "BwSurco", {
		weight: 400,
		success: function() {
			$('html').addClass('bwsurco');
		},
		timeout: 5000
	});

	FontFaceOnload( "BwSurco", {
		weight: 700,
		success: function() {
			$('html').addClass('bwsurco-b');
		},
		timeout: 5000
	});

	FontFaceOnload( "BwSurco", {
		weight: 300,
		success: function() {
			$('html').addClass('bwsurco-l');
		},
		timeout: 5000
	});

	FontFaceOnload( "BwSurco", {
		style: 'italic',
		success: function() {
			$('html').addClass('bwsurco-i');
		},
		timeout: 5000
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


	/* Search form */
	$('.searchform__field')
		.on('focus', function(){
			$(this).parents('form').addClass('focus');
		})
		.on('blur', function(){
			$(this).parents('form').removeClass('focus');
		});

	$('.search-box__trigger').on('click', function(){

		var $_box = $(this).parents('.search-box');

		if($_box.hasClass('open')) {
			$_box.removeClass('open');
		}
		else {
			$_box.addClass('open');
		}
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
                    // console.log('start loading ' + $_widow_scroll);
                    $load_more.trigger("click");
                }
            }
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
