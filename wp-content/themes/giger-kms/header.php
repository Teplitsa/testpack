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

<header id="site_header" class="site-header">
	
	<nav id="site_nav_full" class="site-nav-full hide-upto-medium"><div class="container ">
	<div class="site-nav-row">
		<div class="site-nav-cell main-menu"><?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu ontop')); ?></div>
		<div class="site-nav-cell search">
			<a id="trigger_search" href="<?php echo add_query_arg('s', '', home_url());?>"><?php tst_svg_icon('icon-search');?></a>		
		</div>
		<div class="site-nav-cell social hide-upto-exlarge"><?php echo tst_get_social_menu('top'); ?></div>
	</div>
	</div></nav>
	
	<div class="container">
	<div class="site-header-row">
		<div class="site-header-cell branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
				<img src="<?php echo get_template_directory_uri();?>/assets/img/logo-temp.png" id="logo">
			</a>
		</div>
		<div class="site-header-cell details hide-upto-large">
		<?php
			$header_text_one = get_theme_mod('header_text_one');
			$header_text_two = get_theme_mod('header_text_two');
		?>
			<div class="details-row">
				<div class="details-cell hide-upto-exlarge"><?php echo apply_filters('tst_the_content', $header_text_one);?></div>
				<div class="details-cell"><?php echo apply_filters('tst_the_content', $header_text_two);?></div>
			</div>
		</div>
		<div class="site-header-cell donation hide-upto-small">
			<?php $now_id = get_theme_mod('now_campaign_id');?>
			<a href="<?php echo get_permalink((int)$now_id);?>" class="donation-button">Помочь сейчас</a>
		</div>
		<div class="site-header-cell menu-trigger hide-on-medium">
			<a id="trigger_menu" href="<?php echo home_url('sitemap');?>" class="trigger-button open-menu"><?php tst_svg_icon('icon-menu');?></a>	
		</div>
	</div>
	</div><!-- .container -->
	
	<!-- drawer -->
	<div class="nav-overlay"></div>
	<nav id="site_nav_mobile" class="site-nav-mobile hide-on-medium">
		<div class="site-nav-title">			
			<div id="trigger_menu_close" class="trigger-button close-menu"><?php tst_svg_icon('icon-close');?></div>
		</div>
		<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu')); ?>
		
		<div class="search-holder"><?php get_search_form();?></div>
		
		<?php wp_nav_menu(array('theme_location' => 'social', 'container' => false, 'menu_class' => 'social-menu')); ?>
	</nav>
</header>

<div id="site_content" class="site-content"><a name="#content"></a>