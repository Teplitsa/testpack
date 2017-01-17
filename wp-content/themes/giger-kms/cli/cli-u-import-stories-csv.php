<?php
/**
 * Tweaks for about section itmes - projects, pubs, reports
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $is_skip_first_line = True;
    
    $time_start = microtime(true);
    include('cli_common.php');

    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;
    
    $input_file = get_template_directory() . '/cli/data/stories.csv';
    printf( "Processing %s\n", $input_file );
    
    $count = -1;

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
		    $count++;
		    
		    if( $is_skip_first_line && !$count ) {
		        continue;
		    }
		    
		    $post_title = $line[0];
		    $post_content = $line[1];
		    $post_date = $line[2];
		    
		    $post_title = strip_tags( $post_title );
		    $post_content = nl2br( $post_content );
		    $post_date = date( 'Y-m-d H:i:s', strtotime( $post_date ) );
		    
			$post_arr = array(
				'post_title' 	=> $post_title,
				'post_type' 	=> 'story',
				'post_status' 	=> 'publish',
				'post_content' => $post_content,
				'post_excerpt' => '',
			);
            
            if( $post_date ) {
                $post_arr['post_date'] = $post_date;
            }

            $post_id = wp_insert_post($post_arr);
            
            unset( $line );
            
			wp_cache_flush();
            
 		}
	}

	printf( "\nStories imported: %d\n", $count );

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