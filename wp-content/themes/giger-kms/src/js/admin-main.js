jQuery(function($){
	
	tst_sort_choose_template_control();
	
	function tst_sort_choose_template_control() {
		var options = $('#_wds_builder_template_0_template_group option');
		
		if( !options.length ) {
			return;
		}
		
		var sorted_options = options.slice();
		sorted_options.sort( function( $opt1, $opt2 ){
			return $opt1.text > $opt2.text ? 1 : $opt1.text < $opt2.text ? -1 : 0;
		});
		
		$('.wds-simple-page-builder-template-select').each( function() {
			var $select = $(this);
			var val = $select.val();
			
			$select.empty();
			
			sorted_options.each( function(){
				$select.append( $(this).clone() );
			});
			
			$select.val( val );
		});
	}

});
