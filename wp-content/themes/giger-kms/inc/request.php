<?php
/**
 * Request corrections
 *
 **/

/** Project archive custom ordering */
add_action('parse_query', 'tst_project_archive_ordering');
function tst_project_archive_ordering(WP_Query $query){

    if(is_admin()) {
        return;
    }

    if($query->is_post_type_archive('project') && $query->is_main_query()) {

        $query->set('meta_query', array(
            array(
                array(
                    'key'     => 'exclude-from-archive',
                    'value'   => true,
                    'compare' => '!=',
                ),
            ),
        ));

        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'archive-priority-output');
//        $query->set('meta_value', 'vyvodit-vverhu-spiska');

    }

}

/* CPT Filters */
add_action('parse_query', 'tst_request_corrected');
function tst_request_corrected(WP_Query $query) {

	$doing_ajax = (defined( 'DOING_AJAX' ) && DOING_AJAX);

	if(is_admin() && !$doing_ajax) { //test this
		return;
	}

	if($query->is_main_query()) {
		if($query->is_tax('section')) {
			$query->set('post_type', array('landing', 'page', 'leyka_campaign'));
			$query->set('post_parent', 0);
			$query->set('posts_per_page', -1);
			$query->set('orderby', array('menu_order' => 'DESC', 'date' => 'DESC'));
		}
	}

}

/** == Rewrites and links filters == **/

add_action('init', 'tst_custom_rewrites', 25);
function tst_custom_rewrites(){
	global $wp;

	//clean url for sections
	$slugs = get_terms(array('taxonomy' => 'section', 'hide_empty'=> 0, 'fields' => 'id=>slug'));
	$slugs = implode('|', $slugs);

	add_rewrite_rule('^('.$slugs.')/?$', 'index.php?section=$matches[1]', 'top');
	add_rewrite_rule('^('.$slugs.')/page/?([0-9]{1,})/?$', 'index.php?section=$matches[1]&paged=$matches[2]', 'top');
	add_rewrite_rule('^('.$slugs.')/(feed|rdf|rss|rss2|atom)/?$', 'index.php?section=$matches[1]&feed=$matches[2]', 'top');

	// urls for landing about
	add_rewrite_rule('item/([^/]+)/about/?$', 'index.php?landing=$matches[1]&item_about=1', 'top');
	add_rewrite_rule('item/([^/]+)/archive/?$', 'index.php?landing=$matches[1]&item_archive=1', 'top');


	//should we flush permalinks
	if($slugs != get_option('_tst_section_slugs') ) {

		flush_rewrite_rules(false);
		update_option('_tst_section_slugs', $slugs);
	}

	//add custom qv
	$wp->add_query_var('item_about');
	$wp->add_query_var('item_archive');
}

/* filters sections term link **/
add_filter('term_link', 'tst_filter_section_link', 2, 3);
function tst_filter_section_link($termlink, $term, $taxonomy) {

	if($taxonomy != 'section')
		return $termlink;

	$termlink = str_replace('/section', '', $termlink);
	return $termlink;
}

/** filter CPT url for dates **/
add_filter('post_type_link', 'tst_filter_post_types_link', 2, 2);
add_filter('post_link', 'tst_filter_post_types_link', 2, 2);
function tst_filter_post_types_link($link, $post) {

	if(!in_array($post->post_type, array('event')) || false === strpos($link, '%year%'))
		return $link;

	$y = get_the_date('Y', $post->ID);
	$m = get_the_date('m', $post->ID);
	$d = get_the_date('d', $post->ID);

	if(!$y || !$m || !$d)
		return $link;

	$link = str_replace('%year%', $y, $link);
	$link = str_replace('%monthnum%', $m, $link);
	$link = str_replace('%day%', $d, $link);

	return $link;
}


/** filter to make url shorter - test it doesn' work **/
//add_filter( 'wp_unique_post_slug', 'tst_custom_unique_post_slug', 15, 4 );
function tst_custom_unique_post_slug( $slug, $post_ID, $post_status, $post_type ) {
    $post = get_post($post_ID);

	if(!in_array($post_type, array('post', 'report', 'news', 'event', 'page', 'project', 'ngo_profile')))
		return $slug;


    if(empty($post->post_name) || $slug != $post->post_name ) {
		$test_slug = sanitize_title($post->post_title);

		if($test_slug == $slug && strlen($slug) > 20){
			$slug = substr($test_slug, 0, 15);
			$slug = str_replace(array('-', '_'), '', $slug);
		}
    }


    return $slug;
}

/** == Custom templates == **/
add_filter('template_include', 'tst_template_include_correction', 10);
function tst_template_include_correction($template) {

	$test = get_query_var('item_about');
	if($test && $test == 1) {
		$template = get_template_directory().'/single-landing.php';
	}

	return $template;
}


/** == Redirects == **/

/* redirect default section urls **/
add_action('template_redirect', 'tst_section_redirects', 5);
function tst_section_redirects() {

    if( !is_tax('section') ) {
        return;
    }

    $str_to_lookup = $_SERVER['REQUEST_URI'];
	if(false != strpos($str_to_lookup, 'section')){
		$redirect = str_replace('section/', '', $str_to_lookup);
		wp_redirect(home_url($redirect), 301);
        die();
	}

}


/* redirects fr urls of the old site **/
add_action('template_redirect', 'tst_pages_redirect', 5);
function tst_pages_redirect() {
    global $wp_query, $wpdb;
    
//     if( !$wp_query->is_404 ) {
//         return;
//     }
    
    global $wp_query;
    global $wp;
    
    $args = $wp_query->query_vars;
    $request_uri_with_query = $_SERVER['REQUEST_URI'] . ( $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
    $request_uri_with_query = preg_replace( '/^\/dront2\//', '/', $request_uri_with_query, 1 );
    $is_debug = false;
    
    if( $is_debug ) {
        echo $request_uri_with_query . "<br />\n";
    }
    
    $redirect = '';
    
    if( !$redirect ) {
        $redirects_table_name = $wpdb->prefix . 'tst_redirects';
        $sql = "SELECT new_url FROM {$redirects_table_name} WHERE old_url = %s";
        $sql = $wpdb->prepare( $sql, $request_uri_with_query );
        $new_url = $wpdb->get_var( $sql );
        if( $new_url ) {
            $redirect = home_url( $new_url );
        }
    }
    
    if( !$redirect ) {
        $redirect = TST_URL::get_custom_redirect( $request_uri_with_query );
    }
    
    $return_404 = false;
    
    if( preg_match( '/\/eta-stranitsa-byla-udalena/', $redirect ) ) {
        $return_404 = true;
        
        if( $is_debug ) {
            echo "<br /><br />return 404<br />"; exit();
        }
    }
    
    if( $is_debug ) {
        echo "<br /><br />redirect: " . $redirect . "<br />"; exit();
    }
    
    if(!empty($redirect)){
        
        if( $return_404 ) {
            header("HTTP/1.0 404 Not Found");
            $wp_query->set_404();
        }
        else {
            wp_redirect($redirect, 301);
            die();
        }
    }
    
}

/** Helper to detect current page position from query **/
function tst_get_current_page_for_query(WP_Query $query) {
    return isset($query->query_vars['paged']) && $query->query_vars['paged'] > 1 ? (int)$query->query_vars['paged'] : 1;
}

// add next page detection for load more actions
add_filter('found_posts', 'tst_request_corrected_after_get_posts', 2, 2);
function tst_request_corrected_after_get_posts($found_posts, WP_Query $query) {
    //detect next page for load more request
    $query = tst_request_correction_has_next_page($query);
    return $found_posts;
}

/** after geeting posts detect if we have more posts to load **/
function tst_request_correction_has_next_page(WP_Query $query) {

    $query->set('has_next_page', 0);

    $current = tst_get_current_page_for_query($query);
    $per_page = $query->get('posts_per_page', get_option('posts_per_page'));
    $displayed = 0;

    if(isset($query->query_vars['offset']) && $query->query_vars['offset'] > 0){
        $offset = (int)$query->query_vars['offset'];
        $displayed = $offset + $per_page * ($current - 1);
    }
    else {
        $displayed = $current * $per_page;
    }

    if($displayed < $query->found_posts)
        $query->set('has_next_page', 1);

    return $query;
}
