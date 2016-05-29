<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();

get_header(); 
?>
<section class="page-header-simple"><div class="container-narrow">
	<h1 class="page-title"><?php echo get_the_title($cpost);?></h1>
	<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
</div></section>

<section class="main-content single-post-section page-content-simple">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container-narrow">	
	<div class="entry-content"><?php echo apply_filters('rdc_entry_the_content', $cpost->post_content); ?></div>	
</div>
</section>


<?php get_footer(); ?>
