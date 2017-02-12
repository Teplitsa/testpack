<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bb
 */

$cc_link = '<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">Creative Commons СС-BY-SA 3.0</a>';
$footer_text = get_option('footer_text');
$tst = __("Teplitsa of social technologies", 'tst');
?>
</div><!--  .container #site_content -->

<footer class="site-footer"><div class="container">

	<div class="flex-grid footer-branding">
		<div class="flex-cell flex-md-6 flex-lg-6 flex-exlg-7">
			<a href="<?php echo home_url('/');?>" class="site-logo--footer"><?php  tst_site_logo('white'); ?></a>
		</div>
		<div class="flex-cell flex-md-6 flex-lg-6 flex-exlg-5">
			<?php tst_subscribe_form();?>
		</div>
	</div>

	<div class="hr"></div>

	<div class="widgets flex-grid">
		<div class="flex-cell flex-md-4 flex-lg-3 footer-col-1">
			<div class="footer-text"><?php echo apply_filters('tst_the_content', $footer_text);?></div>
		</div>
		<div class="flex-cell flex-md-8 flex-lg-6 footer-col-2">
			<div class="footer-menu-grid">
				<?php wp_nav_menu(array('theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'footer-menu')); ?>

				<?php wp_nav_menu(array('theme_location' => 'footer_2', 'container' => false, 'menu_class' => 'footer-menu')); ?>

				<?php wp_nav_menu(array('theme_location' => 'footer_3', 'container' => false, 'menu_class' => 'footer-menu')); ?>
			</div>
		</div>
		<div class="flex-cell flex-lg-3 footer-col-3">
			<?php echo tst_get_social_buttons_list(array(), true);?>
		</div>
	</div>

	<div class="hr"></div>

	<div class="credits flex-grid">

		<div class="flex-cell flex-sm-7 flex-md-8">

			<div class="credits__copy">
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'tst'), $cc_link);?></p>
			</div>

		</div>

		<div class="flex-cell flex-sm-5 flex-md-4">
			<div class="tst-banner">
				<p class="tst-banner__support">Сайт сделан <br>при поддержке</p>
				<a title="<?php echo $tst;?>" href="http://te-st.ru/" class="tst-banner__link" target="_blank">
					<svg class="tst-banner__icon"><use xlink:href="#icon-te-st" /></svg>
				</a>
			</div>
		</div>
	</div>


</div></footer>

</div><!-- site_root -->

<?php wp_footer(); ?>
</body>
</html>
