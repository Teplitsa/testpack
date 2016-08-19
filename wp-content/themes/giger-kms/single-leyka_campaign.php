<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 
$thumb_src = tst_post_thumbnail_src($cpost->ID, 'full');

get_header(); ?>
<section class="main-content single-campaign-full" style="background-image: url(<?php echo $thumb_src;?>);">
<div class="container-narrow">
	<div class="campaign-form"><?php tst_donation_form(); ?></div>
	<div class="entry-content">
		<?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?>
		<div class="all-link"><span><a href="<?php echo get_permalink($cpost->ID);?>donations">Благодарим за помощь&nbsp;</a>&gt;</span></div>
	</div>
</div>
</section>
<?php get_footer();
