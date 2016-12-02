<?php
/**
 * Create main menus
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;


	//Top main menu
	$menu_name = 'Главное меню - Верх';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
	$sections = array('ngonews', 'regions', 'topics', 'events', 'opinions', 'articles', 'series', 'ngolife', 'photos', 'video');

	foreach($sections as $s) {
		$sec = get_term_by('slug', $s, 'section');
		if($sec){
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-object-id' => $sec->term_id,
				'menu-item-object' => $sec->taxonomy,
				'menu-item-parent-id' => 0,
				'menu-item-position' => 0,
				'menu-item-type' => 'taxonomy',
				//'menu-item-title' => '',
				//'menu-item-url' => '',
				//'menu-item-description' => '',
				//'menu-item-attr-title' => '',
				//'menu-item-target' => '',
				'menu-item-classes' => 'section-'.$sec->slug,
				//'menu-item-xfn' => '',
				'menu-item-status' => 'publish',
			));
		}
	}

	//assign to location
    $locations = get_theme_mod('nav_menu_locations');
    $locations['primary_top'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations );

    unset($locations);
	unset($menu_name);
	unset($menu_id);

	echo 'Top menu created. Time in sec: '. (microtime(true) - $time_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10);


	//Sections main menu
	$sec_start = microtime(true);
	$menu_name = 'Главное меню - Секции';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
	$sections = array(
		'ngonews' => array(),
		'regions' => array(),
		'topics'  => array(
					'category' => array(
						'blagotvoritel-nost-i-dobrovol-chestvo',
						'gorod',
						'granty-i-konkursy',
						'zdorov-e',
						'korporativnaya-otvetstvennost',
						'kul-tura-i-prosveshhenie',
						'invalidy',
						'nekommercheskij-sektor',
						'obrazovanie',
						'okruzhayushhaya-sreda',
						'prava-cheloveka',
						'sem-ya-i-deti',
						'social-entrepreneur',
						'starshee-pokolenie'
					)),
		'events'   => array(),
		'opinions' => array(
					'category' => array(
						'directors',
						'interview'
					)),
		'articles' => array(),
		'series'   => array(
					'category' => array(
						'speaking',
						'ngocity',
						'yearlead',
						'socentr',
						'soblog'
					)),
		'ngolife'	=> array() ,
		'photos'	=> array() ,
		'video'		=> array() ,
		'agency' 	=> array(
					'section' => array(
						'agency'
					),
					'page' => array(
						'service',
						'advertising',
						'team',
						'contacts'
					),
					'project_cat' => array(
						'projects',
						'publications',
						'reports'
					),
					'category' => array(
						'events-asi'
					))
	);

	foreach($sections as $s => $s_sub) { //add top level items and store their IDs
		$sec = get_term_by('slug', $s, 'section');

		if($sec){
			$i = wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-object-id' => $sec->term_id,
				'menu-item-object' => $sec->taxonomy,
				'menu-item-parent-id' => 0,
				'menu-item-position' => 0,
				'menu-item-type' => 'taxonomy',
				//'menu-item-title' => '',
				//'menu-item-url' => '',
				//'menu-item-description' => '',
				//'menu-item-attr-title' => '',
				//'menu-item-target' => '',
				'menu-item-classes' => 'section-'.$sec->slug,
				//'menu-item-xfn' => '',
				'menu-item-status' => 'publish',
			));

			if(!is_wp_error($i)) {
				//add regions
				if($s == 'regions'){

					$regs = tst_get_items_for_regions_menu();

					if(!is_wp_error($regs)){ foreach($regs as $reg) {
						if($reg->name == 'Москва' || $reg->name == 'Санкт-Петербург'){
							$child = get_terms(array('taxonomy' => 'regions', 'number' => 1, 'parent' => $reg->term_id));
							if($child)
								$reg = $child[0];
						}

						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-object-id' 	=> $reg->term_id,
							'menu-item-object' 		=> $reg->taxonomy,
							'menu-item-parent-id' 	=> $i,
							'menu-item-position' 	=> 0,
							'menu-item-type' 		=> 'taxonomy',
							//'menu-item-title' => '',
							//'menu-item-url' => '',
							//'menu-item-description' => '',
							//'menu-item-attr-title' => '',
							//'menu-item-target' => '',
							'menu-item-classes' => 'region-'.$reg->slug,
							//'menu-item-xfn' => '',
							'menu-item-status' => 'publish',
						));
					}}

					//add all regions page
					$all_reg = get_page_by_path('all-regions');
					if($all_reg) {
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-object-id' => $all_reg->ID,
							'menu-item-object' => 'page',
							'menu-item-parent-id' => $i,
							'menu-item-position' => 0,
							'menu-item-type' => 'post_type',
							'menu-item-title' => 'Все города',
							//'menu-item-url' => home_url('feed'),
							//'menu-item-description' => '',
							//'menu-item-attr-title' => '',
							//'menu-item-target' => '',
							//'menu-item-classes' => 'item-icon-rss',
							//'menu-item-xfn' => '',
							'menu-item-status' => 'publish',
						));
					}

					unset($rf);
					unset($other);
					unset($exclude);
					unset($regs);
					unset($reg);
					unset($all_reg);
				}

				//add category-type subitems
				if(!empty($s_sub)) { foreach($s_sub as $tax => $items){
					if($tax == 'page') {
						foreach($items as $sub_item){
							$sub_item = get_page_by_path($sub_item);

							if($sub_item) {
								wp_update_nav_menu_item($menu_id, 0, array(
									'menu-item-object-id' => $sub_item->ID,
									'menu-item-object' => 'page',
									'menu-item-parent-id' => $i,
									'menu-item-position' => 0,
									'menu-item-type' => 'post_type',
									//'menu-item-title' => '',
									//'menu-item-url' => '',
									//'menu-item-description' => '',
									//'menu-item-attr-title' => '',
									//'menu-item-target' => '',
									'menu-item-classes' => 'page-'.$sub_item->post_name,
									//'menu-item-xfn' => '',
									'menu-item-status' => 'publish',
								));
							}

						}
					}
					else {
						foreach($items as $sub_item){
							$sub_item = get_term_by('slug', $sub_item, $tax);
							if($sub_item) {
								wp_update_nav_menu_item($menu_id, 0, array(
									'menu-item-object-id' 	=> $sub_item->term_id,
									'menu-item-object' 		=> $sub_item->taxonomy,
									'menu-item-parent-id' 	=> $i,
									'menu-item-position' 	=> 0,
									'menu-item-type' 		=> 'taxonomy',
									//'menu-item-title' => '',
									//'menu-item-url' => '',
									//'menu-item-description' => '',
									//'menu-item-attr-title' => '',
									//'menu-item-target' => '',
									'menu-item-classes' 	=> $tax.'-'.$sub_item->slug,
									//'menu-item-xfn' => '',
									'menu-item-status' 		=> 'publish',
								));
							}
						}
					} //endif tax==page

				}}


			} //is_wp_error($i)
		}
	}


	//assign to location
    $locations = get_theme_mod('nav_menu_locations');
    $locations['primary_sec'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations );

    unset($locations);
    unset($menu_name);
    unset($menu_id);

	//Final
	echo 'Section menu created. Time in sec: '. (microtime(true) - $sec_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10);

	// newsletter menu
	$sec_start = microtime(true);
	$menu_name = 'Подписка';
	if(is_nav_menu($menu_name)){
	    wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
	$pages = array( 'subscribe', 'subscribe-weekly', 'subscribe-special', 'subscribe-rss' );
	foreach($pages as $sub_item){
	    $sub_item = get_page_by_path( $sub_item );

	    if($sub_item) {
	        wp_update_nav_menu_item($menu_id, 0, array(
	            'menu-item-object-id' => $sub_item->ID,
	            'menu-item-object' => 'page',
	            'menu-item-parent-id' => 0,
	            'menu-item-position' => 0,
	            'menu-item-type' => 'post_type',
	            'menu-item-classes' => 'page-'.$sub_item->post_name,
	            'menu-item-status' => 'publish',
	        ));
	    }
	}

	//assign to location
	$locations = get_theme_mod('nav_menu_locations');
	$locations['newsletter_types'] = $menu_id;
	set_theme_mod('nav_menu_locations', $locations );

	unset($locations);
	unset($menu_name);
	unset($menu_id);

	echo 'Newsletter menu created. Time in sec: '. (microtime(true) - $sec_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10);

	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);;
}
catch (TstNotCLIRunException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (TstCLIHostNotSetException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (Exception $ex) {
	echo $ex;
}