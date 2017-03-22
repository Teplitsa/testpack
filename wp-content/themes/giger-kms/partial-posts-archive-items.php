
<!-- main blocks -->
<section class="section-loop">
	<div class="container" id="loadmore-<?php echo $archive_query->query_vars['post_type'] ?>" >
		<?php
        if(isset($_GET['tst'])) {
            global $wp_query;
            echo '<pre>' . print_r($wp_query, 1) . '</pre>';
        }
			if(!empty($posts)) {
				tst_posts_loop_page( $posts, $archive_query->query_vars['post_type'] );
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
		if(isset($archive_query->query_vars['has_next_page']) && $archive_query->query_vars['has_next_page']) {
			tst_load_more_button($archive_query, $archive_query->query_vars['post_type'] . '_card', array(), "loadmore-" . $archive_query->query_vars['post_type']);
		}
	?>
	</div>

</section>
