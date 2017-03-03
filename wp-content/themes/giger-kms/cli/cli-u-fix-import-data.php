<?php
/**
* Fix imported data
*
**/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;


    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_name = CONCAT( 'datt-', post_name ) WHERE post_type = %s AND post_name NOT LIKE 'datt-%%' ", 'attachment' ) );
    printf( "post_name prefix added for all imported attachments\n" );
    
    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_name = CONCAT( 'di-', post_name ) WHERE post_type = %s AND post_name NOT LIKE 'di-%%' ", 'import' ) );
    printf( "post_name prefix added for all import items\n" );

    $publications_ids = tst_get_latest_publications( -1, -1, 'ids' );
    $updated_count = 0;
    foreach( $publications_ids as $post_id ) {
        $meta_file_date = get_post_meta( $post_id, 'file_date', true );
        if( $meta_file_date ) {
            $post_date = $meta_file_date;
            
            printf( "%d date is %s\n", $post_id, $post_date );
            
            wp_update_post(
                array (
                    'ID' => $post_id,
                    'post_date' => $post_date,
                    'post_date_gmt' => get_gmt_from_date( $post_date ),
                )
            );
            $updated_count += 1;
        }
    }
    printf( "Date fixed for %d imported publications\n", $updated_count ); 

    //regenerate permalinks
    flush_rewrite_rules();

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
