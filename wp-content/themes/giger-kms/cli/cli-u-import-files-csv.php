<?php
/**
 * Tweaks for about section itmes - projects, pubs, reports
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
    $input_file = isset( $argv[2] ) ? $argv[2] : '';
    printf( "Processing %s\n", $input_file );

	$count = 0;
	$csv = array_map('str_getcsv', file( $input_file ));

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
            
            $url = $line[0];
            printf( "Saving %s\n", $url );
            
            if(false !== strpos($url, 'dront.ru')){
                
                $exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $url );
                
                if( $exist_attachment ) {
                    $file_id = $exist_attachment->ID;
                    $file_url = wp_get_attachment_url($file_id);
                    
                    printf( "Exist %s\n", $file_url );
                }
                else {
                    
                    $attachment_id = TST_Import::get_instance()->import_file( $url );
                    
                    if( $attachment_id ) {
                        $file_url = wp_get_attachment_url( $attachment_id );
                        printf( "Saved %s\n", $file_url );
                    }
                    else {
                        printf( "IMPORT ERROR\n");
                    }
                }

            }
            
			wp_cache_flush();
			$count++;
		}
	}

	print( "\n" );
    printf( "Files from %s stored\n", $input_file );

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
