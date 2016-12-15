<?php
/**
 * The header for our theme.
 */
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?php wp_head(); ?>

</head>

<body id="top" <?php body_class(); ?>>
<?php include_once(get_template_directory()."/assets/svg/svg.svg"); //all svgs ?>
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'tst' ); ?></a>

<div id="drawer">
	<div class="drawer-nav">
		<div class="drawer-nav__search"><?php get_search_form();?></div>
		<div class="drawer-nav__menu">
			<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_id' => 'main', 'menu_class' => 'drawer-nav__list')); ?>
		</div>
	</div>
</div>

<div id="site_root">

<header id="site_header" class="site-header">
	<div class="container">

		<div class="site-header-row">

			<div class="site-header-cell branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
					<?php bloginfo('name');?>
					<div id="logo-full" ><?php rdc_site_logo('regular');?></div>
					<!-- <div id="logo-small" ><?php rdc_site_logo('small');?></div> -->
					<div class="logo-name-cell">
						<?php $header_title_1 = get_theme_mod('header_title_one'); ?>
						<h1 class="logo-name"><?php echo apply_filters('rdc_the_title', $header_title_1); ?></h1>
						<?php $header_title_2 = get_theme_mod('header_title_two'); ?>
						<h2 class="logo-name"><?php echo apply_filters('rdc_the_title', $header_title_2); ?></h2>
					</div>
				</a>
			</div>

			<?php $hedaer_text = get_option('site_header_text'); ?>
			<div class="site-header-cell actions">

				<div class="actions--mobile">
					<div class="actions__text actions__text--mobile hide-upto-small"><?php echo apply_filters('tst_the_title', $hedaer_text);?></div>
					<div class="action__drawer-trigger">
						<a id="trigger_menu" href="<?php echo home_url('sitemap');?>" class="hamburger hamburger--slider">
							<span class="hamburger-box"><span class="hamburger-inner"></span></span>
						</a>
					</div>
				</div>

				<div class="actions--desktop">
					<div class="actions__info-row">
						<div class="actions__text actions__text--desktop"><?php echo apply_filters('tst_the_title', $header_text);?></div>
						<div class="actions__search"><?php get_search_form();?></div>
					</div>
					<div class="actions__menu">
						<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_id' => 'main', 'menu_class' => 'actions__list')); ?>
					</div>
				</div>

			</div>

		</div>

	</div><!-- .container -->
</header>

<div id="site_content" class="site-content"><a name="#content"></a>
<div class="container">