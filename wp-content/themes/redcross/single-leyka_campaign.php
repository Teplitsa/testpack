<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 
 
get_header();
?>
<article class="main-content leyka-campaign">
<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo rdc_posted_on($cpost); //for event ?></div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>				
		<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
	</header>
	
	<div class="frame">
		<main class="bit md-8 lg-6">
		<div class="campaign-form">
				<?php //echo apply_filters('the_content', ''); ?>
				<?php rdc_donation_form(); ?>
			</div>
		</main>
	
		<div id="rdc_sidebar" class="bit md-4 lg-offset-2">
			<div class="widget donation_cta red"><?php echo apply_filters('rdc_the_content', $cpost->post_content); ?></div>
			<div class="widget donation_history">
				<h3><?php _e('Our supporters', 'rdc');?></h3>
				<?php echo leyka_get_donors_list($cpost->ID, array('num' => 5, 'show_purpose' => 0));?>
				
				<div class="all-link"><a href="<?php echo get_permalink($cpost);?>donations"><?php _e('Full list', 'rdc');?></a></div>
			</div>
		</div>
	</div>
</div>
</article>
<?php
get_footer();
