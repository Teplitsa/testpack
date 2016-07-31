<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
$format = tst_get_post_format($cpost);
$video = $thumbnail = '';


if($format == 'introvid'){
	$video = get_post_meta($cpost->ID, 'post_video', true);
	if(empty($video))
		$format = 'standard';
}
elseif($format == 'introimg') {
	$thumbnail = tst_post_thumbnail_src($cpost->ID, 'full');
}

get_header(); ?>
<section class="main-content single-post-section container-wide format-<?php echo $format;?>">
<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>

<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo tst_posted_on($cpost); //for event ?></div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>				
		<div class="mobile-sharing hide-on-medium"><?php echo tst_social_share_no_js();?></div>
		
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
	</header>
	
	<?php if($format == 'introimg' && $cpost->post_type != 'project'){ ?>
	<section class="entry-preview introimg">		
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo $thumbnail;?>);" ></div>		
	</section>
	<?php } ?>
		
	<div class="frame">
		<main class="bit md-8">		
			
		<?php if($format == 'standard' && $cpost->post_type != 'project') { ?>
			<div class="entry-preview">
				<?php echo tst_post_thumbnail($cpost->ID, 'medium-thumbnail');?>						
			</div>
		<?php } elseif($format == 'introvid' && $cpost->post_type != 'project') { ?>
			<div class="entry-preview introvid player">
				<?php echo apply_filters('the_content', $video);?>
			</div>
		<?php } ?>				
			
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div id="tst_sidebar" class="bit md-4"><?php dynamic_sidebar( 'right_single-sidebar' ); ?> </div>
	
	</div>
</div>
</section>

<?php
	if($cpost->post_type == 'post') {
		$cat = get_the_terms($post->ID, 'category');
		$pquery = new WP_Query(array(
			'post_type'=> 'post',
			'posts_per_page' => 5,
			'post__not_in' => array($cpost->ID),
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => (isset($cat[0])) ? $cat[0]->term_id : array()
				)
			)
		));
		
		if(!$pquery->have_posts()) {
			$pquery = new WP_Query(array(
				'post_type'=> 'post',
				'posts_per_page' => 5,
				'post__not_in' => array($cpost->ID),			
			));
		}
		
		tst_more_section($pquery->posts, __('Related news', 'tst'), 'news', 'addon'); 
		
	}
	
	

get_footer();