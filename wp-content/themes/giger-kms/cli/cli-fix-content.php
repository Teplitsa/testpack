<?php
/**
 * Fix links
*
**/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $updated_count = 0;
    
    $params = array(
        'post_type' => array( 'landing', 'project', 'post', 'import' ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $posts = get_posts( $params );
    
    foreach( $posts as $index => $post_id ) {
        $post = get_post( $post_id );
        if( $post ) {
            
            echo $post->ID . "\n";
            
            $post_content = tst_fix_post_inner_urls( $post->post_content );
            $post_content = tst_remove_css_js( $post_content );
            
            if( $post_content != $post->post_content ) {
                $post_data = array(
                    'ID'             => $post->ID,
                    'post_content'   => $post_content,
                );
                wp_update_post( $post_data );
                $updated_count += 1;
//                 break;
            }
        }
    }
    printf( "CONTENT fixed for %d posts\n", $updated_count );

    //regenerate permalinks
    flush_rewrite_rules();

    //Final
    echo "\n";
    echo 'Memory '.memory_get_usage(true).chr(10);
    echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
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

function tst_fix_post_inner_urls( $content ) {
    global $wpdb;
    
    $redirects_table_name = $wpdb->prefix . 'tst_redirects';
    
    $debug = true;
    
    if (!preg_match_all('/<a [^>]+>/', $content, $matches)){
        echo 'links: 0' . chr(10);
        return;
    }
    
    echo "links: ".count($matches[0]) . chr(10);
    $broken_count = 0;
    
    $is_any_url_changed = false;
    
    foreach( $matches[0] as $link ) {
        if($debug) {
            echo $link . chr(10);
        }
    
        if( preg_match('/http:\/\/dront.ru/i', $link ) ) {
    
            preg_match('/href="(.*?)"/i', $link, $s);
    
            if(isset($s[1])){ //we find href
    
                $found_old_url = $s[1];
                $old_url = preg_replace( '/http:\/\/dront.ru\//', '/', $found_old_url );
                
                $sql = "SELECT new_url FROM {$redirects_table_name} WHERE old_url = %s";
                $sql = $wpdb->prepare( $sql, $old_url );
                $new_url = $wpdb->get_var( $sql );
                
                if( $new_url ) {
                    $new_url = home_url( $new_url );
                }
                elseif( preg_match( '/(mailto:.*)/', $found_old_url, $matches ) ) {
                    $new_url = $matches[1];
                }
                
                if( $new_url ) {
                    $content = str_replace( $found_old_url, $new_url, $content);
                    $is_any_url_changed = true;
                    
                    //log
                    $r = array();
                    $r[] = $found_old_url;
                    $r[] = $new_url;
                    
                    $broken_count++;
                    
                    echo 'change: ' . $found_old_url . ' - '.$new_url.chr(10);
                }
                else {
                    echo "no new page found!\n";
                }
            }
            else {
                echo "new image file NOT FOUND: {$new_path}\n";
            }
        }
    }
    
    return $content;
}

function tst_remove_css_js( $post_content ) {
    $post_content = preg_replace( array(
        '@<style[^>]*?>.*?</style>@siu',
        '@<script[^>]*?.*?</script>@siu',
    ), array( ' ', ' ' ), $post_content );
    
    return $post_content;
}