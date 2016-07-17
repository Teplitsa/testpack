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
<section class="main-content single-post-section format-<?php echo $format;?>">
<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>

<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo tst_posted_on($cpost); //for event ?></div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>				
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
		<div class="mobile-sharing hide-on-medium"><?php echo tst_social_share_no_js();?></div>
	</header>
	
	<?php if($format == 'introimg' && $cpost->post_type != 'project'){ ?>
	<section class="entry-preview introimg">		
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo $thumbnail;?>);" ></div>		
	</section>
	<?php } ?>
		
	<div class="frame">
		<main class="bit md-8 bit-no-margin">		
			
		<?php if($format == 'standard') { ?>
			<div class="entry-preview">
				<?php echo tst_post_thumbnail($cpost->ID, 'medium-thumbnail');?>						
			</div>
		<?php } elseif($format == 'introvid') { ?>
			<div class="entry-preview introvid player">
				<?php echo apply_filters('the_content', $video); ?>
			</div>
		<?php } ?>				
			
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div id="tst_sidebar" class="bit md-4 lg-3 lg-offset-1 hide-upto-medium">
			<h3 class="widget-title">Программы фонда</h3>
		<?php
			$rquery = tst_get_related_query($cpost, 'post_tag', 2); 
			if($rquery->have_posts()){ foreach($rquery->posts as $pr) {
				tst_related_program_card($pr);
			}}
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
			'posts_per_page' => 4,
			'post__not_in' => array($cpost->ID),			
		));
	}
		
	if($pquery->have_posts()){
?>
	<section class="related-section"><div class="container">
		<h3 class="widget-title">Новости по теме</h3>
		<div class="cards-loop sm-cols-2 md-cols-3 lg-cols-4">
		<?php foreach($pquery->posts as $p) { tst_related_post_card($p); }?>
		</div>
	</div></section>
<?php
	}
		
	
	

get_footer();