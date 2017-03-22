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

	foreach(get_posts(array('post_type' => 'project', 'nopaging' => true)) as $project) {
        update_post_meta($project->ID, 'project_archive_placement', '1');
    }

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