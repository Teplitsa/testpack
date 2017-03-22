
<!-- main blocks -->
<section class="section-loop">
	<div class="container" id="loadmore-<?php echo $archive_query->query_vars['post_type'] ?>" >
		<?php
			if(!empty($posts)) {
				tst_posts_loop_page( $posts, $archive_query->query_vars['post_type'] );

                if( !empty($_GET['tst']) ) {
                    foreach($posts as $post) {
                        echo '<pre>' . print_r(get_post_meta($post->ID, 'exclude_from_archive', true), 1) . '</pre>';
                    }
                }

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
