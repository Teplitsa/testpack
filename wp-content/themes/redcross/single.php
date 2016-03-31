<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
$format = rdc_get_post_format($cpost);
$video = $thumbnail = '';


if($format == 'introvid'){
	$video = get_post_meta($qo->ID, 'post_video', true);
	if(empty($video))
		$video = 'standard';
}
elseif($format == 'introimg') {
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
}

get_header(); ?>

<?php if($format == 'introimg'){ ?>
<section class="featured-head introimg">
	<div class="tpl-featured-bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	<div class="crumb"><?php echo rdc_breadcrumbs($cpost);?></div>
</section>
<?php } elseif($format == 'introvid') { ?>
<section class="featured-head introvid">
	<div class="player"><?php echo apply_filters('the_content', $video);?></div>
	<div class="crumb"><?php echo rdc_breadcrumbs($cpost);?></div>
</section>
<?php } ?>

<section class="main-content single-post-section">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container">
	<div class="the-post-cols">
	
		<main class="pc-main">
		<?php if($format == 'standard') { ?>
			<div class="entry-preview">
				<?php echo rdc_post_thumbnail($cpost->ID, 'medium-thumbnail');?>
				<div class="crumb"><?php echo rdc_breadcrumbs($cpost);?></div>				
			</div>		
		<?php } ?>	
			<header class="entry-header-full">				
				<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
				<div class="entry-meta"><?php echo rdc_posted_on($cpost);?></div>
				<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
			</header>
			
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div id="rdc_sidebar" class="pc-sidebar">
		<?php
			//$rquery = rdc_get_related_query($cpost, 'post_tag', 1); 
			//if($rquery->have_posts()){
			//	rdc_related_project($rquery->posts[0]);
			//}
			dynamic_sidebar( 'right_single-sidebar' );
		?>			
		</div>
	
	</div>
</div>
</section>

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
	<?php rdc_more_section($pquery->posts, __('More news', 'rdc'), 'news'); ?>
</div></section>
<?php }

get_footer();