<?php
/**
 * The template archive item
 */


$cpost = get_queried_object();

$title = get_the_title($cpost);
if(empty($title)) {
	$title = sprintf(__('Archive page - %s', 'tst'), get_the_date('d.m.Y', $cpost));
	$title = mb_convert_case($title ,  MB_CASE_TITLE);
}


get_header();?>

<article class="landing page-general">
	<header class="page-general__header">
		<div class="container-narrow">

			<div class="page-general__crumbs"><a href="<?php echo home_url('dront-archive/');?>"><?php _e('Archive', 'tst');?></a></div>
			<h1 class="page-general__title"><?php echo $title;?></h1>
		</div>
	</header>

	<div class="page-general__content"><div class="container-narrow">

		<div class="single-body--entry">
			<?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?>
		</div>

	</div></div>
</article>


<?php get_footer(); ?>
