<?php
/**
 * General section templates
 **/

if(is_tax('section', 'news')){
	get_template_part('index'); //just to please hierarchy
	exit;
}

$section = get_queried_object();


get_header();
?>
<section class="heading" <?php echo tst_get_heading_style();?>>
	<div class="heading__block">
		<div class="heading__title"><h1><?php echo apply_filters('tst_the_title', $section->name);?></h1></div>
		<?php if(!empty($section->description)) { ?>
			<div class="heading__desc"><?php echo apply_filters('tst_the_content', $section->description);?></div>
		<?php } ?>
	</div>
</section>

<section class="main">
	<div class="flex-grid start">
		<?php  if(!empty($posts)) { foreach($posts as $cpost) { ?>
			<div class="flex-cell flex-sm-6 flex-md-4"><?php tst_card($cpost);?></div>
		<?php }} else { ?>
		<div class="flex-cell flex-mf-12"><p><?php _e('Nothing found under your request', 'tst');?></p></div>
		<?php } ?>
	</div>
</section>


<?php get_footer();
