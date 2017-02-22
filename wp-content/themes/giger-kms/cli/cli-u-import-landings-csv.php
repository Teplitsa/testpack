<?php
/**
 * Import landings and other basic site elements from CSV
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
            
//             print_r( $line );
//             continue;
            
            $index = $line[0];
            
            if( !(int)$index ) {
                continue;
            }
            
            $post_title = strip_tags( $line[1] );
            $slug = $line[2];
            $section = $line[3];
            $post_type = $line[4];
            $tagline = $line[5];
            $fulltext_file_name = $line[6];
            $details = $line[7];
            
            printf( "title: %s, slug: %s, type: %s\n", $post_title, $slug, $post_type );
            
            if( $post_type == "section archive" ) {
                
                if( $post_type == "section archive" ) {
                    $tax = 'section';
                }
                else {
                    $tax = $post_typel;
                }
                
                $term_exists = get_term_by( 'slug', $slug, $tax );
                if( $term_exists ) {
                    wp_update_term( $term_exists->term_id, $tax, array( 'name' => $post_title ) );
                }
                else {
                    $res = wp_insert_term( $post_title, $tax, array( 'slug' => $slug ) );
                    if(is_wp_error($res)){
                        echo $res->get_error_message();
                    }
                }
                
                continue;
            }
            
            $parent_post = NULL;
            $exist_post = $slug ? tst_get_pb_post( $slug, $post_type ) : NULL;
            
            $parent_post_id = $parent_post ? $parent_post->ID : 0;
            
            $fulltext_file_path = dirname( __FILE__ ) . '/data/txt/' . $fulltext_file_name;
            $post_content = file_exists( $fulltext_file_path ) ? file_get_contents( $fulltext_file_path ) : '';
            
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
            
            if( $post_type == 'landing' ) {
                $section_term = get_term_by( 'slug', $section, 'section' );
                if( $section_term ) {
                    wp_set_post_terms( $post_id, $section_term->term_id );
                }
                else {
                    printf( "section not found: %s\n", $section );
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