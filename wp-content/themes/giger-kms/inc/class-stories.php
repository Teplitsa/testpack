<?php

class TST_Stories {

	public static function get_rotated( $limit = 3 ) {
		
		$items = get_posts(array(
			'post_type' => 'story',
			'posts_per_page' => $limit,
			'no_found_rows' => true,
		    'cache_results' => false,
		    'update_post_meta_cache' => false,
		    'update_post_term_cache ' => false,
		    'orderby' => 'rand'
		));
			
		return $items;
	}
	
} //class