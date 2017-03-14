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
    
    $options = getopt("", array('file:', 'tag:', 'localpdf::'));
    
    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );
    
    $localpdf = isset( $options['localpdf'] ) ? True : False;
    if( $localpdf ) {
        printf( "localpdf option ON\n" );
    }
    else {
        printf( "localpdf option OFF\n" );
    }
    
    $tag_slug = isset($options['tag']) ? $options['tag'] : '';
    if( $tag_slug ) {
        printf( "Mark with TAG:  %s\n", $tag_slug );
    }

	$count = 0;
    $converted2pdf_count = 0;
	$csv = array_map('str_getcsv', file( $input_file ));

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
            
            $url = $line[0];
            $file_id = 0;
            $file_url = '';
            
            if(false !== strpos($url, 'dront.ru')) { # && preg_match( '/.*(?:jpeg|jpg|png|gif|pdf)$/i', $url ) 
                
                printf( "Saving %s\n", $url );
                
                $exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $url );
                
                if( $exist_attachment ) {
                    $file_id = $exist_attachment->ID;
                    $file_url = wp_get_attachment_url($file_id);
                    
                    printf( "Exist %s\n", $file_url );
                }
                else {
                    
                    $attachment_id = TST_Import::get_instance()->import_big_file( $url );
                    
                    if( $attachment_id ) {
                        $file_id = $attachment_id;
                        $file_url = wp_get_attachment_url( $attachment_id );
                        printf( "Saved %s\n", $file_url );
                    }
                    else {
                        printf( "IMPORT ERROR\n");
                    }
                }
                unset( $exist_attachment );
                
                if( $file_id ) {
                    
                    if( TST_Import::get_instance()->is_must_convert2pdf( $file_url ) ) {
                        $converted2pdf_count += 1;

                        $pdf_file_id = TST_Import::get_instance()->convert2pdf( $file_id, $localpdf );
                        if( $pdf_file_id ) {
                            update_post_meta( $file_id, 'pdf_file_id', $pdf_file_id );
                            $file_id = $pdf_file_id;
                            $file_url = wp_get_attachment_url( $file_id );
                        }
                    }
                    elseif( TST_Import::get_instance()->is_must_convert2pdf( $url ) ) {
                        $pdf_file = get_attached_file( $file_id );
                        $pdf_file_info = pathinfo( $pdf_file );                            
                        TST_Import::get_instance()->copy_to_localpdf( $pdf_file, $pdf_file_info['basename'] );
                    }
                    
                }                
                
                if( $file_id ) {
                    if( $tag_slug ) {
                        
                        TST_Import::get_instance()->set_file_date( $file_id, $url, $tag_slug );
                        
                        $tag = get_term_by( 'slug', $tag_slug, 'attachment_tag' );
                        if( $tag ) {
                            wp_set_object_terms( $file_id, $tag->term_id, 'attachment_tag' );
                        }
                        unset( $tag );
                    }
                }

            }
            
			wp_cache_flush();
            
            unset( $line );
            
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
