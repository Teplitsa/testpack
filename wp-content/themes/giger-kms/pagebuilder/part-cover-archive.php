<?php
/**
 * Part Name: Обложка архива
 * Description: Заголовок, ссылки, заставочный элемент
 */

$qo = get_queried_object();
$prefix = "cover_archive_";

$cover = wds_page_builder_get_this_part_data($prefix.'cover_post');
$cover = ($cover) ? get_post($cover) : $cover;

$post_type = wds_page_builder_get_this_part_data($prefix.'post_type');
if( $post_type ) {
    $archive_url = get_post_type_archive_link( $post_type );
}
else {
    $archive_url = trailingslashit(get_permalink($qo)).'archive';
}


$cover_img = wds_page_builder_get_this_part_data($prefix.'cover_file_id'); //correct field id
$cover_url = '';
if($cover_img){
	$cover_url = wp_get_attachment_url($cover_img, 'full');
}
?>

<header class="landing-header">

	<div class="cover-general__title container">
		<h1 class="landing-header__title"><?php echo get_the_title($qo);?></h1>
		<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', get_post_meta($qo->ID, 'landing_excerpt', true));?></div>
		<div class="landing-header__links">
			<?php if( $archive_url ): ?>
			<a href="<?php echo $archive_url;?>"><?php _e('View all publications', 'tst');?></a>
			<?php endif?>
			<a href="#help-block" class="local-scroll"><?php _e('Join us', 'tst');?></a>
		</div>
	</div>

	<?php if($cover) { ?>
	<div class="cover-general__item">
		<div class="container">
			<a href="<?php echo get_permalink($cover);?>" class="cover-item__link">
				<h4><?php echo get_the_title($cover);?></h4>
				<?php if(get_post_type($cover) == 'event') { ?>
					<div class="cover-item__date"><?php echo tst_event_card_date($cover);?></div>
				<?php } ?>
			</a>
		</div>
		<div class="cover-item__bg" style="background-image: url(<?php echo $cover_url;?>);"></div>

	</div>
	<?php } ?>
</header>