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

    $input_file = get_template_directory() . '/cli/data/team.csv';
    printf( "Processing %s\n", $input_file );

    $count = -1;

	if (($handle = fopen( $input_file, "r" )) !== FALSE) {
	    
	    $media = TST_Media::get_instance();

        $about_page_id = get_posts(array('post_type' => 'page', 'pagename' => 'about-us',));
        $about_page_id = reset($about_page_id)->ID;

        $team = array();

        if( !$about_page_id || !is_int($about_page_id) ) {
            echo "Error: 'about' page not found.\n";
            exit;
        }

		while(( $line = fgetcsv( $handle, 1000000 )) !== FALSE) {
		    $count++;
		    
		    if( $is_skip_first_line && !$count ) {
		        continue;
		    }

            $team_member = array('name' => trim($line[0]), 'position' => trim($line[1]), 'image_id' => '');

            $attachment_id = 0;
            if($line[2]) {
                $line[2] = get_template_directory() . '/cli/data/team_images/'.$line[2];
                if(file_exists($line[2])) {
                    $team_member['image_id'] = tst_upload_img_from_path($line[2]);
                }
            }

            $team[] = $team_member;

 		}

        update_post_meta($about_page_id, 'team', $team);

        // Manually fix one wrongly cropped image:
        $image = wp_get_image_editor( 'cool_image.jpg' );
        if ( ! is_wp_error( $image ) ) {
            $image->rotate( 90 );
            $image->resize( 300, 300, true );
            $image->save( 'new_image.jpg' );
        }

	}

	printf( "\nTeam members imported: %d\n", $count );

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