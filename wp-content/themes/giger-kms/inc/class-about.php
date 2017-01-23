<?php
/** Class to handle About section
 **/

class TST_About {

	public $post_object = null;
	protected $section = null;
	protected $item_on_side = 3;

	public function __construct($object) {

		if (($object instanceof WP_Post) && $object->post_type == 'page' ) {
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

	protected function get_side_items() {

		$items = get_posts(array(
			'post_type' => 'page',
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache ' => false,
			'post__not_in' => array($this->ID),
			'orderby' => array('menu_order' => 'DESC'),
			'tax_query' => array(
				array(
					'taxonomy' => 'section',
					'field' => 'slug',
					'terms' => 'about'
				)
			)
		));

		/*$events = get_posts(array(
			'post_type' => 'item',
			'posts_per_page' => 1,
			'no_found_rows' => true,
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache ' => false,
			'title' => 'Итоги мероприятий'
		));

		if($events)
			$items[] = $events[0];

		$books = get_posts(array(
			'post_type' => 'item',
			'posts_per_page' => 1,
			'no_found_rows' => true,
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache ' => false,
			'title' => 'Книги и брошюры'
		));

		if($books)
			$items[] = $books[0];*/

		return $items;
	}

	public function get_sidebar() {

		$side_items = $this->get_side_items();

		ob_start();

		$count = 1;
		if(!empty($side_items)) { foreach($side_items as $i => $si) {
			$css = '';
			if($count > 3)
				$count = 1;

			switch($count){
				case 1:
					$css .= 'scheme-one';
					break;

				case 2:
					$css .= 'scheme-five';
					break;

				case 3:
					$css .= 'scheme-four';
					break;
			}

			$count++;
		?>
			<div class="widget widget--card <?php echo $css;?>"><?php tst_card($si, true);?></div>
		<?php }} ?>
			<div class="widget widget--card scheme-three"><?php tst_news_card();?></div>
		<?php

		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	public function get_projects_content() {

		$projects = get_posts(array(
			'post_type' => 'project',
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache ' => false,
			'orderby' => array('date' => 'DESC')
		));

		if(empty($projects))
			return '';

		ob_start();

		foreach($projects as $pr) {

		?>
			<div class="layout-section__item layout-section__item--card ">
			<?php tst_project_cell($pr); ?>
			</div>
		<?php
		}

		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}


} //class