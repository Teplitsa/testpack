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
	
	
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width(),
			windowHeight = $(window).height();
		
		resize_embed_media();
		
		
	});
	
}); //jQuery
