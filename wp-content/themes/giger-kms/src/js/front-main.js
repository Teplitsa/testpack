/** Main scripts **/
/*
    jQuery Masked Input Plugin
    Copyright (c) 2007 - 2015 Josh Bush (digitalbush.com)
    Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
    Version: 1.4.1
*/
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});

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
			target = $("#"+trgt);


		if (target.length > 0) {
			var offset = target.offset().top;

			if(offset > 50)
				offset = offset - 50;

			$('html, body').animate({scrollTop:offset}, 900);
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
