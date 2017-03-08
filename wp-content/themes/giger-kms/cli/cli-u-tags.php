<?php
/** Update post_type/term structure 
 *
 *  landings
 *  
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    tst_cli_tags_add_landing_tags();
    tst_cli_tags_connect_posts_to_landing_with_tags();
    tst_cli_tags_connect_attachments_to_landing_with_tags();
    
    wp_cache_flush();

    //Cleanup
    echo 'Flush rewrite rules'.chr(10);
    flush_rewrite_rules(false);


    //Final
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

function tst_cli_tags_add_landing_tags() {
    
    $time_start = microtime(true);
    
    global $wpdb;
    
    $landing_count = 0;
    
    $args = array(
        'post_type' => array( 'landing' ),
        'posts_per_page' => -1,
        'fields'    => 'ids',
    );
    $landings = get_posts( $args );
    
    foreach( $landings as $landing_id ) {
    
        $landing = get_post( $landing_id );
    
        $tag_name = $landing->post_title;
    
        $landing_tag = get_term_by( 'slug', $landing->post_name, 'post_tag' );
        if( !$landing_tag ) {
            $landing_tag_ret = wp_insert_term( $landing->post_title, 'post_tag', array( 'slug' => $landing->post_name ) );
            $landing_tag = get_term_by( 'id', $landing_tag_ret['term_id'], 'post_tag' );
        }
    
        $landing_tag_id = $landing_tag->term_id;
        wp_set_object_terms( $landing->ID, array( $landing_tag_id ), 'post_tag', true );
    
        $landing_count++;
    
        printf( "%d - tag: %d\n", $landing->ID, $landing_tag_id );
    }
    
    echo $landing_count." landing pages processed. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);
}

function tst_cli_tags_connect_posts_to_landing_with_tags() {
    $time_start = microtime(true);
    
    global $wpdb;
    
    $post_types = array( 'post', 'archive_page' );
    
    $tags = get_terms( array( 
        'taxonomy' => 'post_tag', 
        'hide_empty' => false, 
    ) );
    
    $posts_tags = array();
    
    foreach( $tags as $tag ) {
        
        $args = array(
            'post_type' => $post_types,
            'fields'    => 'ids',
            'posts_per_page' => -1,
            's' => $tag->name,
        );
        $posts = get_posts( $args );
        
        foreach( $posts as $post_id ) {
            
            if( !isset( $posts_tags[$post_id] ) ) {
                $posts_tags[$post_id] = array();
            }
            $posts_tags[$post_id][] = $tag->term_id;
            
        }
        
        printf( "tag: %s, posts: %d\n", $tag->name, count( $posts ) );
    }
    
    printf( "setting tags for %d posts...\n", count( $posts_tags ) );
    
    foreach( $posts_tags as $post_id => $tags_id ) {
        wp_set_object_terms( $post_id, $tags_id, 'post_tag', true );
//         printf( "%d - tag: %s\n", $post->ID, implode( ', ', $tags_id ) );
    }
    
    echo count( $posts_tags )." posts tagged. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);
}

function tst_cli_tags_connect_attachments_to_landing_with_tags() {
    $landing = tst_get_pb_post( 'dront-publications', 'landing' );
    
    $landing_tag = get_term_by( 'slug', $landing->post_name, 'attachment_tag' );
    if( !$landing_tag ) {
        $landing_tag_ret = wp_insert_term( $landing->post_title, 'attachment_tag', array( 'slug' => $landing->post_name ) );
        $landing_tag = get_term_by( 'id', $landing_tag_ret['term_id'], 'attachment_tag' );
    }
    $landing_tag_id = $landing_tag->term_id;
    wp_set_object_terms( $landing->ID, array( $landing_tag_id ), 'attachment_tag', true );
    
    $publications = array( 'datt-chto-takoe-prirodoohrannoe-dvizhenie', 'datt-mir-mezhdu-dvuh-ekologiy', 'datt-factor_four', 'datt-limits_to_growth', 'datt-zhitkov_buturlin', 'datt-serebrovsky-1918', 'datt-1915' );
    foreach( $publications as $pub_name ) {
        $pub = tst_get_pb_post( $pub_name, 'attachment' );
        if( $pub ) {
            wp_set_object_terms( $pub->ID, array( $landing_tag_id ), 'attachment_tag', true );
        }
    }
    
    printf( "publications attachments connected to publicataions landing - ok\n" );
}