<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object();


get_header(); ?>

<article class="single-card">
	<div class="single-card__header">
		<div class="single-card__title"><h1><?php echo get_the_title($cpost);?></h1></div>
		<div class="single-card__options">
			<div class="single-card__meta"></div>
			<div class="sharing"><?php tst_social_share($cpost);?></div>
		</div>
	</div>

	<div class="single-card__content <?php if(!has_post_thumbnail($cpost)) {echo 'no-thumbnail'; } ?>">
	<div class="frame">

		<div class="bit md-8 single-body">

			<?php if(has_post_thumbnail($cpost)) { ?>
				<div class="single-body__preview"><?php tst_single_thumbnail($cpost);?></div>
			<?php } ?>

			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
			<div class="single-body__footer single-body__footer-mobile"><?php tst_single_post_nav();?></div>
		</div>

		<div class="bit md-4 single-aside">
		<?php
			$related = tst_get_related_query($cpost, 'post_tag', 4);
			if(!empty($related)) {
		?>
			<div class="widget">
				<div class="widget__title"><?php _e('More stories', 'tst');?></div>
				<div class="widget__content"><?php tst_related_list($related->posts); ?></div>
			</div>
		<?php
			}
		?>
		</div>
	</div></div><!-- .frame .single-card__content -->
	<div class="single-body__footer single-body__footer-desktop"><?php tst_single_post_nav( __('Prev. Story', 'tst'), __('Next Story', 'tst') );?></div>
</article>

<?php
get_footer();