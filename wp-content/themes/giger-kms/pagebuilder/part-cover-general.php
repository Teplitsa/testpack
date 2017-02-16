<?php
/**
 * Part Name: Обложка простая
 * Description: Заголовок, ссылки, заставочный элемент
 */

$qo = get_queried_object();
$prefix = "cover_general_";

$cover = wds_page_builder_get_this_part_data($prefix.'cover_post');

$about_url = trailingslashit(get_permalink($qo)).'about';
$cover_url = '';
if($qo && has_post_thumbnail($qo)){
	$cover_url = get_the_post_thumbnail_url($qo, 'full');
}
?>

<header class="landing-header">

	<div class="cover-general__title container">
		<h1 class="landing-header__title"><?php echo get_the_title($qo);?></h1>
		<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', $qo->post_excerpt);?></div>
		<div class="landing-header__links">
			<a href="<?php echo $about_url;?>"><?php _e('Get details', 'tst');?></a>
			<a href="#" class="local-scroll"><?php _e('Join us', 'tst');?></a>
		</div>
	</div>

	<?php if($cover) { ?>
	<div class="cover-general__item">
		<div class="container">
			<a href="<?php echo get_permalink($cover);?>" class="cover-item__link"><?php echo get_the_title($cover);?></a>
		</div>
		<div class="cover-item__bg" style="background-image: url(<?php echo $cover_url;?>);"></div>

	</div>
	<?php } ?>
</header>