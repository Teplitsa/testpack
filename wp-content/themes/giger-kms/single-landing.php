<?php
/**
 * The template for landings
 *
 * @package bb
 */


//var_dump($wp_query->posts);
$cpost = get_queried_object();
$about = get_query_var('item_about');

get_header();?>
<?php
	if($about && $about == 1)  { //print about page markup
		//$cpost = $wp_query->posts[0]; var_dump($cpost)

	$section = get_the_terms($cpost, 'section');
?>
<div class="single-crumb container">
	<?php if($section) { ?>
		<a href="<?php echo get_term_link($section[0]->term_id);?>"><?php echo apply_filters('tst_the_title', $section[0]->name); ?></a>
	<?php } ?>
</div>

<article class="single">

	<header class="single__header single__header--smaller">
		<div class="container">
			<div class="flex-grid--stacked">
				<div class="flex-cell--stacked md-9 single__title-block">
					<h1><?php printf(__('%s: What are we doing', 'tst'), get_the_title($cpost));?></h1>
					<div class="sharing"><?php tst_social_share($cpost);?></div>
				</div>

				<div class="flex-cell--stacked md-3 single__nav--back">
						<?php tst_card_iconic($cpost); ?>
				</div>

			</div>
		</div>
	</header>

	<div class="single__content"><div class="container">
		<div class="flex-grid--stacked">

			<div class="flex-cell--stacked lg-9 single-body">

				<div class="single-body--entry">
					<?php echo apply_filters('tst_entry_the_content', get_post_meta($cpost->ID, 'landing_content', true));?>
				</div>

				<div class="single-body__footer">
					<?php $label = __('Tags', 'tst'); ?>
					<?php echo get_the_term_list($cpost->ID, 'post_tag', '<span class="tags"><i>'.$label.'</i>', ' ', '</span>' );?>
				</div>
			</div>

			<div class="flex-cell--stacked lg-3 single-sidenews">
				<?php
					$news = tst_landing_get_related_news($cpost, 1);
					if(!empty($news)) {
						tst_news_apart_card($news[0]);
					}
				?>
			</div>
		</div>
	</div></div><!-- .single__content -->

	<section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>



<?php }  else { ?>

<article class="landing">
	<?php wds_page_builder_area( 'page_builder_default' );?>
	<section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>



<?php }

get_footer();