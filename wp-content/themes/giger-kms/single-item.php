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
	<?php
		$menu = $item->get_menu();
		$sidebar = $item->get_sidebar();

		if(!empty($menu) && !empty($sidebar)) {
	?>
		<div class="bit md-3 lg-2"><?php echo $menu;?></div>

		<div class="bit md-9 lg-7 single-body">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>

		<div class="bit md-12 lg-3 single-side"><?php echo $sidebar;?></div>

	<?php } elseif(!empty($menu)) { ?>

		<div class="bit md-3"><?php echo $menu;?></div>

		<div class="bit md-9 lg-8 lg-offset-1 single-body">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>

	<?php } elseif(!empty($sidebar)) { ?>

		<div class="bit md-8 lg-8 single-body">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>
		<div class="bit md-3 lg-3 lg-offset-1 single-side">

			<?php echo $sidebar;?>
		</div>

	<?php } else { ?>

		<div class="bit md-12 single-body">

			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
			<?php if(is_singular('item', 'hiv-test')) { ?>
				<div class="lab-map"><?php echo do_shortcode('[tst_markers_map show_legend="1" legend_title="Лаборатории по диагностике ВИЧ" legend_subtitle="Выделите нужные вам объекты, чтобы показать только их."]');?></div>
			<?php } ?>
		</div>

	<?php } ?>
	</div>
</section>

<?php
get_footer();