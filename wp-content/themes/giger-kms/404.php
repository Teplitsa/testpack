<?php
/** Error **/

$er_text = get_theme_mod('er_text'); 
$src = get_template_directory_uri().'/assets/img/er404.jpg';
get_header();
?>

<section class="top-content">
	<div class="top-content__middle top-content__middle--padded">
		<header class="single-card__header">
			<div class="single-card__title">
				<h1 class="err-404"><?php _e('404: Page not found', 'tst');?></h1>
			</div>
		</header>
	</div>
</section>

<section class="main-content single-post-section">
	<div class="entry-content err-text">
		<?php echo apply_filters('tst_the_content', $er_text); ?>	
	</div>
</section>

<div class="spacer"></div>
<section class="main-content">
	<div class="layout-section layout-section--full">
		<div class="layout-section__content">

			<article class="err-404">
				<div class="err-404__text border--half-space"><?php echo apply_filters('tst_the_content', $er_text); ?></div>
				<div class="err-404__search regular-search"><?php get_search_form();?></div>
				<div class="err-404__img"><div class="img-background" style="background-image: url(<?php echo $src;?>);"></div></div>
			</article>

		</div>
	</div>
</section>

<?php get_footer(); ?>
