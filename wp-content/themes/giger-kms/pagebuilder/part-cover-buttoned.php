<?php
/**
 * Part Name: Обложка с кнопкой
 * Description: Заголовок, ссылка, кнпока, заставка, анонс или главный элемент
 */

$qo = get_queried_object();
$prefix = "cover_buttoned_";

$cover = wds_page_builder_get_this_part_data($prefix.'cover_post');

$about_url = trailingslashit(get_permalink($qo)).'about';
$cover_url = '';
if($qo && has_post_thumbnail($qo)){
	$cover_url = get_the_post_thumbnail_url($cover, 'full');
}
?>
<header class="landing-header landing-header--buttoned">
	<div class="container">
		<div class="cover-buttoned__title">
			<h1 class="landing-header__title"><?php echo get_the_title($qo);?></h1>
			<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', $qo->post_excerpt);?></div>
			<div class="landing-header__links">
				<a href="<?php echo $about_url;?>"><?php _e('Get details', 'tst');?>&nbsp;&gt;</a>
				<a href="#" class="local-scroll hide-on-large"><?php _e('Join us', 'tst');?>&nbsp;&gt;</a>
			</div>
		</div>
	</div>

	<div class="cover-buttoned__bg" style="background-image: url(<?php echo $cover_url;?>);"></div>

	<div class="container">
		<div class="cover-buttoned__item">
			<div class="cover-buttoned_button">
				<a href="#" class="local-scroll hide-upto-large button"><?php _e('Join us', 'tst');?></a>
			</div>
			<div class="cover-buttoned__card card card--text"><?php tst_card_text((int)$cover) ?></div>
		</div>
	</div>
</header>
