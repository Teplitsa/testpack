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
	include( get_template_directory() . '/inc/class-import.php' );

	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	$uploads = wp_upload_dir();

	//Read file
	$handle = file('projects.tsv');
	$csv = array();

	if($handle) { foreach($handle as $i => $line) {
		//$csv = array_map('str_getcsv', file('projects.csv'));
		$csv[] = str_getcsv($line, "\t");
	}}

	echo "Read lines ".count($csv).chr(10);

	$count = 0;
	foreach($csv as $i => $line) {

		if($i == 0)
			continue;

			
			
		$post_name = trim($line[2]);
		$exist_page = tst_get_pb_post( $post_name, 'project' );
			
		$page_data = array();

		$page_data['ID'] = $exist_page ? $exist_page->ID : 0;
		$page_data['post_type'] = 'project';
		$page_data['post_status'] = 'publish';
		$page_data['post_excerpt'] = '';

		$page_data['post_title']	= $line[0];
		$page_data['post_name'] 	= $post_name;
		$page_data['menu_order']	= (int)$line[5];

		//content
		if(false !== strpos($line[1], 'import | ')){
			//import fromt old page

			echo "Import fromt URL ".$line[1].chr(10);
			$old_url = str_replace('import | ', '', $line[1]);
			$old_post = TST_Import::get_instance()->get_post_by_old_url($old_url);

			if($old_post) {

				$page_data['post_content'] = $old_post->post_content;

				//images
				if(trim($line[4]) == 'none' || trim($line[4]) == 'NEED'){
					$img = tst_get_connected_images($old_post);
					if($img) {
						$page_data['meta_input']['_thumbnail_id'] = (int)$img[0]->ID;
					}
				}
			}
		}
		else {
			$page_data['post_content'] = trim($line[1]);
		}

		//post parent
		if($line[3] != 'none'){
			$parent = get_posts(array('post_type' => 'project', 'posts_per_page' => 1, 'name' => trim($line[3]), 'post_status' => 'publish'));
			if($parent){
				$page_data['post_parent'] = (int)$parent[0]->ID;
			}

		}
		else {
			$page_data['post_parent'] = 0;
		}

		//thumbnail
		$thumb_id = false;
		if(false !== strpos($line[4], 'http://dront.ru')) {
			//imported old photo
			$old_url = trim($line[4]);
			$atthachment = TST_Import::get_instance()->get_attachment_by_old_url($old_url);
			if($atthachment){
				$thumb_id = $atthachment->ID;
			}
		}
		elseif(trim($line[4]) != 'none' && trim($line[4]) != 'NEED'){
			//new photo
			$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.trim($line[4]);
			//var_dump($path);

			$test_path = $uploads['path'].'/'.trim($line[4]);
			//var_dump($test_path);

			if(!file_exists($test_path)) {
				$thumb_id = tst_upload_img_from_path($path);
				echo 'Uploaded thumbnail '.$thumb_id.chr(10);
			}
			else {
				$thumb_id = tst_register_uploaded_file($test_path);
			}
			var_dump($thumb_id);
		}

		if($thumb_id){
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		$uid = wp_insert_post($page_data);

		if($uid){
			$count++;
			//create connections

			$landing = array();
			$landing = ($line[7] != 'none') ? array_merge($landing, explode(',', $line[7])) : $landing;
			$landing = (!empty($line[8]) && $line[8] != 'none') ? array_merge($landing, explode(',', $line[8])) : $landing;
			$landing = (!empty($line[9]) && $line[9] != 'none') ? array_merge($landing, explode(',', $line[9])) : $landing;
			$landing = array_map('trim', $landing);
			$landing = array_unique($landing);


			$c_count = 0;
			if(!empty($landing)) { foreach($landing as $l_slug) {
				if($l_slug == 'none')
					continue;

				$item = get_posts(array('post_type' => 'landing', 'posts_per_page' => 1, 'name' => $l_slug));
				if($item) {
					$c = p2p_type('landing_project')->connect($item[0]->ID, $uid, array('date' => current_time('mysql')));
					if(!is_wp_error($c)){
						$c_count++;
					}
				}
			}}

			echo 'Added '.$c_count.' connections for '.$page_data['post_title'].chr(10);

			//add tags
			if(!empty($line[6]) && $line[6] != 'none') {
				wp_set_post_terms((int)$uid, $line[6], 'project_cat', false);
				wp_cache_flush();
			}

			//documents
			$doc = ($line[10] != 'none') ? explode(',', $line[10]) : array();
			$doc = array_map('trim', $doc);
			if($doc) {
				$d_count = 0;
				foreach($doc as $d) {
					$d = str_replace('^', ',', $d); //yes, we have commas in URLs
					$d_doc = TST_Import::get_instance()->get_attachment_by_old_url($d);
					if($d_doc) {
						$c = p2p_type('connected_attachments')->connect((int)$uid, $d_doc->ID, array('date' => current_time('mysql')));
						if(!is_wp_error($c)){
							$d_count++;
						}
					}
				}

				echo 'Added '.$d_count.' document for '.$page_data['post_title'].chr(10);
			}
		}
	}

	echo "Imported projects ".$count.chr(10);

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