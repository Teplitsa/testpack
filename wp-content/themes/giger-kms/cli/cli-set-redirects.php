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
    
    $table_name = $wpdb->prefix . 'tst_redirects';
    
    if( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `old_url` varchar(255) NOT NULL,
            `new_url` varchar(255) NOT NULL,
            KEY `old_url` (`old_url`)
            ) ENGINE=InnoDB;";
        $wpdb->query( $sql );
    }
    else {
        $wpdb->query( "TRUNCATE {$table_name};" );
    }
    
    
    $sql = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = %s ORDER BY post_id ASC";
    $sql = $wpdb->prepare( $sql, 'old_url' );
    
    $metas = $wpdb->get_results( $sql );
    foreach( $metas as $k => $post_meta ) {
        $post_id = $post_meta->post_id;
        $old_url = $post_meta->meta_value;
        
        $post = get_post( $post_id );
        if( $post ) {
            if( $post->post_type == 'attachment' ) {
                $new_url = wp_get_attachment_url( $post_id );
            }
            else {
                $new_url = get_permalink( $post_id );
            }
            if( $old_url && $new_url ) {
                $old_url = str_replace( 'http://dront.ru/', '/', $old_url );
                $new_url = str_replace( home_url( '/' ), '/', $new_url );
                $wpdb->insert( $table_name, array( 'old_url' => $old_url, 'new_url' => $new_url ) );
            }
        }
    }

    $sql = "DELETE FROM {$table_name} WHERE old_url IN ('/')";
    $wpdb->query( $sql );
    
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
