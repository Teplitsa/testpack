<?php
/** Single Template for items **/

$cpost = get_queried_object();
$item = new TST_Item($cpost);

get_header();
?>

<div class="sharing"><?php tst_social_share($item->post_object);?></div>

<section class="main">
	<div class="single-item--title"><h1><?php echo apply_filters('tst_the_title', $item->get_root_title());?></h1></div>

	<div class="frame">
		<div class="bit md-12 single-body">
			
			<div class="single-body--entry">
                <?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?>
			</div>

			<div class="map"><?php echo do_shortcode('[tst_markers_map show_legend="1"]');?></div>
		</div>
	</div>
</section>

<?php
get_footer();