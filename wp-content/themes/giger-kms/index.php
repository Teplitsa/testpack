<?php
/**
 * The main template file.
 */

$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;
$title = __('News', 'tst');

if(is_tag()){
	$title = '#'.get_queried_object()->name;
}

get_header();
?>
<section class="heading" <?php echo tst_get_heading_style();?>>
	<div class="heading__block">
		<div class="heading__title"><h1><?php echo apply_filters('tst_the_title', $title);?></h1></div>
	</div>
	<?php if(is_tag()) { ?>
		<div class="heading__options"><?php echo tst_tag_breadcrubms();?></div>
	<?php } ?>
</section>

<section class="main">
	<div class="frame">
		<div class="bit md-8">
			<div class="layout-section layout-section--card">
			<?php
				if(!empty($posts)) { foreach($posts as $i => $cpost) {
			?>
				<div class="layout-section__item layout-section__item--card"><?php tst_cell($cpost);?></div>
			<?php
				}}
				else {
					echo "<p>".__('Nothing found under your request', 'tst')."</p>";
				}
			?>
			</div>
			<div class="layout-section layout-section--loadmore">
				loadmore
			</div>
		</div>
		<div class="bit md-4">
			<div class="layout-section layout-section--card-block">
				<!-- cards -->
				sidebar
			</div>
		</div>
	</div>
</section>

<?php get_footer();