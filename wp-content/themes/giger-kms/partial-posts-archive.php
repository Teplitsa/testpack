<?php
/**
 * Common archive template
 */

get_header();
?>
<article class="landing landing--news">
	<section class="section-intro">
		<header class="landing-header landing-header--news">
			<div class="cover-general__title container">
				<h1 class="landing-header__title"><?php echo apply_filters( 'tst_the_title', $title );?></h1>
				<?php if(!empty($desc)) { ?>
					<div class="landing-header__tagline"><?php echo $desc;?></div>
				<?php } ?>

				<?php if(!empty($crumb)) {?>
					<div class="landing-header__links"><?php echo $crumb;?></div>
				<?php } ?>
			</div>

		</header>
	</section>

	<!-- main blocks -->
	<section class="section-loop">
		<div class="container" id="loadmore-<?php echo $wp_query->query_vars['post_type'] ?>" >
			<?php
				if(!empty($posts)) {
					tst_posts_loop_page( $posts, $wp_query->query_vars['post_type'] );
				}
				else {
			?>
				<div class="loop-not-found"><?php _e('Nothing found under your request', 'tst');?></div>
			<?php
				}
			?>
		</div>

		<div class="layout-section--loadmore">
		<?php
			if(isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
				tst_load_more_button($wp_query, $wp_query->query_vars['post_type'] . '_card', array(), "loadmore-" . $wp_query->query_vars['post_type']);
			}
		?>
		</div>

	</section>
</article>
<?php get_footer();