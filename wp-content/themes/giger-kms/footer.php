<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bb
 */

$cc_link = '<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons СС-BY-SA 3.0</a>';
$tst = __("Teplitsa of social technologies", 'tst');
$banner = get_template_directory_uri().'/assets/images/te-st-logo-10x50';
$footer_text = get_theme_mod('footer_text');
?>
</div><!--  #site_content -->

<div style="display:none;">
	<?php if($subscribe_list = get_theme_mod('subscribe_list_subscription')):?>
	    <span style="display:none;" id="tst_mailchimp_subscribe_list_subscription">
	        <input type="hidden" name="FNAME" class="tst-mailchimp-fname"/>
	        <input type="hidden" name="LNAME" class="tst-mailchimp-lname" />
	        <input type="hidden" name="EMAIL" class="tst-mailchimp-email" />
            <input type="hidden" name="_mc4wp_lists" value="<?php echo $subscribe_list; ?>" />
            <input type="hidden" name="mc4wp-subscribe" value="1" />
        </span>
    <?php endif; ?>

	<?php if($subscribe_list = get_theme_mod('subscribe_list_volonter')):?>
	    <span style="display:none;" id="tst_mailchimp_subscribe_list_volonter">
	        <input type="hidden" name="FNAME" class="tst-mailchimp-fname"/>
	        <input type="hidden" name="LNAME" class="tst-mailchimp-lname" />
	        <input type="hidden" name="EMAIL" class="tst-mailchimp-email" />
            <input type="hidden" name="_mc4wp_lists" value="<?php echo $subscribe_list; ?>" />
            <input type="hidden" name="mc4wp-subscribe" value="1" />
        </span>
    <?php endif; ?>
    
	<?php if($subscribe_list = get_theme_mod('subscribe_list_needhelp')):?>
	    <span style="display:none;" id="tst_mailchimp_subscribe_list_needhelp">
	        <input type="hidden" name="FNAME" class="tst-mailchimp-fname"/>
	        <input type="hidden" name="LNAME" class="tst-mailchimp-lname" />
	        <input type="hidden" name="EMAIL" class="tst-mailchimp-email" />
            <input type="hidden" name="_mc4wp_lists" value="<?php echo $subscribe_list; ?>" />
            <input type="hidden" name="mc4wp-subscribe" value="1" />
        </span>
    <?php endif; ?>
    
</div>

<div id="bottom_bar" class="bottom-bar"><div class="container">	
	<div class="frame frame-wide">
		<div class="bit md-9 lg-8">
			<?php if(!is_page('subscribe'))	{ ?>
				<h5><?php _e('Subscribe to our newsletter', 'tst');?></h5>
				<div class="newsletter-form in-footer">
					<?php echo tst_get_newsletter_form('bottom');?>
				</div>
			<?php } else { ?>
				&nbsp;
			<?php  }?>
		</div>

		<div class="bit md-3 lg-3 lg-offset-1">
			<h5><span class="icons-label"><?php _e('Our social profiles', 'tst');?></span>&nbsp;</h5>
			<?php echo tst_get_social_menu(); ?>
		</div>
	</div>
</div></div>

<footer class="site-footer"><div class="container">		
	
	<div class="widget-area"><?php dynamic_sidebar( 'footer-sidebar' );?></div>
	<div class="hr"></div>
	<div class="sf-cols">
		
		<div class="sf-cols-8">		
				
			<div class="copy">
				<?php echo apply_filters('tst_the_content', $footer_text); ?>	
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'tst'), $cc_link);?></p>
			</div>
			
		</div>
		
		<div class="sf-cols-4">
			<div class="te-st-bn">
				<p class="support">Сайт сделан <br>при поддержке</p>
				<a title="<?php echo $tst;?>" href="http://te-st.ru/" class="rdc-banner" target="_blank">					
					<svg class="rdc-icon"><use xlink:href="#icon-te-st" /></svg>
				</a>
			</div>			
		</div>
	</div>
	
	
</div></footer>

<?php wp_footer(); ?>

<div id="requestt-call-modal" class="rdc-modal" style="<?php if(isset($_POST['form_key']) && $_POST['form_key'] == 'call_request_form'):?>display:block;"<?php else:?>display:none;<?php endif?>">
  <div class="rdc-modal-content">
    <span class="rdc-close" id="rdc-request-call-modal-close"><?php tst_svg_icon('icon-close');?></span>
    <?php echo tst_get_call_request_form();?>    
  </div>
</div>

</body>
</html>
