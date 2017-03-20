<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object();


get_header(); ?>
<div class="single-crumb container">
	<a href="<?php echo home_url('news');?>"><?php _e('News', 'tst'); ?></a>
</div>
<article class="single">

	<header class="single__header">
		<div class="container">
			<div class="flex-grid--stacked">

				<div class="flex-cell--stacked lg-9 single__title-block">
					<div class="single-card__meta"><?php echo get_the_date('d.m.Y', $cpost);?><div class="single-card__author"><?php echo tst_single_post_authors_list($cpost);?></div></div>
					
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
			<?php
				$land = tst_get_related_landings($cpost, 2);
				if(!empty($land)) {
			?>
			<div class="flex-cell--stacked lg-3 single__related_land">
				<div class="realted-landings">
				<?php foreach($land as $l) { ?>
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
				<?php  tst_single_cta($cpost);?>
			</div>
		</div>
	</div></div><!-- .single__content -->

	<?php
		$news = tst_get_related_query($cpost, 'post_tag', 4);
		if(!empty($news)) {
			$grid_css = (!empty($land)) ? tst_get_colors_for_news((int)$land[0]->ID) : '';
	?>
	<footer class="single__footer">
		<div class="news-block container">
			<h3 class="news-block__title--inpage"><?php _e('More on the topic', 'tst'); ?></h3>

			<div class="news-block__content">
				<div class="flex-grid--stacked <?php echo $grid_css;?>">
				<?php foreach($news->posts as $i => $n) { ?>
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