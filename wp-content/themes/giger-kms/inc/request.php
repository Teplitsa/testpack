<?php
/**
 * Request corrections
 *
 **/

/* CPT Filters */
add_action('parse_query', 'tst_request_corrected');
function tst_request_corrected(WP_Query $query) {

	$doing_ajax = (defined( 'DOING_AJAX' ) && DOING_AJAX);

	if(is_admin() && !$doing_ajax) { //test this
		return;
	}

	if($query->is_main_query()) {
		if($query->is_tax('section') && !$query->is_tax('section', 'news')) {
			$query->set('post_parent', 0);
			$query->set('posts_per_page', -1);
			$query->set('orderby', array('menu_order' => 'DESC', 'date' => 'DESC'));
		}
		elseif( isset( $query->query['post_type'] ) && $query->query['post_type'] == 'story' ) {
		    $query->set('posts_per_page', -1);
		    $query->set('orderby', array( 'date' => 'DESC' ));
		}
	}


}

// add next page detection for load more actions
add_filter('found_posts', 'tst_request_corrected_after_get_posts', 2, 2);
function tst_request_corrected_after_get_posts($found_posts, WP_Query $query) {
    //detect next page for load more request
    $query = tst_request_correction_has_next_page($query);
    return $found_posts;
}

function tst_get_current_page_for_query(WP_Query $query) {
    return (isset($query->query_vars['paged']) && $query->query_vars['paged'] > 1) ? (int)$query->query_vars['paged'] : 1;
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


	//should we flush permalinks
	if($slugs != get_option('_tst_section_slugs') ) {

		flush_rewrite_rules(false);
		update_option('_tst_section_slugs', $slugs);
	}


}

/* filters sections term link **/
add_filter('term_link', 'tst_filter_section_link', 2, 3);
function tst_filter_section_link($termlink, $term, $taxonomy) {

	if($taxonomy != 'section')
		return $termlink;

	$termlink = str_replace('/section', '', $termlink);
	return $termlink;
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


}
