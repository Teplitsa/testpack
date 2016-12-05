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
?>
</div></div><!--  .container #site_content -->

<footer class="site-footer"><div class="container">

	<div class="widgets frame">
		<div class="bit md-4"><?php dynamic_sidebar( 'footer_1-sidebar' );?></div>
		<div class="bit md-4"><?php dynamic_sidebar( 'footer_2-sidebar' );?></div>
		<div class="bit md-4"><?php dynamic_sidebar( 'footer_3-sidebar' );?></div>
	</div>

	<div class="hr"></div>

	<div class="credits flex-grid">

		<div class="flex-cell flex-sm-7 flex-md-8">

			<div class="copy">
				<?php echo apply_filters('tst_the_content', $footer_text); ?>
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'tst'), $cc_link);?></p>
			</div>

		</div>

		<div class="flex-cell flex-sm-5 flex-md-4">
			<div class="tst-banner">
				<p class="tst-banner__text">Сайт сделан <br>при поддержке</p>
				<a href="http://te-st.ru/" class="tst-banner__link" target="_blank">
					<svg class="svg-icon tst-banner__icon"><use xlink:href="#icon-te-st" /></svg>
				</a>
			</div>
		</div>
	</div>


</div></footer>

</div><!-- site_root -->
<?php wp_footer(); ?>

</body>
</html>
