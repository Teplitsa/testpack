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
$contributors_link = get_permalink( get_page_by_path( 'contributors' ) );
?>
</div></div><!--  .container #site_content -->

<footer class="site-footer"><div class="container">

	<div class="widgets flex-grid">
		<div class="flex-cell flex-sm-6 flex-md-4 first"><?php dynamic_sidebar( 'footer_1-sidebar' );?></div>
		<div class="flex-cell flex-sm-6 flex-md-4 middle"><?php dynamic_sidebar( 'footer_2-sidebar' );?></div>
		<div class="flex-cell flex-sm-9 flex-md-4 last"><?php dynamic_sidebar( 'footer_3-sidebar' );?></div>
	</div>

	<div class="hr"></div>

	<div class="credits flex-grid">

		<div class="flex-cell flex-sm-7 flex-md-8">

			<div class="credits__copy">
				<?php echo apply_filters('tst_the_content', $footer_text); ?>
				<p><?php printf(__('All materials of the site are avaliabe under license %s', 'tst'), $cc_link);?> | <a href="<?php echo $contributors_link ?>"><?php _e('Contributors', 'tst')?></a></p>
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

<!-- GA -->
<?php
	$ga = get_option('ga_id');
	if($ga) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', '<?php echo $ga; ?>', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
</body>
</html>
