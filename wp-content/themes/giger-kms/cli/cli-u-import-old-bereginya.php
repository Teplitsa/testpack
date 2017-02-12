<?php
/**
 * Tweaks for about section itmes - projects, pubs, reports
 *
 **/
set_time_limit (0);
ini_set('memory_limit','512M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;
	$uploads = wp_upload_dir();
    
    $options = getopt("", array('dir:', 'action:'));
    
    $input_dir = isset($options['dir']) ? $options['dir'] : '';
    printf( "Processing %s\n", $input_file );

    $action = $options['action'] ? $options['action'] : '';
    printf( "action: %s\n", $action );
    
    if( file_exists( $input_dir ) ) {
        $import_bereginya = TST_ImportOldBereginya::get_instance();
        
        if( method_exists( $import_bereginya, $action ) ) {
            $import_bereginya->$action( $input_dir );
        }
        else {
            printf( "unknown action: %s\n", $action );
        }
    }
    
    printf( "\n" );
    
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
