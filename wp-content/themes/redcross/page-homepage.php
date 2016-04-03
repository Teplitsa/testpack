<?php
/**
 * Template Name: Homepage
 **/
 
$cpost = get_queried_object();
$ex = (!empty($cpost->post_excerpt)) ? apply_filters('rdc_the_content', $cpost->post_excerpt) : '';

$home_featured_item = get_post_meta($cpost->ID, 'home_featured_item', true);
$home_featured_text = get_post_meta($cpost->ID, 'home_featured_text', true);

//to-do: only future events !!!
$events = new WP_Query(array('post_type' => array('event'), 'posts_per_page' => 1, 'post__not_in' => array($home_featured_item)));

$news_per_page = ($events->have_posts()) ? 4 : 5;
$news = new WP_Query(array('post_type' => array('post'), 'posts_per_page' => $news_per_page, 'post__not_in' => array($home_featured_item)));
$show_posts = array_merge($events->posts, $news->posts);

get_header(); 
?>
<section class="featured-action">
<?php
	if($home_featured_item){		
		rdc_featured_action_card(get_post($home_featured_item), $home_featured_text);
	}
?>
</section>
<section class="home-intro"><div class="container-wide">
	<div class="intro-text entry-content"><?php echo $ex; ?></div>
</div></section>

<section class="main-content home-content-section">

<div class="container">	
	<div class="entry-content"><?php echo apply_filters('rdc_entry_the_content', $cpost->post_content); ?></div>	
</div>
</section>

<?php rdc_more_section($show_posts, '', 'news', 'home-news'); ?>
	

<?php get_footer(); ?>