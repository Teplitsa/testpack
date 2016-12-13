<?php
/** Update post_type/term structure from "old" ASI
 *
 *  permalink structure
 *  sections
 *  category tree
 *  build main menu
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	// Sections
	echo 'Creating sections'.chr(10);
	$sections = array(
		'Сервисы'	=> 'services',
		'Советы'	=> 'advices',
		'Ресурсы'	=> 'resources',
		'Новости'	=> 'news',
		'О нас'		=> 'about'
	);

	//print_r(get_taxonomies());

	$count = 0;

	foreach($sections as $s_name => $s_key){
		$res = wp_insert_term($s_name, 'section', array('slug' => $s_key));
		if(!is_wp_error($res)){
			$count++;
			echo "Section ".$s_name.chr(10);
		}
		else {
			echo $res->get_error_message();
		}
	}

	echo $count." sections created. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

	wp_cache_flush();

	$news = get_term_by('slug', 'news', 'section');

	if($news && !is_wp_error($news)) {
		echo 'Updating posts'.chr(10);
		$query = new WP_Query(array(
			'post_type' => array('post'),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'cache_results' => false,
			'orderby' => 'ID',
			'order' => 'ASC',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows' => true,
			'suppress_filters' => true,
			'fields' => 'ids'
		));

		$count = 0;
		if($query->have_posts()){
			foreach($query->posts as $p) {
				wp_set_object_terms((int)$p, $news->term_id, 'section', false);
				wp_cache_flush();
				$count++;
			}
		}

		echo 'Add news to section: '.$count.chr(10);
	}


	// Tags in news
	$old = get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false,));
	$deleted_tags_url = array();
	if(!empty($old)) {
		foreach($old as $oldt) {
			$deleted_tags_url[] = $oldt->slug;
			wp_delete_term($oldt->term_id, $oldt->taxonomy);
		}
		wp_cache_flush();
	}
	update_option('_tst_deleted_tags_url', $deleted_tags_url);

	$tags = array(
		'акции'				=> array(261, 212, 159, 151, 79,   ),
		'группа поддержки'	=> array(1456, 1383, 1306, 1247, 1330, 10),
		'вебинары'			=> array(1472, 1273 ),
		'встречи с врачом'	=> array(1481, 1462, 1285, 272),
		'Казанский марафон'	=> array(261, 151 ),
		'объявление' 		=> array(1445, 1212, 209)
	);

	$count = 0;
	foreach($tags as $t_name => $t_ids){
		$res = wp_insert_term($t_name, 'post_tag');

		if(!is_wp_error($res)){
			foreach($t_ids as $p) {
				wp_set_object_terms((int)$p, $res['term_id'], 'post_tag', true);
				wp_cache_flush();
			}

			echo "Added tag ".$t_name.chr(10);
		}
		else {
			echo $res->get_error_message();
		}

		$count++;
	}

	echo $count." Tags created. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);



	//Cleanup
	echo 'Flush rewrite rules'.chr(10);
	flush_rewrite_rules(false);


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