<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bb
 */

$cc_link = '<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons СС-BY-SA 3.0</a>';
$tst = __("Teplitsa of social technologies", 'kds');
$banner = get_template_directory_uri().'/assets/images/te-st-logo-10x50';
$footer_text = get_theme_mod('footer_text');
?>
</div><!--  #site_content -->

<div id="bottom_bar" class="bottom-bar"><div class="container">	
	<div class="frame">
		<div class="bit md-8">
			<?php if(!is_page('subscribe'))	{ ?>
				<h5><?php _e('Subscribe to our newsletter', 'kds');?></h5>
				<div class="nl-form in-footer">
					<?php echo kds_get_newsletter_form();?>
				</div>
			<?php } else { ?>
				&nbsp;
			<?php  }?>
		</div>

		<div class="bit md-4 lg-3 lg-offset-1">
			<h5><?php _e('Our social profiles', 'kds');?></h5>
			<?php echo kds_get_social_menu(); ?>
		</div>
	</div>
</div></div>

<footer class="site-footer"><div class="container">		
	
	<div class="frame">
		<div class="bit md-8">		
				
			<div class="copy">
				<?php echo apply_filters('kds_the_content', $footer_text); ?>
								
				<p><?php printf(__('All materials of the site are avaliabe under license %s.', 'kds'), $cc_link);?></p>
			</div>
			
		</div>
		
		<div class="bit md-4 lg-3 lg-offset-1">
			<div class="te-st-bn">
				<p class="support"><?php _e('Made by', 'kds');?></p>
				<a title="<?php echo $tst;?>" href="http://te-st.ru/" class="tst-banner">
					<!-- <img alt="<?php echo $tst;?>" src="<?php echo $banner;?>.svg" onerror="this.onerror=null;this.src=<?php echo $banner;?>.png;">-->
					<svg class="tst-icon"><use xlink:href="#pic-te-st" /></svg>
				</a>
			</div>		
		</div>
	</div>
	
	
</div></footer>

<?php wp_footer(); ?>
</body>
</html>
