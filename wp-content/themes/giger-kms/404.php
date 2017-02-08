<?php
/** Error **/

$er_text = get_option('er_text');
$src = get_template_directory_uri().'/assets/img/er404.jpg';
get_header();
?>
<header class="single-item--title">
	<h1 class="err-404"><?php _e('404: Page not found', 'tst');?></h1>
</header>

<section class="main-content">

	<article class="err-404">
		<div class="err-404__text"><?php echo apply_filters('tst_the_content', $er_text); ?></div>
		<div class="err-404__search regular-search"><?php get_search_form();?></div>
	</article>

</section>

<?php get_footer(); ?>