<?php
/**
 * Set posts thumbnails
 *
 **/
set_time_limit (0);
ini_set('memory_limit','512M');

$process_posts = array(
    // landings
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-ornotologlab',
        'thumbnail' => 'birds-dront-ornotologlab.jpg',
    ),
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-publications',
        'thumbnail' => '',
    ),
    
    // projects
    array(
        'post_type' => 'project',
        'post_name' => 'birds-kadastr',
        'thumbnail' => 'birds-kadastr-eggs.jpg',
    ),
);

try {
    $time_start = microtime(true);
    include('cli_common.php');
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;
    $not_found_posts = 0;
    
    foreach( $process_posts as $index => $post_data ) {
        $post = tst_get_pb_post( $post_data['post_name'], $post_data['post_type'] );
        
        printf( "post: %s, type: %s, thumbnail: %s\n", $post_data['post_name'], $post_data['post_type'], $post_data['thumbnail'] );
        
        if( $post ) {
            
            if( isset( $post_data['thumbnail'] ) && $post_data['thumbnail'] ) {
                $thumbnail_id = TST_Import::get_instance()->maybe_import_local_file( dirname( __FILE__ ) . '/sideload/' . $post_data['thumbnail'] );
                
                if( $thumbnail_id ) {
                    printf( "set post thumbnail: %d, %s\n", $thumbnail_id, get_attached_file( $thumbnail_id ) );
                    set_post_thumbnail( $post->ID, $thumbnail_id );
                    wp_update_attachment_metadata( $thumbnail_id, wp_generate_attachment_metadata( $thumbnail_id, get_attached_file( $thumbnail_id ) ) );
                }
                else {
                    printf( "set thumbnail error!\n" );
                }
            }
            elseif( isset( $post_data['thumbnail'] ) ) {
                delete_post_thumbnail( $post->ID );
            }
            
        }
        else {
            $not_found_posts += 1;
        }
    }
                
	printf( "\nPosts updated: %d\n", count( $process_posts ) );
	printf( "Not found posts: %d\n", $not_found_posts );

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