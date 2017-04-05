<?php
/**
 * Related posts funcitons
 *
 **/

/* connection configuration */
function tst_related_posts_rules(){

	return array(
		'post'     => array('post'),
		'project'  => array('project')
	);
}

/* get relalted ids */
function tst_get_related_ids($cpost, $tax = 'post_tag', $limit = 5){
	global $wpdb, $related_pt_rules;

	$related_ids = array();

	//params
	$related_pt_rules = tst_related_posts_rules();
	$post_type = (isset($related_pt_rules[$cpost->post_type])) ? $related_pt_rules[$cpost->post_type] : '';
	$post_type = apply_filters('tst_related_post_types', $post_type, $cpost, $tax); //sometimes we need to alter it outside

	if(empty($post_type))
		return $related_ids;

	$post_id = absint($cpost->ID);

	//tags
	$relation_tags = get_the_terms($cpost, $tax);
	if(empty($relation_tags))
		return $related_ids;

	$related_ids = tst_get_related_posts_by_tags($relation_tags, $post_type, $limit, $post_id);


	return $related_ids;
}

function tst_get_related_posts_by_tags($relation_tags = array(), $post_type = array(), $limit = 5, $exclude_post_id = 0) {
	global $wpdb;


	$related_ids = array();
	if(empty($relation_tags))
		return $related_ids;

	//num
	$limit = absint($limit);

	// post_type
	if(empty($post_type))
		$post_type = array('post');

	$post_type = implode("','", $post_type);

	// term_taxonomy
	$tag_ids = array();
	foreach($relation_tags as $pt)
		$tag_ids[] = (int)$pt->term_taxonomy_id;

	$tag_ids = implode(',', $tag_ids);

	$sql =
"SELECT p.ID, COUNT(t_r.object_id) AS cnt
FROM $wpdb->term_relationships AS t_r, $wpdb->posts AS p
WHERE t_r.object_id = p.id
AND t_r.term_taxonomy_id IN($tag_ids)
AND p.post_type IN('$post_type')
AND p.id != $exclude_post_id
AND p.post_status='publish'
GROUP BY t_r.object_id
ORDER BY cnt DESC, p.post_date_gmt DESC
LIMIT $limit ";

	$r_posts = $wpdb->get_results($sql);
	if(empty($r_posts))
		return $related_ids;

	foreach($r_posts as $p){
		$related_ids[] = (int)$p->ID;
	}

	return $related_ids;
}


/* build related query */
function tst_get_related_query($cpost, $tax = 'post_tag', $limit = 5) {

	if(empty($cpost))
		$cpost = get_post();


	$r_ids = tst_get_related_ids($cpost, $tax, $limit);

	if(!empty($r_ids)){
		$q = new WP_Query(array('post__in' => $r_ids, 'post_type' => 'any', 'posts_per_page' => $limit));
	}
	else {
		$related_pt_rules = tst_related_posts_rules();
		$post_type = (isset($related_pt_rules[$cpost->post_type])) ? $related_pt_rules[$cpost->post_type] : $cpost->post_type;
		$q = new WP_Query(array('post_type' => $post_type, 'posts_per_page' => $limit, 'post__not_in' => array($cpost->ID)));
	}

	return $q;
}


/** Connections for import */
function tst_get_connected_images($cpost) {

	if(!$cpost)
		return array();

	$connection_type = ($cpost->post_type == 'import') ? 'import_attachments' : 'connected_attachments';

	return get_posts(array(
		'connected_type' => $connection_type,
		'connected_items' => $cpost,
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'posts_per_page' => -1,
		'post_mime_type' => array('image/jpeg', 'image/png', 'image/gif'),
		'cache_results'  => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false
	));


}

/** Specific connections **/
function tst_get_related_landings(WP_Post $cpost, $num = 2) {

	$tags = get_the_terms($cpost, 'post_tag');
	$lands = array();

	if(!empty($tags)) {
		$lands_ids = tst_get_related_posts_by_tags($tags, array('landing'), $num, (int)$cpost->ID);
		if(!empty($lands_ids)){
			$lands = get_posts(array(
				'post_type' => 'landing',
				'post__in' => $lands_ids
			));
		}
	}

	if(empty($lands)){
		//get random landing from work section
		$lands = get_posts(array(
			'post_type' => 'landing',
			'posts_per_page' => $num,
			'orderby' => 'rand',
			'tax_query' => array(
				array(
					'taxonomy' => 'section',
					'field' => 'slug',
					'terms' => 'work'
				)
			)
		));
	}

	return $lands;
}

function tst_landing_get_related_news($cpost, $num = 4) {

	$tags = get_the_terms($cpost, 'post_tag');
	$news_ids = tst_get_related_posts_by_tags($tags, array('post'), $num, $cpost->ID);

	if(empty($news_ids)) {
		$news = get_posts(array('post_type' => 'post', 'posts_per_page' => $num, 'post_status' => 'publish'));
	}
	else {
		$news = get_posts(array('post_type' => 'post', 'post__in' => $news_ids));
	}


	return $news;
}

function tst_project_get_related_news($cpost, $num = 4) {

	$tags = get_the_terms($cpost, 'post_tag');
	$news_ids = tst_get_related_posts_by_tags($tags, array('post'), $num, $cpost->ID);

	if(empty($news_ids)) {
		$news = get_posts(array('post_type' => 'post', 'posts_per_page' => $num, 'post_status' => 'publish'));
	}
	else {
		$news = get_posts(array('post_type' => 'post', 'post__in' => $news_ids));
	}


	return $news;
}


function tst_landing_get_connected_projects($cpost, $num = -1 ) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$connected = get_posts( array(
		'connected_type' => 'landing_project',
		'connected_items' => $cpost,
		'posts_per_page' => $num,
		'post_type' => 'project',
		'orderby' => array('date' => 'DESC', 'title' => 'ASC')
	));

	return $connected;
}

function tst_project_get_connected_landings($cpost, $num = -1 ) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$connected = get_posts( array(
		'connected_type' => 'landing_project',
		'connected_items' => $cpost,
		'posts_per_page' => -1,
		'post_type' => 'landing',
		'orderby' => 'title',
		'order' => 'ASC'
	));

	if($num < count($connected)){
		$k = array_rand($connected, $num);
		$cont = array();
		if($k) { foreach($k as $key) {
			$cont[] = 	$connected[$key];
		}}
	}
	else {
		$cont = $connected;
	}

	return $cont;
}

function tst_project_get_connected_projects($cpost, $num = -1 ) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$connected = get_posts( array(
		'posts_per_page' => $num,
		'post_type' => 'project',
		'orderby' => 'title',
		'order' => 'ASC',
		'post_parent' => $cpost->ID
	));

	return $connected;
}

function tst_get_related_publications( $cpost ) {
    $params = array(
        'post_type' => 'attachment',
        'tax_query' => array(
            array(
                'taxonomy' => 'attachment_tag',
                'field' => 'slug',
                'terms' => $cpost->post_name,
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $posts = get_posts( $params );

    return $posts;
}