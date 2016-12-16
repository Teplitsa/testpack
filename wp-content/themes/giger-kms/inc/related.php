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

	$post_type = implode("','", $post_type);
	$limit = absint($limit);
	$post_id = absint($cpost->ID);

	//tags
	$relation_tags = get_the_terms($cpost, $tax);
	if(empty($relation_tags))
		return $related_ids;

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
AND p.id != $post_id
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