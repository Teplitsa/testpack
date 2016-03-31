<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();
$ex = (!empty($cpost->post_excerpt)) ? apply_filters('rdc_the_content', $cpost->post_excerpt) : '';
$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');

get_header(); 
?>
<section class="featured-head">
	<div class="tpl-featured-bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
</section>

<section class="featured-heading"><div class="container for-title">
	<h1 class="featured-title"><?php echo get_the_title($cpost);?></h1>
	<?php echo $ex; ?>
	<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
</div></section>

<section class="main-content single-post-section">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container">	
	<div class="entry-content"><?php echo apply_filters('rdc_entry_the_content', $cpost->post_content); ?></div>	
</div>
</section>

<?php get_footer(); ?>
