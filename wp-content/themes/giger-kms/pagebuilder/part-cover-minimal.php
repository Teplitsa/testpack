<?php
/**
 * Part Name: Обложка - заставка
 * Description: Заставочный элемент в качестве обложки
 */

$qo = get_queried_object();
$prefix = "cover_minimal_";

$cover = wds_page_builder_get_this_part_data($prefix.'cover_post');
$cover = ($cover) ? get_post($cover) : $cover;

$cover_img = wds_page_builder_get_this_part_data($prefix.'cover_file_id');


$cover_title = wds_page_builder_get_this_part_data($prefix.'cover_title');
if(empty($cover_title)){
	$cover_title = get_the_title($cover);
}

$cover_desc = wds_page_builder_get_this_part_data($prefix.'cover_desc');
if(empty($cover_desc)){
	if($cover->post_type == 'event') {
		$cover_desc = tst_event_card_meta($cover);
	}
	else {
		$cover_desc = tst_get_post_excerpt($cover, 10, true);
	}
}
?>

<header class="landing-header">

	<?php if($cover) { ?>
	<div class="cover-general__item">
		<?php tst_fullscreen_thumbnail($cover_img, 'cover-item__bg'); ?>
		<div class="container">
			<a href="<?php echo get_permalink($cover);?>" class="cover-item__link">
				<h4><?php echo apply_filters('tst_the_title', $cover_title);?></h4>
				<div class="cover-item__date"><?php echo apply_filters('tst_the_title', $cover_desc);?></div>
			</a>
		</div>


	</div>
	<?php } ?>
</header>