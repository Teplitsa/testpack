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
		if($query->is_tax('section') && !$query->is_tax('news')) {
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
