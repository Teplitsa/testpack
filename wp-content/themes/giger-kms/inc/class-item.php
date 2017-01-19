<?php
/** Class for Item functions **/

class TST_Item {

	public $post_object = null;
	protected $section = null;
	protected $item_on_side = 3;

	public function __construct($object) {

		if (($object instanceof WP_Post) && $object->post_type == 'item' ) {
			$this->post_object = $object;

		}
		else {
			throw new Exception("TST_Item can be created from post object only");
		}
	}


	public function __get( $key ) {

		if(!$this->post_object)
			return;

		if($this->post_object && property_exists($this->post_object , $key))
			return $this->post_object->$key;

		switch($key){

			case 'permalink':
				return get_permalink($this->post_object);
				break;
		}
	}

	protected function get_section() {

		if(!$this->section) {

			$sec = get_the_terms($this->ID, 'section');
			if(!empty($sec))
				$this->section = $sec[0];
		}

		return $this->section;
	}

	public function get_menu() {

		//to-do cache in post_meta

		$root = ($this->post_parent > 0) ? get_post($this->post_parent) : $this->post_object;
		$children = get_posts(array(
			'post_type' => 'item',
			'posts_per_post' => -1,
			'post_parent' => $root->ID,
			'orderby' => array('date' => 'DESC'),
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache ' => false,
			'no_found_rows' => true
		));

		$menu = array_merge(array($root), $children);
		if(count($menu) < 2 )
			return '';

		$list = array();
		foreach($menu as $i => $m) {
			$css = ($m->ID == $this->ID) ? 'menu-item current' : 'menu-current';
			$list[] = "<li class='".$css."'><a href='".get_permalink($m)."'>".get_the_title($m)."</a></li>";
		}

		$out = "<ul class='single-item-list'><li class='intro'><span>В этом разделе</span></li>".implode('', $list)."</ul>";


		return $out;
	}

	protected function get_side_items() {

		$section = $this->get_section();
		if(empty($section))
			return array();

		$items = array();
		if($this->post_name == 'have-test'){
			$items[] = get_page_by_title('Где сдать анализы', OBJECT, 'item');
			$items = array_merge(
				$items,
				get_posts(array(
					'post_type' => 'item',
					'posts_per_page' => $this->item_on_side - 1,
					'post__not_in' => array($this->ID),
					'post_parent' => 0,
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
				))
			);
		}
		else {
			$items = get_posts(array(
				'post_type' => 'item',
				'posts_per_page' => $this->item_on_side,
				'post__not_in' => array($this->ID),
				'post_parent' => 0,
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

		return $items;
	}

	public function get_sidebar() {

		$generate = get_post_meta($this->ID, 'has_sidebar', true);
		if($generate != 'on')
			return '';

		$side_items = $this->get_side_items();
		if(empty($side_items))
			return '';

		ob_start();
		foreach($side_items as $si) {
		?>
			<div class="widget widget--card"><?php tst_card($si, false);?></div>
		<?php
		}
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	public function get_root_title() {
		$root = ($this->post_parent > 0) ? get_post($this->post_parent) : $this->post_object;
		return $root->post_title;
	}

} //class
