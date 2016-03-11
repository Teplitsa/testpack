<?php
/**
 * Template Name: Activity
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
				<div class="entry-cta"><?php echo kds_get_help_now_cta(null, __('Support us', 'kds'));?></div>			
			</div>
		<?php } ?>				
		
	</div>
</header>	
</section>

<section class="main-content single-post-section">
<div id="kds_sharing" class="regular-sharing hide-upto-medium"><?php echo kds_social_share_no_js();?></div>

	<div class="container">
		
		<main class="custom-page">		
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
			
			<div class="programm-block cp-block">
				<h2><?php _e('Our programms', 'kds');?></h2>
			<?php
				$pquery = new WP_Query(array('post_type'=> 'programm', 'posts_per_page' => -1, 'orderby'=> 'menu_order'));
				if($pquery->have_posts()){
			?>
				<div class="frame eqh-container">
				<?php foreach($pquery->posts as $pp){ ?>
					<div class="bit sm-6 eqh-el"><?php kds_programm_card($pp);?></div>
				<?php } ?>
				</div>
			<?php } ?>
			</div>
		</main>	
	</div>

	<div class="newsletter-block roll"><div class="container">
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
		
	<div class="news-roll"><div class="container">
	<?php
		$nquery = new WP_Query(array('post_type'=> 'post', 'posts_per_page' => 4));
		if($nquery->have_posts()){
			kds_more_section($nquery->posts, __('News of programms', 'kds'), 'news');
		}
	?>
	</div></div>
	
	
	<div class="container">	
		<main class="custom-page">	
			<div class="entry-content narrow cp-block "><?php echo apply_filters('kds_the_content', $bottom); ?></div>	
		</main>
	</div>
	
</section>

<?php get_footer(); ?>
