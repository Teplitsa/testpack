<?php
/**
 * Import projects and it's connections to landings from CSV
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

    $options = getopt("", array('file:'));

    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );

    $count = 0;
    $csv = array_map('str_getcsv', file( $input_file ));

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle )) !== FALSE) {
		    
		    if( $count == 0 ) {
		        $count++;
		        continue;
		    }
            
            $post_title = trim( strip_tags( $line[0] ) );
            $post_content = strip_tags( $line[1] );
            $post_type = 'project';
            $slug = tst_clean_csv_slug( $line[2] );
            $parent_project = tst_get_csv_noneable_val( $line[3] );
            $thumbnail_url = tst_get_csv_noneable_val( $line[4] );
            $menu_order = trim( $line[5] );
            $tags = trim( $line[6] );
            
//             print_r($line);
            
            $department = tst_get_csv_noneable_val( $line[7] );
            $direction = tst_get_csv_noneable_val( $line[8] );
            $problem = tst_get_csv_noneable_val( $line[9] );
            $documents_urls = tst_get_csv_noneable_val( $line[10] );
            
            $documents_urls = explode( ",", $documents_urls );
            
            printf( "title: %s, slug: %s, type: %s\n", $post_title, $slug, $post_type );
            
            $parent_post = $parent_project ? tst_get_pb_post( $parent_project, $post_type ) : NULL;
            $exist_post = $slug ? tst_get_pb_post( $slug, $post_type ) : NULL;
            if( $exist_post ) {
                print( "project EXISTS!!!\n" );
            }
            
            $parent_post_id = $parent_post ? $parent_post->ID : 0;
            printf( "parent_id: %d\n", $parent_post_id );
            
            if( preg_match( '/^import\s*\|\s*(.*)$/', $post_content, $matches ) ) {
                $external_page_url = trim( $matches[1] );
                
                printf( "external_page_url: %s\n", $external_page_url );
                
                $import_post = TST_Import::get_instance()->get_post_by_old_url( $external_page_url );
                
                printf( "import_post with content: %d\n", $import_post ? $import_post->ID : 0 );
                
                $post_content = $import_post ? $import_post->post_content : '';
            }
            
			$post_arr = array(
				'ID' => $exist_post ? $exist_post->ID : 0,
				'post_title' 	=> $post_title,
				'post_type' 	=> $post_type,
			    'post_name' 	=> $slug,
				'post_status' 	=> 'publish',
				'post_content' => $post_content,
				'post_excerpt' => '',
                'post_parent' => $parent_post_id,
			);
			
// 			print_r( $post_arr );
            
            $post_id = wp_insert_post($post_arr);
            
            if( $post_id ) {
                
                if( $department ) {
                    $landing = tst_get_pb_post( $department, 'landing' );
                    if( $landing ) {
                        if( p2p_connection_exists( 'landing_project', array( 'from' => $landing->ID, 'to' => $post_id ) ) ) {
                            printf( "connection exist - DEPARTMENT\n" );
                        }
                        else {
                            printf( "create connection - DEPARTMENT\n" );
                            p2p_type( 'landing_project' )->connect( $landing->ID, $post_id, array( 'type' => 'department' ) );
                        }
                    }
                }
                
                if( $direction ) {
                    $landing = tst_get_pb_post( $direction, 'landing' );
                    if( $landing ) {
                        if( p2p_connection_exists( 'landing_project', array( 'from' => $landing->ID, 'to' => $post_id ) ) ) {
                            printf( "connection exits - DIRECTION\n" );
                        }
                        else {
                            printf( "create connection - DIRECTION\n" );
                            p2p_type( 'landing_project' )->connect( $landing->ID, $post_id, array( 'type' => 'direction' ) );
                        }
                    }
                }
                
                if( $problem ) {
                    $landing = tst_get_pb_post( $problem, 'landing' );
                    if( $landing ) {
                        if( p2p_connection_exists( 'landing_project', array( 'from' => $landing->ID, 'to' => $post_id ) ) ) {
                            printf( "connection exits - PROBLEM\n" );
                        }
                        else {
                            printf( "create connection - PROBLEM\n" );
                            p2p_type( 'landing_project' )->connect( $landing->ID, $post_id, array( 'type' => 'problem' ) );
                        }
                    }
                }
                
                printf( "thumbnail_url: %s\n", $thumbnail_url );
                if( $thumbnail_url ) {
                    if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
                        $thumbnail_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
                    }
                    else {
                        $filename = dirname( __FILE__ ) . '/sideload/' . $thumbnail_url;
                        printf( "local file: %s\n", $filename );
                        $thumbnail_id = TST_Import::get_instance()->maybe_import_local_file( $filename );
                        printf( "thumbnail_id=%s\n", $thumbnail_id );
                    }
                }
                if( $thumbnail_id ) {
                    printf( "set post thumbnail: %d\n", $thumbnail_id );
                    set_post_thumbnail( $post_id, $thumbnail_id );
                    wp_update_attachment_metadata( $thumbnail_id, wp_generate_attachment_metadata( $thumbnail_id, get_attached_file( $thumbnail_id ) ) );
                }
                
                //add tags
                if( !empty( $tags ) && $tags != 'none' ) {
                    wp_set_post_terms((int)$post_id, $tags, 'project_cat', false);
                    wp_cache_flush();
                }
                
//                 foreach( $documents_urls as $doc_url ) {
//                     $doc_url = tst_clean_csv_file_url( $doc_url );
//                     if( $doc_url ) {
//                         printf( "doc url: %s\n", $doc_url );
//                         $doc_id = TST_Import::get_instance()->maybe_import( $doc_url );
//                         if( $doc_id ) {
//                             p2p_type( 'connected_attachments' )->connect( $post_id, $doc_id );
//                         }
//                     }
//                 }
            }
            
            unset( $line );
            unset( $post_arr );
            
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

function tst_get_csv_noneable_val( $val ) {
    return trim( $val ) == 'none' ? '' : trim( $val );
}