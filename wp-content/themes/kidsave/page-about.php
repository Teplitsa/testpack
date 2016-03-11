<?php
/**
 * Template Name: About
 **/

$cpost = get_queried_object();
$ex = (!empty($cpost->post_excerpt)) ? apply_filters('kds_the_content', $cpost->post_excerpt) : '';
$thumbnail = kds_post_thumbnail_src($cpost->ID, 'full');
$bottom = get_post_meta($cpost->ID, 'page_side', true);

get_header(); 
?>
<section class="intro">
<header class="actionable-head">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container for-message">
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
		
		<?php if(!empty($ex)) { ?>
			<div class="entry-summary">
				<?php echo $ex;?>
				<div class="entry-cta"><?php echo kds_get_help_now_cta($cpost, __('Support us', 'kds'));?></div>			
			</div>
		<?php } ?>				
		
	</div>
</header>	
</section>

<section class="main-content single-post-section">
<div id="kds_sharing" class="regular-sharing hide-upto-medium"><?php echo kds_social_share_no_js();?></div>

<div class="container">
	<main class="custom-page">		
		<div class="inpage-menu hide-upto-medium"><?php wp_nav_menu(array('theme_location' => 'about', 'container' => false, 'menu_class' => 'about-menu', 'fallback_cb' => '')); ?></div>
		
		<div id="aboutus" class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
	
	</main>
</div>

		<div id="team" class="people-group cp-block "><div class="container">
			<h2 class="cp-block-title"><?php _e('Team', 'kds');?></h2>			
			<?php kds_people_gallery('team');?>
		</div></div>
		
		<div id="sovet" class="people-group cp-block "><div class="container">
			<h2 class="cp-block-title"><?php _e('Board of Trustees', 'kds');?></h2>
			<?php kds_people_gallery('sovet');?>
		</div></div>
	
	<div class="container">	
		<div id="partners" class="org-group cp-block ">
			<h2 class="cp-block-title"><?php _e('Partners', 'kds');?></h2>
			<?php kds_orgs_gallery('partners');?>
		</div>
				
		<main class="custom-page">
			<div id="contacts" class="entry-content cp-block "><?php echo apply_filters('kds_the_content', $bottom); ?></div>
		</main>
		
	</div>
</div></section>

<?php get_footer(); ?>