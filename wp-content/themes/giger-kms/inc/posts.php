<?php
/**
 * Get posts funcitons
 *
 **/

function tst_get_latest_publications( $year = '', $num = -1, $fields = false ) {
    
    $params = array(
        'post_type' => 'attachment',
        'tax_query' => array(
            array(
                'taxonomy' => 'attachment_tag',
                'field' => 'slug',
                'terms' => 'publication'
            )
        ),
        'posts_per_page' => $num,
    );
    
    $year = is_numeric( $year ) ? (int) $year : 0;
    if( !$year ) {
        $year = (int)date('Y');
    }
    
    if( $year > 0 ) {
        $params['date_query'] = array(
            array(
                'year' => $year,
            ),
        );
    }
    
    if( $fields ) {
        $params['fields'] = $fields;
    }
    
    $posts = get_posts( $params );

	return $posts;
}

function tst_get_latest_news($num = 4) {
    return get_posts(array( 'post_type' => 'post', 'posts_per_page' => $num, 'post_status' => 'publish' ));
}