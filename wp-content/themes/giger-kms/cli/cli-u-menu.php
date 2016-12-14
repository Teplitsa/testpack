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

	//Main menu
	$menu_name = 'Главное меню';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
	$sections = array('work', 'ecoproblems', 'departments', 'about', 'supportus');


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
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations );

	echo 'Main menu created. Time in sec: '. (microtime(true) - $time_start).chr(10);


	//Sitemap
	$menu_name = 'Карта сайта';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
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