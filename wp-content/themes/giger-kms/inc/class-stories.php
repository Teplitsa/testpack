<?php

class TST_Stories {
    
    protected static $item_on_side = 4;
    
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
	
	protected static function get_side_items() {
	    $section = get_term_by( 'slug', 'advices', 'section' );
	    
	    if( $section ) {
            $items = get_posts(array(
                'post_type' => 'item',
                'post_parent' => 0,
                'posts_per_page' => self::$item_on_side - 1,
                'no_found_rows' => true,
                'cache_results' => false,
                'update_post_meta_cache' => false,
                'update_post_term_cache ' => false,
                'orderby' => 'rand',
                'tax_query' => array(
                    array(
                        'taxonomy'	=> 'section',
                        'field' 	=> 'term_id',
                        'terms'		=> $section->term_id
                    )
                )
            ));
	    }
	    else {
	        $items = array();
	    }
        
        return $items;
	}
	
	public static function get_all_stories_sidebar() {
	    
        $side_items = self::get_side_items();
    
        ob_start();
        ?>
    			<div class="widget widget--card scheme-two"><?php TST_Item::get_enter_card();?></div>
    
		<?php
			if(!empty($side_items)) { foreach($side_items as $i => $si) {

			$css = '';
			switch($i){
				case 0:
					$css = 'scheme-four';
					break;

				case 1:
					$css = 'scheme-three';
					break;

				case 2:
					$css = 'scheme-five';
					break;
			}
		?>
			<div class="widget widget--card <?php echo $css; ?>"><?php tst_card($si, true);?></div>
		<?php
		}}
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
	
} //class