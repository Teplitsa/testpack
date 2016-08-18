<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
$format = tst_get_post_format($cpost);
$video = '';


if($format == 'introvid'){
	$video = get_post_meta($cpost->ID, 'post_video', true);
	if(empty($video))
		$format = 'standard';
}

get_header(); ?>
<section class="main-content single-post-section format-<?php echo $format;?>"><div class="container-narrow">
	
	<header class="entry-header-full">
		<?php echo tst_breadcrumbs($cpost);?>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
		<div class="entry-meta"><?php echo tst_posted_on_single($cpost);?></div>
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
	</header>
	
	<?php
		if($format == 'standard' || $format == 'introimg') {
			$thumb = tst_post_thumbnail_on_single($cpost->ID, $format);
			
			if(!empty($thumb)) 
				echo "<div class='entry-preview-full {$format}'>{$thumb}</div>";
			
		}
		elseif($format == 'introvid') {			
			echo "<div class='entry-preview-full video player'>".apply_filters('the_content', $video)."</div>";		
		}
	?>
	
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
	
	<footer class="entry-footer">
		<?php
			$s_link = tst_get_post_source_link($cpost->ID); 
			if(!empty($s_link)) {
		?>
			<div class="post-source-link">Источник: <?php echo $s_link;?></div>
		<?php } ?>
			<?php tst_post_nav();?>
	</footer>
</div></section>

<?php
	if($cpost->post_type == 'post') {
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
				'posts_per_page' => 5,
				'post__not_in' => array($cpost->ID),			
			));
		}
		
		tst_more_section($pquery->posts, 'Новости по теме', 'news', 'addon'); 
		
	}
	
	

get_footer();