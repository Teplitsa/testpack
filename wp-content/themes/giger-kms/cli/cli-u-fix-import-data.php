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


    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_name = CONCAT( 'di-', post_name ) WHERE post_type = %s AND post_name NOT LIKE 'di-%%' ", 'attachment' ) );
    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_name = CONCAT( 'di-', post_name ) WHERE post_type = %s AND post_name NOT LIKE 'di-%%' ", 'import' ) );


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
