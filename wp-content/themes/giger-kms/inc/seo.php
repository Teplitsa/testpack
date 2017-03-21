<?php
/**
 * SEO related functions
 **/





/** SEO Fixes **/
add_filter('robots_txt', 'tst_robots_txt_permission', 2, 20);
function tst_robots_txt_permission($output, $public){

	if($public == 0)
		return $output;

$host = home_url('', 'https');
$sitemap = home_url('sitemap_index.xml', 'https');

$output = "User-agent: Yandex
Disallow: /core/wp-admin/
Host: {$host}


User-agent: GoogleBot
Disallow: /core/wp-admin/


User-agent: *
Disallow: /core/wp-admin/


Sitemap: {$sitemap}";


	return $output;
}

//fix for robots tst
add_action('parse_request', 'tst_robots_txt_request', 5);
function tst_robots_txt_request($request){


	if(isset($request->query_vars['pagename']) && $request->query_vars['pagename'] == 'robots.txt'){
		$request->query_vars = array();
		$request->query_vars['robots'] = 1;
	}

	
}


//titles
function tst_wpseo_title($title){
	global $wp_query;


	if(is_front_page()){

		$title = get_bloginfo('name');
	}
	elseif(is_singular('landing')) {

		$qo = get_queried_object();

		$terms = wp_get_object_terms( $qo->ID, 'section' );
		$section = count( $terms ) > 0 ? $terms[0] : NULL;
		if( in_array( $section->slug, array( 'departments', 'ecoproblems', 'work', 'supportus' ) ) ) {
		    $title = " " . $section->name . ": ".$qo->post_title;
		}
	}
	elseif(is_singular('project')) {
	
	    $qo = get_queried_object();
	
	    $title = " Проекты: ".$qo->post_title;
	}
	elseif(is_singular('person')) {
	
	    $qo = get_queried_object();
	
	    $title = " Люди: ".$qo->post_title;
	}
	elseif(is_singular('event')) {
	
	    $qo = get_queried_object();
	
	    $title = " Анонсы: ".$qo->post_title;
	}
	elseif(is_singular()) {

		$qo = get_queried_object();

		$title = $qo->post_title;
	}
	elseif(is_tax() || is_tag() || is_category()) {

		$qo = get_queried_object();
		$title = $qo->name;
	}
	elseif(is_search()) {

		$title = 'Результаты поиска';
	}
	elseif(is_404()) {

		$title = 'Страница не найдена';
	}


	$title = trim($title);
    $title = mb_strtoupper(mb_substr($title, 0, 1, "UTF-8"), "UTF-8").mb_substr($title, 1, mb_strlen($title), "UTF-8" );

	//paging?
	$paged = get_query_var('paged');
	if(!empty($paged) && $paged > 1) {
		$title .= ' | '.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
	}

	return $title;
}

function tst_correct_seo_title_parts($title){

	$page['title'] = tst_wpseo_title($title['title']);

	return $page;
}

add_action('wp', 'tst_correct_wpseo');
function tst_correct_wpseo(){

	if(class_exists('WPSEO_Frontend')) {
		$wpseo_fe = WPSEO_Frontend::get_instance();
		remove_action('wpseo_head', array($wpseo_fe, 'debug_marker'), 2);

		add_filter('wpseo_title', 'tst_wpseo_title', 50);

	}
	else {
		add_filter('document_title_parts', 'tst_correct_seo_title_parts', 50);
	}
}

/* correct protocol in sitempas */
add_filter('wpseo_sitemap_url', 'tst_sitemap_url_protocol');
function tst_sitemap_url_protocol($url){

	//if(is_ssl())
	$url = str_replace('http:', 'https:', $url);

	return $url;
}

add_filter('home_url', 'tst_sitemap_url_protocol_correct', 3, 4);
function tst_sitemap_url_protocol_correct($url, $path, $orig_scheme, $blog_id) {

	$type = get_query_var( 'sitemap' );
	if($type == 1) {
		$url = str_replace('http:', 'https:', $url);
	}

	return $url;
}

/* remove comment feed links */
add_filter('feed_links_show_comments_feed', '__return_false');
apply_filters( 'post_comments_feed_link', function(){ return ''; });


/** deregister post format - it's show in sitemap **/
add_action('init', function(){

	$url = tst_current_url();
	if(false !== strpos($url, 'sitemap') && false !== strpos($url, '.xml'))
		tst_unregister_post_formats('post_format');

}, 50);

function tst_unregister_post_formats() {

	$taxonomy = 'post_format';
    $taxonomy_args = get_taxonomy( $taxonomy );

    global $wp, $wp_taxonomies;

    // Remove query var.
    if ( false !== $taxonomy_args->query_var ) {
        $wp->remove_query_var( $taxonomy_args->query_var );
    }

    // Remove rewrite tags and permastructs.
    if ( false !== $taxonomy_args->rewrite ) {
        remove_rewrite_tag( "%$taxonomy%" );
        remove_permastruct( $taxonomy );
    }

    // Unregister callback handling for meta box.
    remove_filter( 'wp_ajax_add-' . $taxonomy, '_wp_ajax_add_hierarchical_term' );

    // Remove the taxonomy.
    unset( $wp_taxonomies[ $taxonomy ] );

    return true;
}
