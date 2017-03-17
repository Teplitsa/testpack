<?php
/**
 * Set posts params or create post if not exist
 *
 **/
set_time_limit (0);
ini_set('memory_limit','512M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;
    $updated_count = 0;
    $not_found_count = 0;
    
    $params = array(
        'post_type' => 'landing',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $landings = get_posts( $params );
    
    foreach( $landings as $index => $landing_id ) {
        
        $landing = tst_get_pb_post( $landing_id, 'landing' );
        
        if( $landing ) {
            
            $landing_content = get_post_meta( $landing_id, 'landing_content', true );
            $projects_list = tst_get_landing_projects_list_as_content( $landing );
            
            if( $projects_list && !strpos( $landing_content, 'projects-title-in-content' ) !== false ) {
                $landing_content .= $projects_list;
                update_post_meta( $landing_id, 'landing_content', $landing_content );
                $updated_count += 1;
            }
            
            printf( "%s\n", $landing->post_name );
        }
        else {
            printf( "not found: %d\n", $landing_id );
            $not_found_count += 1;
        }
    }
                
	printf( "\nPosts processed: %d; updated - %d; not_found - %d\n", count( $landings ), $updated_count, $not_found_count );

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
