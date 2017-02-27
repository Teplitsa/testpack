<?php
/**
 * Color Schemes for landings
 **/

class TST_Color_Schemes {

	private static $_instance = null;

	protected $supported_post_types = array('landing');
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

		//get colors data
		if(empty($sections))
			return $scheme;

		$panels = $this->panels_config();
		$labels = $this->labels_config();

		//loop through section and generate colors for each
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

		foreach($keys as $color_key){
			if(false !== strpos($color_key, 'panels')){
				$r_key = array_rand($panels);
				$classes[] = 'scheme-'.str_replace('panels', $panels[$r_key], $color_key);
				unset($panels[$r_key]);
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


} //class


//init
TST_Color_Schemes::get_instance();