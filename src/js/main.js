/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width();
	
	/** Fade In **/
	function is_touch_device() {
		return 'ontouchstart' in window        // works on most browsers 
			|| navigator.maxTouchPoints;       // works on IE10/11 and Surface
	};
	
	
	if(windowWidth > 600 && !is_touch_device()) {
				
		$('.vp-el').addClass('op-hidden').viewportChecker({
			classToAdd: 'op-visible animated fadeIn',
			classToRemove: 'op-hidden',
			offset: 100
		});
		
		
		$('.mdl-layout__content').scroll(function() {
	
			$('.vp-el').viewportChecker({
				classToAdd: 'op-visible animated fadeIn',
				classToRemove: 'op-hidden',
				offset: 100
			});	
		});
	}
	
	
}); //jQuery