<?php
/**
 * The main template file.
 */

$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;
$title = __('Stories', 'tst');

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
				<div class="layout-section__item layout-section__item--card"><?php tst_cell_story($cpost);?></div>
			<?php
				}}
				else {
					echo "<p>".__('Nothing found under your request', 'tst')."</p>";
				}
			?>
			</div>
			<div class="layout-section layout-section--card layout-section--loadmore">
    		<?php
    			if(isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
    				tst_load_more_button($wp_query, 'news_card', array(), "loadmore-news");
    			}
    		?>
			</div>
		</div>
		<div class="bit md-4">
			<div class="layout-section layout-section--card-block">
				<?php echo TST_Stories::get_all_stories_sidebar()?>
			</div>
		</div>
	</div>
</section>

<?php get_footer();