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
            $slug = $line[2];
            $parent_project = $line[3] == 'none' ? '' : $line[3];
            $thumbnail_url = $line[4];
            $menu_order = $line[5];
            $tags = $line[6];
            
            $department = $line[7] == 'none' ? '' : $line[7];
            $direction = $line[8] == 'none' ? '' : $line[8];
            $problem = $line[9] == 'none' ? '' : $line[9];
            $documents_urls = $line[10];
            $documents_urls = explode( ",", $documents_urls );
            
            printf( "title: %s, slug: %s, type: %s\n", $post_title, $slug, $post_type );
            
            $parent_post = $parent_project ? get_page_by_path( $parent_project, OBJECT, $parent_project ) : NULL;
            $exist_post = $slug ? get_page_by_path( $slug, OBJECT, $post_type ) : NULL;
            
            $parent_post_id = $parent_post ? $parent_post->ID : 0;
            printf( "parent_id: %d\n", $parent_post_id );
            
            $fulltext_file_path = dirname( __FILE__ ) . '/' . $fulltext_file_name;
            
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
				'post_excerpt' => $tagline,
                'post_parent' => $parent_post_id,
			);
			
// 			print_r( $post_arr );
            
            $post_id = wp_insert_post($post_arr);
            
            if( $post_id ) {
                if( $department ) {
                    update_post_meta( $post_id, 'landing_department', $department );
                }
                
                if( $direction ) {
                    update_post_meta( $post_id, 'landing_direction', $direction );
                }
                
                if( $problem ) {
                    update_post_meta( $post_id, 'landing_problem', $problem );
                }
                
                printf( "thumbnail_url: %s\n", $thumbnail_url );
                $thumbnail_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
                if( $thumbnail_id ) {
                    set_post_thumbnail( $post_id, $thumbnail_id );
                }
                
                foreach( $documents_urls as $doc_url ) {
                    $doc_url = trim( $doc_url );
                    $doc_id = TST_Import::get_instance()->maybe_import( $doc_url );
                    if( $doc_id ) {
                        p2p_type( 'connected_attachments' )->connect( $post_id, $doc_id );
                    }
                }
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