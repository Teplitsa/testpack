<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();

get_header();?>
<section class="heading" <?php echo tst_get_heading_style();?>>
	<div class="heading__block">
		<div class="heading__title"><h1><?php echo get_the_title($cpost);?></h1></div>

	</div>
	<div class="heading__options">
        <div class="crumbs"></div>
		<div class="sharing"><?php tst_social_share($cpost);?></div>
	</div>
</section>

<section class="main">
	<div class="frame">
        <div class="bit md-12 single-body">
            
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>
    </div>
</sectipn>


<?php get_footer(); ?>
