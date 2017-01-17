<?php
/** Export data about regions **/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
    
    include( dirname( __FILE__ ) . '/../admin/post-hooks.php');

    $per_page = 100;

    $options = getopt("", array('update:', 'post_id:', 'debug:'));
    $post_id = isset($options['post_id']) ? (int)$options['post_id'] : 0;
    $is_update = isset($options['update']) ? (int)$options['update'] : 0;
    $debug = isset($options['debug']) ? (int)$options['debug'] : 0;

    printf( "post_id=%d, debug=%d\n", $post_id, $debug );

    $posts_count = fix_images_step( $post_id, $is_update, $debug );
    $processed_posts = $posts_count;
    
    printf( "\nprocessed posts: %d\n", $processed_posts );

    print( "done\n" );

	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10);
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

function tst_image_path_from_url($url){
    $uploads = wp_upload_dir();

    $url = str_replace(array('https:', 'http:'), '', $url);
    $base_url = str_replace(array('https:', 'http:'), '', $uploads['baseurl']);
    $path = str_replace($base_url, $uploads['basedir'], $url);

    return $path;
}

function fix_images_step( $post_id = 0, $is_update = 0, $debug = 0 ) {
    /* Change broken internal images URL into working ones if there is valid original file
     * Update post in DB
     * Store changes in log */
    global $wpdb;
    
    $post_id = (int)$post_id;
    
    $sql = " SELECT p1.* FROM {$wpdb->posts} AS p1 JOIN ( SELECT * FROM {$wpdb->posts} WHERE post_type IN ('post')"
            . " AND post_status = 'publish'"
            . ($post_id ? " AND ID = $post_id" : "")
            . " ORDER BY post_date ASC"
            . " ) AS p2 ON p1.ID = p2.ID";
    
    $sql = $wpdb->prepare( $sql );
    $posts = $wpdb->get_results( $sql );

    $file = get_template_directory() . '/data/broken-images-report.csv';
    $home_test = str_replace( array('http:', 'https:'), '', home_url( '' ) );
    
    $posts_count = count($posts);
    
    $media = TST_Media::get_instance();

    if( count($posts) ){
        $csv_handler = fopen( $file, 'w' );
        fwrite( $csv_handler, chr(239) . chr(187) . chr(191) ); // add BOM

        $title = array('Broken', 'Fixed', 'Page');

        fputcsv($csv_handler, $title);
        $broken_count = 0;

        foreach($posts as $p){			

            printf( 'time: %s post_id: %d post_name: %s post_date: %s images: ' . chr(10), date( 'H:i:s' ), $p->ID, $p->post_name, substr($p->post_date, 0, 10), count( $all_post_images ) );
            if (!preg_match_all('/<img [^>]+>/', $p->post_content, $matches)){
                    echo '0' . chr(10);
            }

            echo count($matches[0]) . chr(10);

            $content = $p->post_content;
            $is_any_image_changed = false;

            $all_post_images = $matches[0];
            
            $thumbnail_url = '';
            $post_thumbnail_id = get_post_thumbnail_id( $p->ID );
            if( $post_thumbnail_id ) {
                $thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
            }
            $all_post_images[] = sprintf( ' src="%s" wp-image-%d ', $thumbnail_url, $post_thumbnail_id );
            
            if( $thumbnail_url ) {
                echo '+thumbnail' . chr(10);
            }
            
            foreach( $all_post_images as $image ) {
                
                if($debug) {
                    echo $image . chr(10);
                }
                
                if(false === strpos($image, $home_test)) {
                    if($debug) {
                        echo "external page!\n";
                    }
                    continue;
                }

                //check if it's broken
                if(preg_match('/wp-image-([0-9]+)/i', $image, $class_id ) && ($attachment_id = absint($class_id[1]))) {

                    if($debug) {
                        echo "-1- WP image HTML found\n";
                        echo absint($class_id[1]) . "\n";
                    }

                    //local image with original
                    preg_match('/src="(.*?)"/i', $image, $s);

                    if(isset($s[1])){ //we find $src
                        if($debug) {
                            echo "-2- image SRC found\n";
                        }

                        $img_path = tst_image_path_from_url($s[1]); //path for image in text
                        if($debug) {
                            echo "image_path: $img_path\n";
                        }

                        if(!file_exists($img_path)){// it's broken!
                            if($debug) {
                                echo "-2- image file not found\n";
                            }
                            
                            if( $is_update ) {
                                $media->localize_attachment( $attachment_id );
                                printf( "remote image downloaded\n" );
                            }
                            $new_src = wp_get_attachment_image_src($attachment_id, 'large');
                            
                            if( $debug || $is_update ) {
                                echo "new_src: ".( isset( $new_src[0] ) ? $new_src[0] : "" ) . "\n";
                            }

                            if($new_src[0]){
                                $new_path = tst_image_path_from_url( $new_src[0] ); //path for new image

                                if($debug) {
                                    echo "-4- new image URL generated\n";
                                    echo "new_path: ". $new_path . "\n";
                                }
                                
                                $broken_img = tst_norm_img_url( $s[1] );
                                
                                $fixed_img = file_exists($new_path) ? $new_src[0] : '';
                                $fixed_img = $fixed_img ? tst_norm_img_url( $fixed_img ) : '';
                                
                                $is_any_image_changed = true;
                                
                                if( $thumbnail_url == $broken_img ) {
                                    
                                }

                                //log 
                                $r = array();
                                $r[] = $broken_img;
                                $r[] = $fixed_img;
                                $r[] = get_permalink($p);

                                fputcsv($csv_handler, $r);
                                $broken_count++;

                                echo 'broken: '.$broken_img.' - '.$broken_count.chr(10);
                            }							
                        }
                    }
                }
            } // images loop
            
        } // posts loop

        fclose($csv_handler);
        printf( "broken images found: %d\n", $broken_count );
        update_option('tst_broken_count', $broken_count);
    }
    else { //print stats
            echo "No posts to process\n";
    }	
    
    unset( $posts );
    unset( $csv_handler );
    
    return $posts_count;
}

function tst_norm_img_url( $img_url ) {
    $img_url = str_replace(array('http:', 'https:'), '', $img_url);
    $img_url = (is_ssl() ? 'https:' : 'http:') . $img_url;
    return $img_url;
}

function tst_get_old_site_image_src( $img_path ) {
    
}
