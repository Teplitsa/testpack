<?php
/** section related */

class TST_Current_Section {
	#code
	
	private static $_instance = null;
	protected $current_section = null;
	
	private function __construct() {
	
		
		//add section class to body
		add_action('wp', array($this, 'set_current_section'));
		add_filter('body_class', array($this, 'body_class'));
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
	
	public function set_current_section() {
		
		if(!$this->current_section) {
			$qo = get_queried_object();
			
			if(is_tax('section')) {
				$this->current_section = $qo;
			}
			elseif(is_singular('post') || is_tag()){
				$sec = get_term_by('slug', 'news', 'section');
				if($sec)
					$this->current_section = $sec;
			}
			elseif(is_singular('item') || is_page()) {
				$sec = get_the_terms($qo, 'section');
				if($sec){
					$this->current_section = $sec[0];
				}		
			}
			
		}
		
		
	}
	
	public function get_current_section() {
		
		return $this->current_section;
	}
	
	public function body_class($classes) {
		
		$sec = $this->get_current_section();
		if($sec)
			$classes[] = 'current-section-'.$sec->slug;
			
		return $classes;
	}

	
} //class

TST_Current_Section::get_instance();

/** add current section to menu **/
add_filter('wp_nav_menu_objects', 'tst_current_menu_item', 2, 3);
function tst_current_menu_item($items, $args){

	if(empty($items))
		return $items;
	
	if($args->theme_location != 'primary')
		return $items;
	
	//var_dump($args);
	$sec_ob = TST_Current_Section::get_instance();
	$sec = $sec_ob->get_current_section();
	
	if(!$sec)
		return $items;
	
	foreach($items as $index => $menu_item){
		if(false !== strpos($menu_item->url, $sec->slug)){
			$menu_item->classes[] = 'current-section';
		}
	}
	

	return $items;
}