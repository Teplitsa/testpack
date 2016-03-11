<?php
/**
 * The template for displaying all pages.
 */

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
	<div class="the-page-cols frame">
	
		<main class="bit md-8">				
			<div class="mobile-sharing hide-on-medium"><?php echo kds_social_share_no_js();?></div>
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div class="bit md-4 lg-3 lg-offset-1">
			<div class="related-widget widget">
			<?php
				$txt = get_post_meta($cpost->ID, 'page_side', true);
				if($txt)
					echo apply_filters('kds_the_content', $txt);
			?>
			</div>
		</div>
	
	</div>
</div></section>

<?php get_footer(); ?>
