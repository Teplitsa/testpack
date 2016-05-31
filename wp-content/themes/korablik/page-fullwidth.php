<?php
/**
 * Template Name: Fullwidth
 **/

$qo = get_queried_object(); 

get_header();?>

<article id="single-page" class="main-content tpl-page-fullwidth single-post-section">
	<div id="krbl_sharing" class="regular-sharing hide-upto-medium"><?php echo krbl_social_share_no_js();?></div>
	
	<div class="container">
		<div class="entry-content"><?php echo apply_filters('the_content', $qo->post_content); ?></div>
	</div>
</article>

<?php get_footer();