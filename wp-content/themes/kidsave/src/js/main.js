/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width();
	
	/** Newsletter **/
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
	var nlEmail = $('.nl-form').find('.kds-textfield__input');
	
	nlEmail
	.on('focus', function(e){
		$(this).parents('fieldset').addClass('focus');
	})
	.on('blur', function(e){
		$(this).parents('fieldset').removeClass('focus');
	});
	
	
	
}); //jQuery
