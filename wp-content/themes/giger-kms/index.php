<?php
/**
 * The main template file.
 */

$posts = $wp_query->posts;

$title = __('News', 'tst');
$desc = '';

if(is_tag()){
	$title = '#'.get_queried_object()->name;
	$desc = apply_filters('tst_the_content', get_queried_object()->description);
}
elseif(is_home()) {
	$news = get_post((int)get_option('page_for_posts'));
	$desc = ($news && !empty($news->post_excerpt)) ? apply_filters('tst_the_content', $news->post_excerpt) : '';
}

include( get_template_directory() . '/partial-posts-archive.php' );
