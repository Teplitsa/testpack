<?php
/**
 * Template Name: Activity
 **/


$cpost = get_queried_object();
$ex = (!empty($cpost->post_excerpt)) ? apply_filters('rdc_the_content', $cpost->post_excerpt) : '';
$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
$bottom = apply_filters('rdc_the_content', $cpost->post_content);

$query = new WP_Query(array('post_type' => 'project', 'posts_per_page' => -1));

get_header(); 
?>
<section class="featured-head">
	<div class="tpl-featured-bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
</section>

<section class="featured-heading"><div class="container for-title">
	<h1 class="featured-title"><?php echo get_the_title($cpost);?></h1>
	<?php echo $ex; ?>
</div></section>

<section class="main-content cards-holder"><div class="container">
<div class="cards-loop sm-cols-2 md-cols-2 lg-cols-3">
	<?php
		if($query->have_posts()){
			foreach($query->posts as $p){
				rdc_project_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="bottom-content"><div class="container">
	<div class="entry-content"><?php echo $bottom; ?></div>
</div></section>

<?php get_footer(); ?>
