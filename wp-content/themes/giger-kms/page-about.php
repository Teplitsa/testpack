<?php
/**
 * Template Name: About section
 **/

$cpage = get_queried_object();
$about = new TST_About($cpage);

get_header();
?>

<div class="sharing"><?php tst_social_share($cpage);?></div>

<section class="main">
	<div class="frame">

		<div class="bit md-8">
		<?php if(is_page('projects')) { ?>
			<div class="layout-section layout-section--title"><h1><?php echo get_the_title($cpage);?></h1></div>
			<div class="layout-section layout-section--card"><?php echo $about->get_projects_content();?></div>
		<?php } else { ?>
			<div class="single-body--title"><h1><?php echo get_the_title($cpage);?></h1></div>
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $about->post_content);?></div>
		<?php } ?>
		</div>
		<div class="bit md-4"><?php echo $about->get_sidebar();?></div>

	</div>
</section>
<?php
get_footer();