<?php
/**
 * Create menus and delete old ones
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;


	$uploads = wp_upload_dir();

	//Impport people
	$handle = file('people.tsv');
	$csv = array();
	if($handle) { foreach($handle as $i => $line) {
		//$csv = array_map('str_getcsv', file('projects.csv'));
		$csv[] = str_getcsv($line, "\t");
	}}

	var_dump(count($csv));

	$count = 0;
	foreach($csv as $i => $line) {

		if($i == 0)
			continue;

		$page_data = array();

		$page_data['ID'] = 0;
		$page_data['post_type'] = 'person';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = $line[0];
		$page_data['post_excerpt'] = $line[1];
		$page_data['post_content'] = $line[2];

		if(isset($line[3]) && !empty($line[3])){
			$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$line[3];
			var_dump($path);

			$test_path = $uploads['path'].'/'.$line[3];
			if(!file_exists($test_path)) {
				$thumb_id = tst_upload_img_from_path($path);
				echo 'Uploaded thumbnail '.$thumb_id.chr(10);
			}
			else {
				$a_url = $uploads['url'].'/'.$line[3];
				$thumb_id = attachment_url_to_postid($a_url);
			}

			if($thumb_id){
				$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
			}
		}

		$uid = wp_insert_post($page_data);

		if($uid){
			$count++;
			//create connections
			
			$landing = array();
			$landing = (!empty($line[4])) ? explode(',', $line[4]) : array();
			$landing = (!empty($line[5])) ? array_merge($landing, explode(',', $line[5])) : array();
			$landing = (!empty($line[6])) ? array_merge($landing, explode(',', $line[6])) : array();
			$landing = array_map('trim', $landing);
			$landing = array_unique($landing);

			$c_count = 0;
			if(!empty($landing)) { foreach($landing as $l_slug) {
				if($l_slug == 'none')
					continue;

				$item = get_posts(array('post_type' => 'landing', 'posts_per_page' => 1, 'name' => $l_slug));
				if($item) {
					$c = p2p_type('landing_person')->connect($item[0]->ID, $uid, array('date' => current_time('mysql')));
					if(!is_wp_error($c)){
						$c_count++;
					}
				}
			}}

			echo 'Added '.$c_count.' connections for '.$page_data['post_title'].chr(10);
		}
	}

	echo "Imported people ".$count.chr(10);

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