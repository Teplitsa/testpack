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

<script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="https://yastatic.net/share2/share.js"></script>
<!-- old yandex share widget
<script src="https://yandex.st/share/share.js" charset="utf-8"></script>
-->

</head>

<body id="top" <?php body_class(); ?>>
<?php include_once(get_template_directory()."/assets/svg/svg.svg"); //all svgs ?>
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'tst' ); ?></a>

<header id="site_header" class="site-header">
	
	<div class="container">
	<div class="site-header-row">
		<div class="site-header-cell branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
				Дом Фрупало
			</a>
		</div>
		
		<div class="site-header-cell mainmenu hide-upto-large">
			<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_id' => 'main', 'menu_class' => 'main-menu ontop')); ?>
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
	
	<div class="nav-overlay"></div>
	<nav id="site_nav_mobile" class="site-nav-mobile hide-on-large">
		<div class="site-nav-title">			
			<div id="trigger_menu_close" class="trigger-button close-menu"><?php tst_svg_icon('icon-close');?></div>
		</div>
		<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'main-menu')); ?>
	</nav>
	
</header>

<div id="site_content" class="site-content"><a name="#content"></a>

<!-- old yandex share markup <div class="share ya_shr"></div> -->