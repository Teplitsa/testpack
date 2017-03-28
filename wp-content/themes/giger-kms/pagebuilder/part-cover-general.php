<?php
/**
 * Part Name: Обложка - заголовок
 * Description: Заголовок, ссылки, заставочный элемент
 */

$qo = get_queried_object();
$prefix = "cover_general_";

$cover = wds_page_builder_get_this_part_data($prefix.'cover_post');
$cover = ($cover) ? get_post($cover) : $cover;

$about_url = trailingslashit(get_permalink($qo)).'about';


$cover_img = wds_page_builder_get_this_part_data($prefix.'cover_file_id'); //correct field id

?>

<header class="landing-header">

	<div class="cover-general__title container">
		<h1 class="landing-header__title"><?php echo get_the_title($qo);?></h1>
		<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', get_post_meta($qo->ID, 'landing_excerpt', true));?></div>
		<div class="landing-header__links">
			<a href="<?php echo $about_url;?>" class="text-link tst-get-details-link"><?php _e('Get details', 'tst');?></a>
			<a href="#help-block" class="local-scroll button-link"><?php _e('Join us', 'tst');?></a>
		</div>
	</div>

	<?php if($cover || $cover_img) { ?>
	<?php
		$meta = tst_get_post_meta($cover);
		$desc = tst_get_post_excerpt($cover, 15, true);
	?>
	<div class="cover-general__item">
		<?php tst_fullscreen_thumbnail($cover_img, 'cover-item__bg'); ?>
		<?php if($cover) { ?>
		<div class="container">
			<a href="<?php echo get_permalink($cover);?>" class="cover-item__link">

				<?php if(!empty($meta)) { ?>
					<div class="cover-item__meta"><?php echo $meta;?></div>
				<?php } ?>

					<h4><?php echo wp_trim_words( get_the_title($cover), TST_CARD_TITLE_WORDS_LIMIT );?></h4>

				<?php if(!empty($desc)) { ?>
					<div class="cover-item__summary"><?php echo $desc;?></div>
				<?php } ?>
			</a>
		</div>
		<?php } ?>

	</div>
	<?php } ?>
</header>