<?php
/**
 * Template Name: Homepage
 **/
 
$qo = get_queried_object();
$src = get_template_directory_uri();

get_header(); 


?>

<section class="page-content"><div class="container">
	
	<h1 class="section-title"><?php echo get_the_title($post);?></h1>
	
	<div class="frame">
	
		<div class="bit md-8">
			<div class="entry-content"><?php echo apply_filters('the_content', $post->post_content); ?></div>
		</div>
		
		<div class="sidebar bit md-4">
			<?php dynamic_sidebar('right-sidebar' ); ?>
		</div>
	
	</div>
</div></section>

<?php get_footer(); ?>