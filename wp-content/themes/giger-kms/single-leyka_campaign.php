<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 


get_header();

if(!tst_is_children_campaign($cpost->ID)){
?>
<article id="single-page" class="main-content tpl-page-fullwidth project-page">
	<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>
		
	<div class="container">
		<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
	</div>
</article>


<?php } else { ?>

<article class="main-content tpl-child-profile">
<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>

<div class="container">
	<header class="entry-header-full">		
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
	</header>
	
	<div class="frame">
		<div class="bit md-8">
			
		<div class="frame profile-meta">
			<?php $src = tst_post_thumbnail_src($cpost, 'post-thumbnail'); ?>
			<?php if(!empty($src)) { ?>
			<div class="bit sm-5">				
				<div class="entry-preview"><div class="tpl-pictured-bg" style="background-image: url(<?php echo $src;?>);" ></div></div>
			</div>
			<?php } ?>
			<div class="bit sm-7 ">
				<?php tst_child_meta($cpost); ?>
				<?php tst_connected_project_meta($cpost); ?>
				<div class="mobile-sharing hide-on-medium"><?php echo tst_social_share_no_js();?></div>
			</div>
		</div>	
			
		<?php if(function_exists('leyka_get_scale') && !has_term('rosemary', 'campaign_cat', $cpost)) {?>
			<div class="hide-on-medium"><?php echo leyka_get_scale($cpost, array('show_button' => 1));?></div>
		<?php }?>
				
		<?php
			$thanks = get_post_meta($cpost->ID, 'campaign_child_thanks', true);
			if(!empty($thanks)){
		?>
			<div class="entry-summary thnak-you"><?php echo apply_filters('tst_entry_the_content', $thanks); ?></div>
			<h4 class="archive-subtitle"><?php _e('From archive', 'tst');?></h4>
		<?php } ?>
		
			<div class="entry-content"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?></div>
			
			<?php if(has_term(array('need-help'), 'campaign_cat', $cpost)) {?>
				<div class="campaign-form"><?php tst_donation_form(); ?></div>
			<?php } ?>
		</div>
		
		<div class="bit md-4 exlg-3 exlg-offset-1">			
			<?php
				if(function_exists('leyka_get_scale') && !has_term('rosemary', 'campaign_cat', $cpost)) {
					
					$c_campaign = new Leyka_Campaign($cpost);
					$title = (has_term(array('need-help'), 'campaign_cat', $cpost)) ? __('Help now', 'tst') : __(' Thank you!', 'tst');
									
					if($c_campaign->target && $c_campaign->target > 0) {
				?>
				<div class="widget hide-upto-medium widget_child_help">				
					<h3><?php echo $title;?></h3>
					<?php echo leyka_get_scale($cpost, array('show_button' => 1));?>
				</div>
			<?php }} ?>
			<?php
				if(function_exists('leyka_get_donors_list')) {
					$dlist = leyka_get_donors_list($cpost->ID, array('num' => 10, 'show_purpose' => 0));
				if(!empty($dlist)) {
			?>
				<div class="widget donation_history">					
					<h3><?php _e('Already helped', 'tst');?></h3>
					<?php echo $dlist; ?>					
					<div class="all-link"><a href="<?php echo get_permalink($cpost);?>donations"><?php _e('Full list', 'tst');?>&nbsp;&rarr;</a></div>
				</div>
			<?php }} ?>
		</div>	
	</div>
		
</div>
</article>
<?php
} //has terms

get_footer();
