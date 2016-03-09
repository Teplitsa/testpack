<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
 
get_header(); 

?>
<section class="intro"><div class="container">
	<?php echo kds_breadcrumbs($cpost);?>
	<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
	<div class="entry-meta"><?php echo kds_posted_on($cpost);?></div>
	
</div></section>

<section class="media">
<div class="regular-sharing"><?php echo kds_social_share_no_js();?></div>

<div class="container">
	<div class="entry-preview"><?php echo kds_post_thumbnail($cpost->ID, 'post-thumbnail');?></div >
</div>
</section>

<section class="main-content"><div class="container">
	
	<div class="frame">
	
		<div class="bit md-8">
			<main class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></main>
		</div>
		
		<div class="sidebar bit md-4 lg-3 lg-offset-1">
			Related project
		</div>
	
	</div>
</div></section>


<?php
	$cat = get_the_terms($post->ID, 'category');
	$pquery = new WP_Query(array(
				'post_type'=> 'post',
				'posts_per_page' => 4,
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => (isset($cat[0])) ? $cat[0]->term_id : array()
					)
				)
			));
	
	if($pquery->have_posts()){
?>
<section class="addon"><div class="container">
	<?php kds_more_section($pquery->posts, __('More news', 'kds'), 'news'); ?>
</div></section>
<?php }

get_footer();