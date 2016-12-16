<?php
/**
 * Tweaks for about section itmes - projects, pubs, reports
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;
	$uploads = wp_upload_dir();
    $input_file = isset( $argv[2] ) ? $argv[2] : '';
    print_r( $input_file );

	$count = 0;
	$csv = array_map('str_getcsv', file( $input_file ));

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {

		while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
            
//            print_r( $line );
            
//			if($i == 0)
//				continue;

			$post_type = 'post'; #$line[0];
            $page_url = $line[1];
            $post_title = strip_tags( $line[2] );
            $post_content = $line[3];
            $post_date = $line[4];
            $post_files_string = $line[5];
            $files_url = explode( '|', $post_files_string );
            
//            print_r( $files_url );
            printf( "%s: %s\n", $post_type, $post_title );
            printf( "files: %d\n", count( $files_url ) );
            
			$file_url = '';
			$file_id = 0;
            $post_files = [];

			echo "Importing " . $line[1].chr(10);

            foreach( $files_url as $url ) {
                if(false !== strpos($url, 'dront.ru')){
                    //remote
                    $file = wp_remote_get($url, array('timeout' => 50, 'sslverify' => false));
                    
                    $response_code = $file['response']['code'];
                    
                    if(!is_wp_error($file) && isset($file['body']) && $response_code == '200' ){
                        if(isset($file['headers']['content-type'])) {

                            $filename = basename($url);
                            $upload_file = wp_upload_bits($filename, null, $file['body']);

                            if (!$upload_file['error']) {
                                $wp_filetype = wp_check_filetype($filename, null );

                                $attachment = array(
                                    'post_mime_type' => $wp_filetype['type'],
                                    'post_parent' => 0,
                                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                                    'post_content' => '',
                                    'post_status' => 'inherit'
                                );

                                $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

                                if (!is_wp_error($attachment_id)) {
                                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                                }

                                $file_id = $attachment_id;
                                $file_url = wp_get_attachment_url($file_id);
                                
                                $post_files[] = array(
                                    'file_id' => $file_id,
                                    'file_url' => $file_url,
                                );
                            }
                        }
                    }
                    unset($file);
                    
                    if( $response_code == '200' ) {
                        $post_content = preg_replace( "/" . preg_quote( $url, '/' ) . "/", $file_url, $post_content );
                    }
                    else {
                        printf( "SKIP FILE STATUS: %s\n", $response_code );
                    }
                }
            }
            
			$post_arr = array(
				'ID' => 0,
				'post_title' 	=> $post_title,
				'post_type' 	=> $post_type,
				'post_status' 	=> 'publish',
				'meta_input'	=> array(
					'old_page_url' => $page_url,
					'post_files'   => maybe_serialize( $post_files ),
				),
				'post_content' => $post_content,
				'post_excerpt' => '',
			);
            
            if( $post_date ) {
//                $post_arr['post_date'] = $post_date;
            }

			$post_id = wp_insert_post($post_arr);
//			if($post_id && !is_wp_error($post_id))
//				wp_set_object_terms($post_id, $project_cats['publications']['term_id'], $tax);

			wp_cache_flush();
			$count++;
            
		}
	}

	echo 'Pubs moved into Publications term: '.$count.chr(10);

//	//Editorial settings for publications widget
//	$query = new WP_Query(array(
//		'post_type' => array('project'),
//		'post_status' => 'publish',
//		'posts_per_page' => 2,
//		'cache_results' => false,
//		'orderby' => 'ID',
//		'order' => 'ASC',
//		'update_post_meta_cache' => false,
//		'update_post_term_cache' => false,
//		'no_found_rows' => true,
//		'suppress_filters' => true,
//		'fields' => 'ids',
//		'post_name__in' => array('rasskazat-ob-nko-zachem-komu-i-kak', 'kak-povysit-izvestnost-svoej-nko')
//	));
//
//	if($query->have_posts())
//		set_theme_mod('selected_publications', implode(',', $query->posts));

	echo 'Selected publications stored'.chr(10);


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