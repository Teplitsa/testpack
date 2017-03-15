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
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-chebpotop',
        'thumbnail' => 'datt-cheboksarges',
    ),
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-bereginya',
        'thumbnail' => 'datt-1-2-2',
    ),
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-ecomap',
        'thumbnail' => 'datt-sbereg-center2',
    ),
    
    
    // projects
    array(
        'post_type' => 'project',
        'post_name' => 'birds-kadastr',
        'thumbnail' => 'birds-kadastr-eggs.jpg',
    ),
    array(
        'post_type' => 'project',
        'post_name' => 'reptiles-photo',
        'thumbnail' => 'datt-reptiles',
    ),
    array(
        'post_type' => 'project',
        'post_name' => 'marsh-konkurs',
        'thumbnail' => 'marsh-park.jpg',
    ),
    
    
    // leyka_campaign
    array(
        'post_type' => 'leyka_campaign',
        'post_name' => 'donate',
        'thumbnail' => 'sopr.jpg',
    ),

    // import
    array(
        'post_type' => 'import',
        'post_name' => 'di-nizhegorodskoe-ekologo-pravovoe-agentstvo',
        'thumbnail' => 'eco-law-agency-002.jpg',
    ),
    
    // posts
    array(
        'post_type' => 'post',
        'post_name' => 'v-kerzhenskom-zapovednike-nachalsya-sezon-zimnih-lyzhnyh-ekskursij',
        'thumbnail' => 'datt-rubbit-footsteps',
    ),
    
    // attachments
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-16-11',
        'thumbnail' => 'bereginya-201612.jpg',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-limits_to_growth',
        'thumbnail' => 'publications-2016-002.jpg',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-factor_four',
        'thumbnail' => 'publications-2016-004.jpg',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-rastenia-detyam',
        'thumbnail' => 'publications-2016-001.jpg',
    ),
    
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-chto-takoe-prirodoohrannoe-dvizhenie',
        'thumbnail' => 'publications-2016-006.jpg',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-serebrovsky-1918',
        'thumbnail' => 'publications-2016-005.jpg',
    ),
//     array(
//         'post_type' => 'attachment',
//         'post_name' => '',
//         'thumbnail' => '',
//     ),
//     array(
//         'post_type' => 'attachment',
//         'post_name' => '',
//         'thumbnail' => '',
//     ),
    
    
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
                
                $thumbnail_id = 0;
                
                if( is_array( $post_data['thumbnail'] ) ) {
                    
                    $post = tst_get_pb_post( $post_data['thumbnail'][0], $post_data['thumbnail'][1] );
                    if( $post ) {
                        $thumbnail_id = $post->ID;
                    }
                    
                }
                elseif( preg_match( '/^datt-/', $post_data['thumbnail'] ) ) {
                    $attachment = tst_get_pb_post( $post_data['thumbnail'], 'attachment' );
                    $thumbnail_id = $attachment ? $attachment->ID : 0;
                }
                elseif( is_numeric( $post_data['thumbnail'] ) && (int)$post_data['thumbnail'] ) {
                    $thumbnail_id = $post_data['thumbnail'];
                }
                elseif( $post_data['thumbnail'] ) {
                    $thumbnail_id = TST_Import::get_instance()->maybe_import_local_file( dirname( __FILE__ ) . '/sideload/' . $post_data['thumbnail'] );
                }
                
                if( $thumbnail_id ) {
                    printf( "set post_id: %d; thumbnail: %d, %s\n", $post->ID, $thumbnail_id, get_attached_file( $thumbnail_id ) );
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