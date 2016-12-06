<?php
/**
 * Temp Utilities
 *
 **/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//Posts list metas
	$file = BASE_PATH.'wp-content/themes/giger-kms/data/items.csv';
	echo "File: ".$file.chr(10);
	echo 'Memory before everything: '.memory_get_usage(true).chr(10).chr(10);

	$posts = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'cache_results' => false,
		'orderby' => 'ID',
		'order' => 'ASC',
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'no_found_rows' => true,
		'suppress_filters' => true,
	));

	//Open file
	if(!is_writable(dirname($file))){
		throw new Exception('A target directory does not exist or is not writable '.dirname($file));
	}

	$csv_handler = fopen($file,'w');
	if($csv_handler)
		fputcsv($csv_handler, array('ID', 'Title', 'Parent', 'Section', 'URL'), ",");

	$count = 0;
	
	if(empty($posts)){
		throw new Exception('No items found for export');
	}
	
	foreach($posts as $p) {
	
		$sec = get_the_term_list($p->ID, 'section', '', ', ', '');
		
		$r = array();
		$r[] = $p->ID;
		$r[] = $p->post_title;
		$r[] = $p->post_parent;
		$r[] = strip_tags($sec);
		$r[] = get_permalink($p);

		fputcsv($csv_handler, $r, ",", '"');
		unset($r);

		$count++;
	}

	

	fclose($csv_handler);
	

	echo "Added items ".$count.chr(10);

	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
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
