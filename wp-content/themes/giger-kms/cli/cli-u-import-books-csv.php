<?php
/**
 * Tweaks for about section itmes - projects, pubs, reports
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $is_skip_first_line = True;
    
    $time_start = microtime(true);
    include('cli_common.php');

    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $input_file = get_template_directory() . '/cli/data/books.csv';
    printf( "Processing %s\n", $input_file );

    $count = -1;

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {
	    
	    $media = TST_Media::get_instance();
	     
		while(( $line = fgetcsv( $handle, 1000000 )) !== FALSE) {
		    $count++;
		    
		    if( $is_skip_first_line && !$count ) {
		        continue;
		    }
            
		    $post_title = $line[0];
            $post_content = $line[1];
            $post_date = $line[2];
            $post_author = $line[3];
            $post_img = $line[4];
            $book_pdf = $line[5];
            
            $post_title = trim( strip_tags( $post_title ) );
            $post_content = wpautop( $post_content );
            $post_date = $post_date ? date( 'Y-m-d H:i:s', strtotime( $post_date ) ) : current_time( 'mysql' );
            $post_author = trim( strip_tags( $post_author ) );
            
            $attachment_id = 0;
            if( $post_img ) {
                $post_img = get_template_directory() . '/cli/data/books_images/' . $post_img;
                if( file_exists( $post_img ) ) {
                    $attachment_id = tst_upload_img_from_path( $post_img );
                }
            }
            
            $book_att_id = 0;
            if( $book_pdf ) {
                $book_pdf = get_template_directory() . '/cli/data/books_images/' . $book_pdf;
                if( file_exists( $book_pdf ) ) {
                    $book_att_id = tst_upload_img_from_path( $book_pdf );
                }
            }
            
            $book_meta = array(
                'book_author' => $post_author,
            );
            
            if( $book_att_id ) {
                $book_meta['book_att_id'] = $book_att_id;
            }
            
            $post_arr = array(
                'post_title' 	=> $post_title,
                'post_type' 	=> 'book',
                'post_status' 	=> 'publish',
                'post_content' => $post_content,
                'post_excerpt' => '',
                'meta_input' => $book_meta,
            );
            
            if( $post_date ) {
                $post_arr['post_date'] = $post_date;
            }

            $post_id = wp_insert_post($post_arr);
            
            $is_img = false;
            if( $post_id && $attachment_id ) {
                set_post_thumbnail( $post_id, $attachment_id );
                $is_img = true;
            }
            
            printf( "imported: %s, img: %s, file: %s\n", $post_title, ($is_img ? 'YES' : 'no'), ($book_att_id ? 'YES' : 'no') );
            
            unset( $line );
            
            wp_cache_flush();
 		}
	}

	printf( "\nBooks imported: %d\n", $count );

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