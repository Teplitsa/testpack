<?php
/**
 * Set posts params or create post if not exist
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
    $updated_count = 0;
    $not_found_count = 0;
    $found_count = 0;
    $converted2pdf_count = 0;
    
    $options = getopt("", array('file:', 'localpdf::', 'convert2pdf::'));
    $input_file = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $input_file );
    
    $localpdf = isset( $options['localpdf'] ) ? True : False;
    $is_convert2pdf = isset( $options['convert2pdf'] ) ? True : False;
    
    $count = 0;
    $csv = array_map('str_getcsv', file( $input_file ));
    
    if (($handle = fopen( $input_file, "r" )) !== FALSE) {
    
        while(( $line = fgetcsv( $handle )) !== FALSE) {
    
            if( $count == 0 ) {
                $count++;
                continue;
            }
    
            $post_name = tst_clean_csv_slug( $line[2] );
            $documents_urls = tst_get_csv_noneable_val( $line[10] );
            $documents_urls = explode( ",", $documents_urls );
            
            $project = tst_get_pb_post( $post_name, 'project' );
            
            if( $project ) {
                
                $atts = array();
                foreach( $documents_urls as $doc_url ) {
                    $doc_url = tst_clean_csv_file_url( $doc_url );
                    if( $doc_url ) {
                        printf( "doc url: %s\n", $doc_url );
                        continue;
                        $doc_id = TST_Import::get_instance()->maybe_import( $doc_url );
                        if( $doc_id ) {
                            
                            $file_id = $doc_id;
                            
                            $url = $doc_url;
                            $file_url = wp_get_attachment_url( $file_id );
                            
                            if( $file_id && $is_convert2pdf ) {
                            
                                if( TST_Import::get_instance()->is_must_convert2pdf( $file_url ) ) {
                                    $converted2pdf_count += 1;
                            
                                    $pdf_file_id = TST_Import::get_instance()->convert2pdf( $file_id, $localpdf );
                                    if( $pdf_file_id ) {
                                        $file_id = $pdf_file_id;
                                        $file_url = wp_get_attachment_url( $file_id );
                                    }
                                }
                            }
                            
                            $atts[] = $file_id;
                        }
                    }
                }
                
                
                $project_content = $project->post_content;
                $atts_list = tst_get_project_attachments_list_as_content( $project, $atts );
                
                if( $atts_list && strpos( $project_content, 'projects-title-in-content' ) !== false ) {
                    $project_content = preg_replace( '/<h3 class="projects-title-in-content">.*/s', '', $project_content );
                }
                
                $project_content .= $atts_list;
                wp_update_post( array( 'ID' => $project->ID, 'post_content' => $project_content ) );
                $updated_count += 1;
                
                printf( "%s\n", $project->post_name );
                $found_count += 1;
            }
            else {
                printf( "not found: %s\n", $project_name );
                $not_found_count += 1;
            }
            
            unset( $line );
            unset( $project );
            wp_cache_flush();
        }
    }
                
	printf( "\nPosts processed: %d; updated - %d; not_found - %d\n", $found_count, $updated_count, $not_found_count );

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


function tst_get_project_attachments_list_as_content( $project, $atts ) {
    
    $out = '';
    if( !empty( $atts ) ) {
        ob_start();
        ?>
            <h3 class="projects-title-in-content">Документы: <?php echo $project->post_title ?></h3>
    
            <?php
            foreach( $atts as $att ) {
            ?>
                <p><a href="<?php echo wp_get_attachment_url( $att );?>"><?php echo get_the_title( $att );?></a></p>
            <?php
            }

        $out = ob_get_contents();
        ob_end_clean();
    }

    return $out;
}

