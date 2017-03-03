<?php
/**
 * Color Schemes for landings
 **/

class TST_Color_Schemes {

	private static $_instance = null;

	protected $supported_post_types = array('landing', 'page');
	protected $current_post = null;
	protected $current_scheme = array();


	private function __construct() {

		add_action('tst_site_body', array($this, 'get_scheme'));
	}


	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

	public function get_scheme() {

		if(empty($this->current_post))
			$this->current_post = (is_singular()) ? get_queried_object() : null;

		if(!$this->current_post || !$this->is_supported_post_type($this->current_post->post_type))
			return array();

		if(empty($this->current_scheme)){
			$this->current_scheme = get_post_meta($this->current_post->ID, '_tst_color_scheme', true);

			if(empty($this->current_scheme)){
				$this->current_scheme = $this->build_scheme();
			}
		}

		return $this->current_scheme;
	}


	//helpers
	public function is_supported_post_type($post_type) {

		if(!$post_type || !in_array($post_type, $this->supported_post_types))
			return false;

		return true;
	}

	public static function clear_scheme($post_id) {

		delete_post_meta($post_id, '_tst_color_scheme');
	}

	public function build_named_full_scheme( $post_id, $scheme_name, $sections, $force_rebuild = false ) {
	    $scheme = array();
	    
	    $scheme = get_post_meta( $post_id, 'color_scheme_' . $scheme_name, true);
	    if( !empty( $scheme ) && !$force_rebuild ) {
	        return $scheme;
	    }
	    
        $panels = $this->panels_config();
        $labels = $this->labels_config();

        foreach($sections as $section_name) {
            
            $scheme_code = 'color-1-panels__color-2-panels__color-3-panels__color-4-panels';
            $scheme_classes = $this->get_colors_classes($scheme_code);
            
            $scheme[$section_name] = $scheme_classes;
        }

        if( !empty( $scheme ) ) {
            update_post_meta( $post_id, 'color_scheme_' . $scheme_name, $scheme);
        }

        return $scheme;
	}
	
	public function get_named_full_scheme( $post_id, $scheme_name, $section_name ) {
	    
	    $scheme = get_post_meta( $post_id, 'color_scheme_' . $scheme_name, true);
	    
        return isset( $scheme[ $section_name ] ) ? $scheme[ $section_name ] : '';
        
	}
	
	//Build
	protected function build_scheme() {

		$scheme = array();
		if(!$this->current_post)
			return $scheme;

		//get all section
		$sections = get_post_meta($this->current_post->ID, '_wds_builder_template', true);

		//echo "<pre>";
		//print_r($sections);
		//echo "</pre>";

		//loop through section and generate colors for each
		if(!empty($sections)) {
			foreach($sections as $i => $sec){

				$sec_key = $i.'_'.$sec['template_group'];

				$field = str_replace('-', '_', $sec['template_group']);
				$field = $field.'_color_scheme';
				$scheme_code = (isset($sec[$field])) ? $sec[$field] : null;
				$scheme_classes = $this->get_colors_classes($scheme_code);

				$scheme[$sec_key] = $scheme_classes;

				//echo "<pre>";
				//var_dump($scheme_classes);
				//echo "</pre>";
			}
		}

		//add cta sections
		$cta_sections = get_post_meta($this->current_post->ID, '_wds_builder_cta_template', true);
		if(!empty($cta_sections)) {
			foreach($cta_sections as $i => $sec){

				$sec_key = $i.'_'.$sec['template_group'];
				if(!isset($scheme[$sec_key])) {
					$field = str_replace('-', '_', $sec['template_group']);
					$field = $field.'_color_scheme';
					$scheme_code = (isset($sec[$field])) ? $sec[$field] : null;
					$scheme_classes = $this->get_colors_classes($scheme_code);

					$scheme[$sec_key] = $scheme_classes;
				}

				//echo "<pre>";
				//var_dump($scheme_classes);
				//echo "</pre>";
			}
		}

		//store results
		if(!empty($scheme)){
			update_post_meta($this->current_post->ID, '_tst_color_scheme', $scheme);
		}


		return $scheme;
	}


	protected function get_colors_classes($code = null) {

		if(!$code)
			return '';

		$keys = array_map('trim', explode('__', $code));
		if(empty($keys))
			return '';

		$classes = array();

		$panels =  $this->panels_config();
		$labels = $this->labels_config();
		$donation = $this->donation_config();

		foreach($keys as $color_key){
			if(false !== strpos($color_key, 'panels')){
				$r_key = array_rand($panels);
				$classes[] = 'scheme-'.str_replace('panels', $panels[$r_key], $color_key);
				unset($panels[$r_key]);
			}
			elseif(false !== strpos($color_key, 'donation')) {
				$r_key = array_rand($donation);
				$classes[] = 'scheme-'.str_replace('donation', $donation[$r_key], $color_key);
				unset($labels[$r_key]);
			}
			else {
				$r_key = array_rand($labels);
				$classes[] = 'scheme-'.str_replace('labels', $labels[$r_key], $color_key);
				unset($labels[$r_key]);
			}
		}

		return implode(' ', $classes);
	}

	protected function panels_config() {

		return array(
			'wetsoil',
			'moss',
			'bark',
			'loam',
			'grass',
			'leaf'
		);
	}

	protected function labels_config() {

		return array(
			'white',
			'ground',
			'moss'
		);
	}

	protected function donation_config() {

		return array(
			'moss',
			'loam',
			'grass',
			'leaf',
			'pollen'
		);
	}


} //class


//init
TST_Color_Schemes::get_instance();


/** Helpers **/
//get color scheme classes for section on landing
function tst_get_colors_for_section(){

	$colors = TST_Color_Schemes::get_instance();
	$scheme = $colors->get_scheme();

	if(empty($scheme))
		return '';


	$slug = wds_page_builder()->functions->get_part();
	$index = wds_page_builder()->functions->get_parts_index();

	$sec_key = $index.'_'.$slug;
//var_dump($scheme);
//var_dump($sec_key);
	if(isset($scheme[$sec_key]))
		return  $scheme[$sec_key];

	return '';
}

//get color scheme classes for news section by landing ID
function tst_get_colors_for_news($landing_id){

	$scheme = get_post_meta($landing_id, '_tst_color_scheme', true);
	$colors = '';

	if(empty($scheme))
		return $colors;

	foreach($scheme as $key => $classes){
		if(false !== strpos($key, 'news')){
			$colors = $classes;
			break;
		}
	}

	return $classes;
}

//get color scheme classes for help section by landing ID
function tst_get_colors_for_help($landing_id){

	$scheme = get_post_meta($landing_id, '_tst_color_scheme', true);
	$colors = '';

	if(empty($scheme))
		return $colors;

	foreach($scheme as $key => $classes){
		if(false !== strpos($key, 'help')){
			$colors = $classes;
			break;
		}
	}

	return $classes;
}