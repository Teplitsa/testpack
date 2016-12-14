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

	delete_transient('frm_options');
	delete_transient('frmpro_options');
	delete_transient('frmpro_css');

	//Upload test
	$file = WP_CONTENT_DIR.'/themes/giger-kms/cli/formidable-opt.csv';

	$opt =$wpdb->get_results("SELECT * FROM $wpdb->options WHERE (option_name LIKE '%frm_%' OR option_name LIKE '%frmpro%' ) AND (option_name NOT LIKE '%wpseo%' AND option_name NOT LIKE '%css%'  AND option_name NOT LIKE '%transient%')");

	if(!empty($opt)){
		$csv_handler = fopen($file,'w');

		foreach($opt as $obj) {
			$r = array();
			$r[] = $obj->option_name;
			$r[] = $obj->option_value;
			fputcsv($csv_handler, $r, ",");
		}
	}
	fclose($csv_handler);

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
