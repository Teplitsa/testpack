<?php
/** Single Template for items **/

$cpost = get_queried_object();
$item = new TST_Item($cpost);

get_header();
?>

<div class="sharing"><?php tst_social_share($item->post_object);?></div>

<section class="main">
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

		<div class="bit md-12 lg-3"><?php echo $sidebar;?></div>

	<?php } elseif(!empty($menu)) { ?>

		<div class="bit md-3"><?php echo $menu;?></div>

		<div class="bit md-9 lg-8 lg-offset-1 single-body">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>

	<?php } elseif(!empty($sidebar)) { ?>

		<div class="bit md-8 lg-8 single-body">
			<div class="single-body--title"><h1><?php echo get_the_title($item->post_object);?></h1></div>
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>
		<div class="bit md-3 lg-3 lg-offset-1"><?php echo $sidebar;?></div>

	<?php } else { ?>

		<div class="bit md-12 single-body">
			<div class="single-body--title"><h1><?php echo get_the_title($item->post_object);?></h1></div>
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>

	<?php } ?>
	</div>
</section>

<?php
get_footer();