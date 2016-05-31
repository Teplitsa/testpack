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
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'krbl' ); ?></a>

<header id="site_header" class="site-header">
	
	<div class="site-header-panel">
		<div class="container-wide">
			
		<div class="site-panel-row">
			<div class="site-branding site-panel-cell">				
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
					<div id="logo"><?php krbl_site_logo('regular');?></div>
					<h1 class="logo-name"><?php echo get_bloginfo('name');?></h1>
					<h2 class="logo-name"><?php echo get_bloginfo('description');?></h2>
				</a>					
			</div>
			
			<?php $header_text = get_option('header_adr_text'); ?>
			<div class="site-details site-panel-cell"><?php echo apply_filters('krbl_the_content', $header_text); ?></div>									
			<div class="trigger-button newsletter site-panel-cell">
				<a id="trigger_newsletter"  href="<?php echo home_url('subscribe');?>"><?php _e( 'Subscribe', 'krbl' ); ?><?php krbl_svg_icon('icon-mail');?><?php krbl_svg_icon('icon-close');?></a>
			</div>
			<div class="trigger-button menu site-panel-cell">
				<a id="trigger_menu" href="<?php echo home_url('sitemap');?>">
					<?php krbl_svg_icon('icon-menu');?>					
				</a>				
			</div>
			
		</div>	
		</div>
	</div>
	
	<div id="newsletter_panel" class="newsletter-panel">
		<div class="newsletter-form"><?php echo krbl_get_newsletter_form(); ?></div>
	</div>
	<div class="nav-overlay"></div>
	<nav id="site_nav" class="site-nav">
		<div class="site-nav-title">
			<div class="snt-cell">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
					<h1 class="logo-name"><?php echo get_bloginfo('name');?></h1>
					<h2 class="logo-name"><?php echo get_bloginfo('description');?></h2>
				</a>
			</div>
			<div id="trigger_menu_close" class="trigger-button close"><?php krbl_svg_icon('icon-close');?></div>
		</div>
		<?php
			$after = '<span class="submenu-trigger">'.krbl_svg_icon('icon-up', false).krbl_svg_icon('icon-down', false).'</span>';
			wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu', 'after' => $after));
		?>
		<div class="search-holder"><?php get_search_form();?></div>
		<?php wp_nav_menu(array('theme_location' => 'social', 'container' => false, 'menu_class' => 'social-menu')); ?>
	</nav>		
</header>

<div id="site_content" class="site-content">