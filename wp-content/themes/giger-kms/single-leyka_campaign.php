<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 


get_header();

if(has_term('programms', 'campaign_cat', $cpost)){
?>
<article id="single-page" class="main-content tpl-page-fullwidth project-page">
	<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>
		
	<div class="container">
		<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
	</div>
</article>


<?php } elseif(has_term(array('children', 'you-helped', 'need-help', 'rosemary'), 'campaign_cat', $cpost)) { ?>

<article class="main-content tpl-child-profile">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container">
	<header class="entry-header-full">		
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
		
		<div class="frame">
			<?php $src = rdc_post_thumbnail_src($cpost, 'post-thumbnail'); ?>
			<?php if(!empty($src)) { ?>
			<div class="bit sm-5 md-3">				
				<div class="entry-preview"><div class="tpl-pictured-bg" style="background-image: url(<?php echo $src;?>);" ></div></div>
			</div>
			<?php } ?>
			<div class="bit sm-7 md-9">
				<?php krb_child_meta($cpost); ?>				
				<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
			</div>
		</div>		
	</header>
	
	<div class="frame">
		<div class="bit md-8">
		<?php
			$thanks = get_post_meta($cpost->ID, 'campaign_child_thanks', true);
			if(!empty($thanks)){
		?>
			<div class="entry-summary thnak-you"><?php echo apply_filters('rdc_entry_the_content', $thanks); ?></div>
			<h4 class="archive-subtitle"><?php _e('From archive', 'rdc');?></h4>
		<?php } ?>
			<div class="entry-content"><?php echo apply_filters('rdc_entry_the_content', $cpost->post_content); ?></div>			
			<div class="campaign-form"><?php rdc_donation_form(); ?></div>
		</div>
		
		<div class="bit md-4 exlg-3 exlg-offset-1">			
			<div class="widget donation_history">
				<h3><?php _e('Our supporters', 'rdc');?></h3>
				<?php echo leyka_get_donors_list($cpost->ID, array('num' => 10, 'show_purpose' => 0));?>
				
				<div class="all-link"><a href="<?php echo get_permalink($cpost);?>donations"><?php _e('Full list', 'rdc');?>&nbsp;&rarr;</a></div>
			</div>
		</div>	
	</div>
		
</div>
</article>

<?php } else { ?>

<article id="single-page" class="main-content tpl-page-fullwidth project-page">
	<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>
		
	<div class="container">
		<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
	</div>
</article>
<?php
} //has terms

get_footer();
