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
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;
	$uploads = wp_upload_dir();
    
    $options = getopt("", array('localpdf::'));
    
    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );
    
    $localpdf = isset( $options['localpdf'] ) ? True : False;
    if( $localpdf ) {
        printf( "localpdf option ON\n" );
    }
    else {
        printf( "localpdf option OFF\n" );
    }
    
	$count = 0;
    $converted2pdf_count = 0;
    
    $params = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $atts_id = get_posts( $params );

	foreach( $atts_id as $file_id ) {
        
        if( $file_id ) {
            $file_url = wp_get_attachment_url( $file_id );
            printf( "file: %s\n", $file_url );
            
            if( TST_Import::get_instance()->is_must_convert2pdf( $file_url ) ) {
                
                $pdf_file_id = get_post_meta( $file_id, 'pdf_file_id', true );
                
                if( !$pdf_file_id ) {
                    $converted2pdf_count += 1;
                    $pdf_file_id = TST_Import::get_instance()->convert2pdf( $file_id, $localpdf );
                }
                
                printf( "pdf_file_id: %s\n", $pdf_file_id );
                
                if( $pdf_file_id ) {
                    
                    $pdf_file_url = wp_get_attachment_url( $pdf_file_id );
                    update_post_meta( $file_id, 'pdf_file_id', $pdf_file_id );
                    
                    printf( "pdf_file_url: %s\n", $pdf_file_url );
                    
                    $params = array(
                        'post_type' => array( 'post', 'import', 'project', 'event', 'landing', 'archive_page' ),
                        's' => $file_url,
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    );
                    
                    $posts_id = get_posts( $params );
                    
                    foreach( $posts_id as $post_id ) {
                        printf( "  post: %d\n", $post_id );
                        
                        $post = get_post( $post_id );
                        $post_content = $post->post_content;
                        $post_content = preg_replace( "/" . preg_quote( $file_url, '/' ) . "/", $pdf_file_url, $post_content );
                        
                        wp_update_post( array( 'ID' => $post_id, 'post_content' => $post_content ) );
                    }
                }
                
            }
        }

		wp_cache_flush();
        
		$count++;
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
