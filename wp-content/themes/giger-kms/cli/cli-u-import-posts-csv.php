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
    include( get_template_directory() . '/inc/class-import.php' );    
    
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;
	$uploads = wp_upload_dir();
    
    $options = getopt("", array('file:'));
    
    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );

	$count = 0;
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
            
//            print_r( $files_url );
            printf( "%s: %s\n", $post_type, $post_title );
            printf( "files: %d\n", count( $files_url ) );
            
			$file_url = '';
			$file_id = 0;
            $post_files = [];

			printf( "Importing %s\n", $page_url );

            foreach( $files_url as $url ) {
                if(false !== strpos($url, 'dront.ru')){
                    
                    $exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $url );

                    if( $exist_attachment ) {
                        $file_id = $exist_attachment->ID;
                        $file_url = wp_get_attachment_url($file_id);

                        printf( "Exist %s\n", $file_url );
                    }
                    else {

                        $attachment_id = TST_Import::get_instance()->import_file( $url );

                        if( $attachment_id ) {
                            $file_url = wp_get_attachment_url( $attachment_id );
                            printf( "File saved %s\n", $file_url );
                        }
                        else {
                            printf( "IMPORT ERROR\n");
                        }
                    }
                    
                    if( $file_url ) {
                        $post_content = preg_replace( "/" . preg_quote( $url, '/' ) . "/", $file_url, $post_content );
                    }
                    else {
                        $post_content = TST_Import::get_instance()->remove_url_tag( $url, $post_content );
                    }
                    
                    $post_content = TST_Import::get_instance()->remove_inline_styles( $post_content );
                    
                }
            }
            
			$post_arr = array(
				'ID' => 0,
				'post_title' 	=> $post_title,
				'post_type' 	=> $post_type,
				'post_status' 	=> 'publish',
				'meta_input'	=> array(
					'old_url' => $page_url,
					'post_files'   => maybe_serialize( $post_files ),
				),
				'post_content' => $post_content,
				'post_excerpt' => '',
			);
            
            if( $post_date ) {
//                $post_arr['post_date'] = $post_date;
            }

			$post_id = wp_insert_post($post_arr);
            
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