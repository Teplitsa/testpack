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
	$cover_desc = tst_get_post_excerpt($cover, 15, true);
}

$meta = ($cover) ? tst_get_post_meta($cover) : '';
?>

<header class="landing-header">

	<?php if($cover) { ?>
	<div class="cover-general__item">
		<?php tst_fullscreen_thumbnail($cover_img, 'cover-item__bg'); ?>
		<div class="container">
			<a href="<?php echo get_permalink($cover);?>" class="cover-item__link">
				<?php if(!empty($meta)) { ?>
					<div class="cover-item__meta"><?php echo $meta;?></div>
				<?php } ?>

				<h4><?php echo wp_trim_words($cover_title, TST_CARD_TITLE_WORDS_LIMIT );?></h4>

				<?php if(!empty($cover_desc)) { ?>
					<div class="cover-item__summary"><?php echo $cover_desc;?></div>
				<?php } ?>
			</a>
		</div>


	</div>
	<?php } ?>


</header>