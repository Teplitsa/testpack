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
	$file = BASE_PATH.'wp-content/languages/events-months.csv';
	echo "File: ".$file.chr(10);
	echo 'Memory before everything: '.memory_get_usage(true).chr(10).chr(10);

	$months = array('2013-04-01', '2013-11-01', '2014-04-01', '2014-11-01', '2015-04-01', '2015-11-01', '2016-04-01', '2016-11-01');
	$count = 0;

	//Open file
	if(!is_writable(dirname($file))){
		throw new Exception('A target directory does not exist or is not writable '.dirname($file));
	}

	foreach($months as $i => $m_label) {

		echo "Performing month ".$m_label.chr(10);

		if($i == 0) {
			$csv_handler = fopen($file,'w');
			if($csv_handler)
				fputcsv($csv_handler, array('Month', 'Event date', 'Title', 'URL'), ",");
		}
		else {
			$csv_handler = fopen($file,'a');
		}

		$first_day_stamp = strtotime(date('01-m-Y 00:00', strtotime($m_label)));
		$last_day_stamp = strtotime(date('t-m-Y 23:59', strtotime($m_label)));

		$posts = get_posts(array(
			'post_type' => 'event',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'cache_results' => false,
			'orderby' => 'ID',
			'order' => 'ASC',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows' => true,
			'suppress_filters' => true,
			'fields' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'event_date_start',
					'value' => $last_day_stamp,
					'compare' => '<=',
					'type' => 'numeric'
				),
				array( //only future
					'key' => 'event_date_start',
					'value' => $first_day_stamp,
					'compare' => '>=',
					'type' => 'numeric'
				)
			)
		));

		foreach($posts as $p) {

			$edate = get_post_meta($p, 'event_date_start', true);

			$r = array();
			$r[] = date_i18n('F Y', strtotime($m_label));
			$r[] = date('d.m.Y', $edate);
			$r[] = esc_attr(get_the_title($p));
			$r[] = get_permalink($p);

			fputcsv($csv_handler, $r, ",");
			unset($r);
			unset($edate);

			$count++;
		}

		echo count($posts)." posts added for month ".$m_label.chr(10);

		fclose($csv_handler);
		wp_cache_flush();
		unset($posts);
	}

	echo "Added posts ".$count.chr(10);

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
