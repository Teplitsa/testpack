<?php
/**
 * Request corrections
 * 
 **/

/* CPT Filters */
add_action('parse_query', 'tst_request_corrected');
function tst_request_corrected(WP_Query $query) {

	if(is_admin()) {
		return;
	}

	if(!$query->is_main_query()) {
		return;
	}

	//if(is_search()){
	//	
	//	$per = get_option('posts_per_page');
	//	if($per < 25)
	//		$query->set('posts_per_page', 25);		
	//}
	
}






