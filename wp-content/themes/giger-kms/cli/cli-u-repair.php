<?php
/**
 * Fixes for incorrect content
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	$posts = get_posts(array(
		'post_type' => array('post', 'item', 'page'),
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'orderby' => 'ID',
		'order' => 'ASC',
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'cache_results' => false,
		'fields' => 'ids'
	));

	$count = 0;
	if(!empty($posts)){
		foreach($posts as $p) {

			$post = get_post($p);
			$content = $post->post_content;

			preg_match('/<script>(.*?)<\/script>/s', $content, $m);
			if(isset($m[1]) && !empty($m[1])){
				$content = str_replace($m[0], '', $content);
			}

			preg_match('/\(function\((.*?)\'pageview\'\)\;/s', $content, $m);
			if(isset($m[0]) && !empty($m[0])){
				$content = str_replace($m[0], '', $content);
			}

			$post_data = array();
			$post_data['ID'] = $post->ID;
			$post_data['post_content'] = $content;
			wp_update_post($post_data);

			$count++;
	}}

	echo 'Updated content in items '.$count.chr(10);

	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in sec: ' . (microtime(true) - $time_start).chr(10).chr(10);
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