<?php
/**
 * Import posts from old dront CSV
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

    $options = getopt("", array('file:', 'localpdf::', 'convert2pdf::'));
    
    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );

    $localpdf = isset( $options['localpdf'] ) ? True : False;
    $is_convert2pdf = isset( $options['convert2pdf'] ) ? True : False;
    
    $count = 0;
    $converted2pdf_count = 0;
    $csv = array_map('str_getcsv', file( $input_file ));

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
            
//            print_r( $line );
            
//			if($i == 0)
//				continue;

			$post_type = $line[0];
            $page_url = $line[1];
            $post_title = strip_tags( $line[2] );
            $post_content = $line[3];
            $post_date = $line[4];
            $post_files_string = $line[5];
            $files_url = explode( '|', $post_files_string );
            $parent_url = $line[6];
            
            printf( "page: %s\n", $page_url );
            
//            print_r( $files_url );
//            printf( "%s: %s\n", $post_type, $post_title );
            printf( "files: %d\n", count( $files_url ) );
            
			$file_url = '';
			$file_id = 0;
            $post_files = array();

			printf( "Importing %s\n", $page_url );

            $files_id = array();
            foreach( $files_url as $url ) {
                printf( "orig url: %s\n", $url );
                
                $file_id = 0;
                if(false !== strpos($url, 'dront.ru') ) {
                    
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
                            printf( "File saved %s\n", $file_url );
                        }
                        else {
                            printf( "IMPORT ERROR\n");
                        }
                    }
                    
                    unset( $exist_attachment );
                    
                    if( $file_id && $is_convert2pdf ) {
                        
                        if( TST_Import::get_instance()->is_must_convert2pdf( $file_url ) ) {
                            $converted2pdf_count += 1;
                            
                            $pdf_file_id = TST_Import::get_instance()->convert2pdf( $file_id, $localpdf );
                            if( $pdf_file_id ) {
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
                        
                    printf( "file_id: %d\n", $file_id );
                    
                    if( $file_id ) {
                        
                        $post_files[] = array( 'id' => $file_id, 'url' => $file_url );

                        // set proper file guid
                        $file_guid = TST_Import::get_instance()->get_attachment_guid_by_url( $file_url );
                        printf( "guid: %s\n", $file_guid );
                        if( $file_guid ) {
                            $wpdb->update( $wpdb->prefix . 'posts', array( 'guid' => $file_guid ), array( 'ID' => $file_id ) ); 
                        }
                        //$found_attachment_id = TST_Import::get_instance()->get_attachment_id_by_url( $file_url );
                        
                        // get url title
                        $file_name = TST_Import::get_instance()->get_file_name( $url, $post_content );
                        
                        // update attachment title
                        if( $file_name ) {
                            printf( "file name: %s\n", $file_name );
                            $attachment = array(
                                'ID'           => $file_id,
                                'post_title'   => $file_name,
                            );
                            wp_update_post( $attachment ); 
                        }
                        
                        TST_Import::get_instance()->set_file_date( $file_id, $url );
                        
                        // replace old url with new
                        $post_content = preg_replace( "/" . preg_quote( $url, '/' ) . "/", $file_url, $post_content );
                    }
                    else {
                        printf( "removed: %s\n", $url );
                        $post_content = TST_Import::get_instance()->remove_url_tag( $url, $post_content );
                        
                    }
                    
                    $post_content = TST_Import::get_instance()->remove_inline_styles( $post_content );
                    
                }
            }
            
            $parent_post = $parent_url ? TST_Import::get_instance()->get_post_by_old_url( $parent_url ) : NULL;
            $exist_post = $page_url ? TST_Import::get_instance()->get_post_by_old_url( $page_url ) : NULL;
            
            if( $localpdf ) {
                $post_content = TST_Import::get_instance()->replace_file_type_hints( $post_content ); 
            }
            
            $parent_post_id = $parent_post ? $parent_post->ID : 0;
//            printf( "parent_url: %s\n", $parent_url );
//            printf( "parent_id: %d\n", $parent_post_id );
            
			$post_arr = array(
				'ID' => $exist_post ? $exist_post->ID : 0,
				'post_title' 	=> $post_title,
				'post_type' 	=> $post_type,
				'post_status' 	=> 'publish',
				'meta_input'	=> array(
					'old_url' => $page_url,
					'post_files'   => maybe_serialize( $post_files ),
				),
				'post_content' => $post_content,
				'post_excerpt' => '',
                'post_parent' => $parent_post_id,
			);
            
            if( $post_date ) {
                $post_arr['post_date'] = $post_date;
            }

            $post_id = wp_insert_post($post_arr);
            
            foreach( $post_files as $file ) {
                $attachment_id = $file['id'];
                p2p_type( 'import_attachments' )->connect( $post_id, $attachment_id );
                TST_Import::get_instance()->set_attachment_old_page_url( $attachment_id, $page_url );
            }
            
            unset( $line );
            
			wp_cache_flush();
			$count++;
            
 		}
	}

	printf( "Posts imported: %n\n", $count );

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