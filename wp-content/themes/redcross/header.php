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
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'rdc' ); ?></a>

<header id="site_header" class="site-header">
	
	<div class="container">
			
		<div class="site-branding">
			<!-- <button id="menu-trigger" class="trigger-button menu"><?php rdc_svg_icon('icon-menu');?></button> -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
				<div id="logo"></div>
				<h1><?php echo get_bloginfo('name');?></h1>
				<p><?php echo get_bloginfo('description');?></p>
			</a>					
		</div>
		
		<nav class="site-nav">
			
			<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu-top')); ?>
		</nav>
	</div>
	
			
</header>

<div id="site_content" class="site-content">