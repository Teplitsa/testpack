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
<section class="main">
	<div class="frame">
		<div class="bit md-8">
			<div class="layout-section layout-section--card" id="loadmore-news">
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
    		<?php
    			if(isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
    				tst_load_more_button($wp_query, 'news_card', array(), "loadmore-news");
    			}
    		?>
			</div>
		</div>
		<div class="bit md-4">
			<div class="layout-section layout-section--card-block">
				<!-- cards -->
			<?php
				$items = get_posts(array(
					'post_type' => 'item',
					'posts_per_page' => 3,
					'post_parent' => 0,
					'no_found_rows' => true,
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache ' => false,
					'orderby' => 'rand'
				));

				if(!empty($items)) { foreach($items as $si) {
			?>
				<div class="widget widget--card"><?php tst_card($si, false);?></div>
			<?php
				}}
			?>
			</div>
		</div>
	</div>
</section>

<?php get_footer();