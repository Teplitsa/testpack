<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object();
$parent = wp_get_post_parent_id( $cpost );

($parent) ? $parent = $parent : $parent = $cpost;

$land = tst_project_get_connected_landings($parent, 4);


if (count($land) > 2) {
	$land_top = array_values(array_chunk($land, 2))[0];
	$land_bottom = array_values(array_chunk($land, 2))[1];
} else {
	$land_top = $land;
	$land_bottom = '';
}


get_header(); ?>
<div class="single-crumb container">
	<!-- ??? -->
</div>
<article class="single">

	<header class="single__header single__header--smaller">
		<div class="container">
			<div class="flex-grid--stacked">

				<div class="flex-cell--stacked lg-9 single__title-block">
					<h1><?php echo get_the_title($cpost);?></h1>
					<div class="sharing"><?php tst_social_share($cpost);?></div>
				</div>

				<div class="flex-cell--stacked lg-3 single__nav">
					<?php tst_single_post_nav($cpost);?>
				</div>

			</div>
		</div>
	</header>

	<?php if(has_post_thumbnail($cpost)) { ?>
	<div class="single__preview"><div class="container">
		<div class="flex-grid--stacked">
			<div class="flex-cell--stacked lg-9 single__thumbnail">
				<?php tst_single_thumbnail($cpost);?>
			</div>
			<?php if(!empty($land)) { ?>
			<div class="flex-cell--stacked lg-3 single__related_land">
				<div class="realted-landings">
				<?php foreach($land_top as $l) { ?>
					<div class="realted-landings__item">
						<?php tst_card_iconic($l); ?>
					</div>
				<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div></div>
	<?php } ?>

	<div class="single__content"><div class="container">
		<div class="flex-grid--stacked">

			<div class="flex-cell--stacked lg-9 single-body">

				<?php if(!empty($cpost->post_excerpt)) { ?>
					<div class="single-body--summary"><?php echo apply_filters('tst_entry_the_content', $cpost->post_excerpt);?></div>
				<?php } ?>

				<div class="single-body--entry">
					<?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?>
				</div>
				<div class="single-body__footer">
					<?php $label = __('Tags', 'tst'); ?>
					<?php echo get_the_term_list($cpost->ID, 'post_tag', '<span class="tags"><i>'.$label.'</i>', ' ', '</span>' );?>
				</div>
			</div>

			<div class="flex-cell--stacked lg-3 single-aside">
				<?php if (!empty($land_bottom)): ?>
					<div class="realted-landings--aside">
						<?php foreach($land_bottom as $k) { ?>
							<div class="realted-landings__item">
								<?php tst_card_iconic($k); ?>
							</div>
						<?php } ?>
					</div>
				<?php endif; ?>				
				<?php  tst_single_cta($cpost, true);?>
			</div>
		</div>
	</div></div><!-- .single__content -->

	<?php
		$projects = tst_project_get_connected_projects($cpost);
		if(!empty($projects)) {
	?>
		<footer class="single__footer">
			<div class="projects-block container">
				<h3 class="projects-block__title"><?php printf(__('Projects by topic: %s', 'tst'), get_the_title($cpost)); ?></h3>

				<div class="projects-block__content">
					<div class="projects-block__icon hide-upto-medium"><?php tst_svg_icon('icon-project');?></div>

					<div class="projects-block__list">
						<ul>
						<?php foreach($projects as $p) { ?>
							<li><a href="<?php echo get_permalink($p);?>"><?php echo get_the_title($p);?></a></li>
						<?php }	?>
						</ul>
					</div>
				</div>
			</div>
		</footer>
	<?php } ?>

	<?php
		$news = tst_project_get_related_news($cpost, 4);
		if(!empty($news)) {
			$grid_css = (!empty($land)) ? tst_get_colors_for_news((int)$land[0]->ID) : '';
	?>
	<footer class="single__footer">
		<div class="news-block container">
			<h3 class="news-block__title--inpage"><?php _e('News on the topic', 'tst'); ?></h3>

			<div class="news-block__content">
				<div class="flex-grid--stacked <?php echo $grid_css;?>">
				<?php foreach($news as $i => $n) { ?>
					<?php if($i %2 > 0) { ?>
						<div class="flex-cell--stacked sm-6 lg-3 card card--colored card--news">
							<?php tst_news_card($n, 'colored'); ?>
					<?php } else { ?>
						<div class="flex-cell--stacked sm-6 lg-3 card card--item card--news">
							<?php tst_news_card($n, 'pictured'); ?>
					<?php } ?>
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</footer>
	<?php } ?>

</article>

<?php
get_footer();