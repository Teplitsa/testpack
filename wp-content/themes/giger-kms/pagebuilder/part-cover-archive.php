<?php
/**
 * Part Name: Обложка для архива
 * Description: Заголовок и ссылка на архив
 */

$qo = get_queried_object();
$prefix = "cover_archive_";

$cover_title = wds_page_builder_get_this_part_data( $prefix . 'title' );
$cover_subtitle = wds_page_builder_get_this_part_data( $prefix . 'subtitle' );
$url = wds_page_builder_get_this_part_data( $prefix . 'url' );
if( $url && !preg_match( '/^(http[s]?:)\/\//', $url ) ) {
    $url = home_url( $url );
}

?>

<header class="landing-header">

	<div class="cover-general__title container">
		<h1 class="landing-header__title"><?php echo apply_filters( 'tst_the_title', $cover_title );?></h1>
		<?php if( trim( $cover_subtitle ) ): ?>
		<div class="landing-header__tagline"><?php echo apply_filters( 'tst_the_title', $cover_subtitle );?></div>
		<?php endif?>
		<?php if( $url ): ?>
		<div class="landing-header__links">
			<a href="<?php echo $url;?>" class="text-link"><?php _e('View archive', 'tst');?></a>
			<a href="#help-block" class="local-scroll button-link"><?php _e('Join us', 'tst');?></a>
		</div>
		<?php endif ?>
	</div>

</header>