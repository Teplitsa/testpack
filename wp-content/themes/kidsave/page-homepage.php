<?php
/**
 * Template Name: Homepage
 **/
 
$cpost = get_queried_object();
$ex = (!empty($cpost->post_excerpt)) ? apply_filters('kds_the_content', $cpost->post_excerpt) : '';
$thumbnail = kds_post_thumbnail_src($cpost->ID, 'full');
$bottom = get_post_meta($cpost->ID, 'page_side', true);


get_header(); 
?>
<section class="intro-home">
<header class="actionable-head">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container for-message">
			
		<?php if(!empty($ex)) { ?>
			<div class="entry-summary">
				<?php echo $ex;?>
				<div class="entry-cta"><?php echo kds_get_help_now_cta($cpost, __('Support us', 'kds'));?></div>			
			</div>
		<?php } ?>				
		
	</div>
</header>	
</section>

<section class="main-content single-post-section onhome">
	<div class="home-news-roll"><div class="container">
	<?php
		$nquery = new WP_Query(array('post_type'=> 'post', 'posts_per_page' => 4));
		if($nquery->have_posts()){
			kds_more_section($nquery->posts, __('Our news', 'kds'), 'news');
		}
	?>
	</div></div>
	
	<div class="newsletter-block"><div class="container">
		<div class="frame">
			<div class="bit md-4">
				<h5><?php _e('Subscribe to our newsletter', 'kds');?></h5>
				<p><?php _e('Stay connected and be informed', 'kds');?></p>
			</div>
			<div class="bit md-8 nl-form">
				<?php echo kds_get_newsletter_form();?>
			</div>
		</div>
	</div></div>
		
	
	<div class="container">
		<main class="custom-page">			
			<div class="entry-content narrow cp-block"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
			
			<div class="programm-gallery cp-block">				
				<?php
					$pquery = new WP_Query(array('post_type'=> 'programm', 'posts_per_page' => -1, 'orderby'=> 'menu_order'));
					if($pquery->have_posts()){
						kds_more_section($pquery->posts, __('More about our programms', 'kds'), 'programms');
					}
				?>
			</div>
			
			<div class="help-block cp-block">
				<h2><?php _e('Would you like to help? Support our work', 'kds');?></h2>				
				<?php
					$menot_url = get_post_meta($cpost->ID, 'mentor_link', true);
					$menot_txt = get_post_meta($cpost->ID, 'mentor_text', true);
					$donor_url = get_post_meta($cpost->ID, 'donation_link', true);
					$donor_txt = get_post_meta($cpost->ID, 'donation_text', true);
				?>
					<div class="frame eqh-container">
						<div class="bit md-6 eqh-el">
							<div class="hb-item mentor">
								<?php echo apply_filters('the_content', $menot_txt); ?>
								<p class="cta"><a href="<?php echo $menot_url;?>"><?php _e('Become mentor', 'kds');?></a></p>
							</div>
						</div>
						<div class="bit md-6 eqh-el">
							<div class="hb-item donate">
								<?php echo apply_filters('the_content', $donor_txt); ?>
								<p class="cta"><a href="<?php echo $donor_url;?>"><?php _e('Donate', 'kds');?></a></p>
							</div>
						</div>
					</div><!-- .frame -->				
			</div>
			
			<div class="entry-content narrow cp-block"><?php echo apply_filters('kds_the_content', $bottom); ?></div>
		</main>
	</div>
	
	
	

</section>
<?php get_footer(); ?>