<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();
$dont_show_footer = get_post_meta($cpost->ID, 'dont_show_footer', true);
$section = get_the_terms($cpost, 'section');
if($section)
	$section = $section[0];



get_header();?>

<article class="landing page-general">
	<header class="page-general__header">
		<div class="container-narrow">
		<?php if($section) { ?>
			<div class="page-general__crumbs"><a href="<?php echo get_term_link($section);?>"><?php echo apply_filters('tst_the_title', $section->name);?></a></div>
		<?php } ?>
			<h1 class="page-general__title"><?php echo get_the_title($cpost);?></h1>
		</div>
	</header>

	<div class="page-general__content"><div class="container-narrow">

		<div class="single-body--entry">
			<?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?>
		</div>

		<?php if(!$dont_show_footer) { ?>
		<footer class="page-general__footer">

			<div class="flex-grid--stacked">
			<?php if($section) { ?>
				<div class="flex-cell--stacked md-6 inpage-block inpage-block--section">
					<div class="inpage-block__content">
						<h3><?php printf(__('At the section &laquo;%s&raquo;', 'tst'), $section->name);?></h3>
						<?php tst_section_list($section->term_id, $cpost->ID);?>
					</div>
				</div>
			<?php } ?>

				<div class="flex-cell--stacked md-6 inpage-block inpage-block--join">
					<div class="inpage-block__content"><h3><?php _e('Join us', 'tst');?></h3>
					<?php tst_join_list(); ?>
					</div>
				</div>
			</div>


		</footer>
		<?php }?>
	</div></div>
</article>


<?php get_footer(); ?>
