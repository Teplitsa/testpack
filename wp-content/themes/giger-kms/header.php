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
	
	<div class="container">
	<div class="site-header-row">
		<div class="site-header-cell branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
				<?php tst_site_logo('regular')?>
			</a>
		</div>
		<div class="site-header-cell mainmenu hide-upto-large">
			<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_id' => 'full_main_menu', 'menu_class' => 'main-menu ontop')); ?>
		</div>
		<div class="site-header-cell donation hide-upto-small">
			<?php $now_id = get_theme_mod('now_campaign_id');?>
			<a href="<?php echo get_permalink((int)$now_id);?>" class="donation-button">Помочь сейчас</a>
		</div>
		<div class="site-header-cell menu-trigger hide-on-large">
			<a id="trigger_menu" href="<?php echo home_url('sitemap');?>" class="trigger-button open-menu"><?php tst_svg_icon('icon-menu');?></a>	
		</div>
	</div>
	</div><!-- .container -->
	
	<nav id="site_nav_full" class="site-nav-full hide-upto-large"><div class="container ">
	<div class="site-nav-row">
		<?php
			$head_text = (is_front_page() || is_search() || is_404()) ? get_option('header_feature_text') : '&nbsp;';			
		?>
		<div class="site-nav-cell submenu"><div id="site_subnav"><?php echo apply_filters('tst_the_title', $head_text);?></div></div>
		<div class="site-nav-cell search">
			<a id="trigger_search" href="<?php echo add_query_arg('s', '', home_url());?>"><?php tst_svg_icon('icon-search');?></a>		
		</div>
		<!--<div class="site-nav-cell social hide-upto-exlarge"><?php echo tst_get_social_menu('top'); ?></div>-->
	</div>
	</div></nav>
	
	<!-- drawer -->
	<div class="nav-overlay"></div>
	<nav id="site_nav_mobile" class="site-nav-mobile hide-on-large">
		<div class="site-nav-title">			
			<div id="trigger_menu_close" class="trigger-button close-menu"><?php tst_svg_icon('icon-close');?></div>
		</div>
		<?php
			$after = '<span class="submenu-trigger">'.tst_svg_icon('icon-up', false).tst_svg_icon('icon-down', false).'</span>';
			wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu', 'after' => $after));
		?>
		
		
		<div class="search-holder"><?php get_search_form();?></div>
		
		<?php wp_nav_menu(array('theme_location' => 'social', 'container' => false, 'menu_class' => 'social-menu')); ?>
	</nav>
</header>

<div id="site_content" class="site-content"><a name="#content"></a>