<?php

class TST_Stories {
    
    private static $used_index = array();
    
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
	
	public static function get_story_unique_icon( $post_id, $gender, $unique_icons_count = 3 ) {
	    
	    if( !isset( self::$used_index[$gender] ) ) {
	        self::$used_index[$gender] = array();
	    }
	    
	    $icon = '';
	    
	    while( !$icon || ( isset( self::$used_index[$gender][$icon] ) && count( self::$used_index[$gender] ) < $unique_icons_count ) ) {
	        $icon_index = $post_id % $unique_icons_count;
	        $icon = ( $gender == 'male' ? 'pic-male-' : 'pic-female-' ) . ( $icon_index );
	        $post_id += 1;
        }
	    
	    self::$used_index[$gender][$icon] = 1;
	    
	    
	    return $icon;
	}
	
} //class