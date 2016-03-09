<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

get_header(); 

?>

<section class="page-content"><div class="container">
	
	<div class="frame">
	
		<div class="bit md-8">
			<div class="entry-content"><?php echo apply_filters('the_content', $post->post_content); ?></div>
		</div>
		
		<div class="sidebar bit md-3 md-offset-1">
			<?php dynamic_sidebar('page_right-sidebar' ); ?>
		</div>
	
	</div>
</div></section>

<?php get_footer(); ?>