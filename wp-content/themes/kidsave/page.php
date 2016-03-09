<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package bb
 */

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
