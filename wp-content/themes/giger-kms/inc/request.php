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
