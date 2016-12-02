<?php
/**
 * Sort out posts ander new sections
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//Opinion
	echo 'Updating Opinions'.chr(10);
	$query = new WP_Query(array(
		'post_type' => array('any'),
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'cache_results' => false,
		'orderby' => 'ID',
		'order' => 'ASC',
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'no_found_rows' => true,
		'suppress_filters' => true,
		'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => array('interview', 'directors')
			)
		)
	));

	$section = get_term_by('slug', 'opinions', 'section');
	$count = 0;
	if($query->have_posts()){
		foreach($query->posts as $p){

			if($wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d", $p, $section->term_taxonomy_id)))
				continue;

			$wpdb->insert($wpdb->term_relationships, array('object_id' => $p, 'term_taxonomy_id' => $section->term_taxonomy_id));
			wp_cache_delete($p, 'section_relationships' );
			$count++;
		}

		unset($p);

		//update counter
		echo 'Updating section count'.chr(10);
		$s = microtime(true);

		$term_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(object_id) FROM $wpdb->term_relationships as tr JOIN $wpdb->posts as p on p.ID = tr.object_id WHERE (tr.term_taxonomy_id = %d AND p.post_type IN ('post','news','announcement', 'report') AND p.post_status = 'publish')", $section->term_taxonomy_id));

		if($term_count)
			$wpdb->update($wpdb->term_taxonomy, array('count' => $term_count), array('term_taxonomy_id' => $section->term_taxonomy_id), array('%d'), array('%d'));

		echo 'Count updated - '.$term_count.'. Time taken in sec: '.(microtime(true) - $s).chr(10);
	}

	echo 'Updated posts: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10).chr(10);

	unset($query);
	unset($count);
	unset($section);
	wp_cache_flush();

	//Articles
	echo 'Updating Articles'.chr(10);
	$query = new WP_Query(array(
		'post_type' => array('any'),
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'cache_results' => false,
		'orderby' => 'ID',
		'order' => 'ASC',
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'no_found_rows' => true,
		'suppress_filters' => true,
		'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => array('analytics')
			)
		)
	));

	$section = get_term_by('slug', 'articles', 'section');
	$count = 0;
	if($query->have_posts()){
		foreach($query->posts as $p){

			if($wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d", $p, $section->term_taxonomy_id)))
				continue;

			$wpdb->insert($wpdb->term_relationships, array('object_id' => $p, 'term_taxonomy_id' => $section->term_taxonomy_id));
			wp_cache_delete($p, 'section_relationships' );
			$count++;
		}

		unset($p);

		//update counter
		echo 'Updating section count'.chr(10);
		$s = microtime(true);

		$term_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(object_id) FROM $wpdb->term_relationships as tr JOIN $wpdb->posts as p on p.ID = tr.object_id WHERE (tr.term_taxonomy_id = %d AND p.post_type IN ('post','news','announcement', 'report') AND p.post_status = 'publish')", $section->term_taxonomy_id));

		if($term_count)
			$wpdb->update($wpdb->term_taxonomy, array('count' => $term_count), array('term_taxonomy_id' => $section->term_taxonomy_id), array('%d'), array('%d'));

		echo 'Count updated - '.$term_count.'. Time taken in sec: '.(microtime(true) - $s).chr(10);
	}

	echo 'Updated posts: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10).chr(10);

	unset($query);
	unset($count);
	unset($section);
	wp_cache_flush();

	//Video
	echo 'Updating Video'.chr(10);
	$posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_content LIKE '%youtube%' AND post_status = 'publish'");

	$section = get_term_by('slug', 'video', 'section');
	$count = 0;
	if($posts){
		foreach($posts as $p){

			if($wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d", $p->ID, $section->term_taxonomy_id)))
				continue;

			$wpdb->insert($wpdb->term_relationships, array('object_id' => $p->ID, 'term_taxonomy_id' => $section->term_taxonomy_id));
			wp_cache_delete($p->ID, 'section_relationships' );
			$count++;
		}

		unset($p);

		//update counter
		echo 'Updating section count'.chr(10);
		$s = microtime(true);

		$term_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(object_id) FROM $wpdb->term_relationships as tr JOIN $wpdb->posts as p on p.ID = tr.object_id WHERE (tr.term_taxonomy_id = %d AND p.post_type IN ('post','news','announcement', 'report') AND p.post_status = 'publish')", $section->term_taxonomy_id));

		if($term_count)
			$wpdb->update($wpdb->term_taxonomy, array('count' => $term_count), array('term_taxonomy_id' => $section->term_taxonomy_id), array('%d'), array('%d'));

		echo 'Count updated - '.$term_count.'. Time taken in sec: '.(microtime(true) - $s).chr(10);
	}

	echo 'Updated posts: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10).chr(10);

	unset($posts);
	unset($count);
	unset($section);

	//Photo
	echo 'Updating Photo'.chr(10);
	$posts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_content LIKE '%[gallery%' AND post_status = 'publish'");

	$section = get_term_by('slug', 'photos', 'section');
	$count = 0;
	if($posts){
		foreach($posts as $p){

			if($wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d", $p->ID, $section->term_taxonomy_id)))
				continue;

			$wpdb->insert($wpdb->term_relationships, array('object_id' => $p->ID, 'term_taxonomy_id' => $section->term_taxonomy_id));
			wp_cache_delete($p->ID, 'section_relationships' );
			$count++;
		}

		unset($p);

		//update counter
		echo 'Updating section count'.chr(10);
		$s = microtime(true);

		$term_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(object_id) FROM $wpdb->term_relationships as tr JOIN $wpdb->posts as p on p.ID = tr.object_id WHERE (tr.term_taxonomy_id = %d AND p.post_type IN ('post','news','announcement', 'report') AND p.post_status = 'publish')", $section->term_taxonomy_id));

		if($term_count)
			$wpdb->update($wpdb->term_taxonomy, array('count' => $term_count), array('term_taxonomy_id' => $section->term_taxonomy_id), array('%d'), array('%d'));


		echo 'Count updated - '.$term_count.'. Time taken in sec: '.(microtime(true) - $s).chr(10);
	}

	echo 'Updated posts: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	echo 'Memory '.memory_get_usage(true).chr(10).chr(10);

	unset($posts);
	unset($count);
	unset($section);
	wp_cache_flush();


	

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