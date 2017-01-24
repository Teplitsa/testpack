<?php
/**
 * Template Name: Join
 **/

$cpost = get_queried_object();


get_header();?>
<section class="main main--join">
	<div class="single-item--title"><h1><?php echo get_the_title($cpost);?></h1></div>

	<div class="frame">
        <div class="bit md-8 md-offset-2 single-body">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>


		<div class="bit md-6 md-offset-3 ">
			<?php do_shortcode( '[tst_join_whatsapp_group]' ) ?>
		</div>


    </div>
</sectipn>


<?php get_footer(); ?>