<?php
if(!defined('ABSPATH')) die; // Die if accessed directly



?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<link href="<?php echo home_url('favicon.ico');?>" rel="shortcut icon" type="image/x-icon">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<title><?php bloginfo( 'name' ); ?></title>
<meta name="description" content="<?php bloginfo( 'description' ); ?>">

<meta property="og:locale" content="ru_RU">
<meta property="og:type" content="website">
<meta property="og:title" content="<?php bloginfo( 'name' ); ?>">
<meta property="og:description" content="<?php bloginfo( 'description' ); ?>">
<meta property="og:url" content="<?php echo home_url(); ?>">
<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
<meta property="og:image" content="">

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<?php do_action('tstl_head');?>

<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>

</head>
<body id="top" <?php body_class(); ?> style="background-image: url(<?php echo TSTL_PLUGIN_BASE_URL;?>/assets/img/bg.jpeg);">
<?php include_once(TSTL_PLUGIN_DIR."/assets/svg/svg.svg"); //all svgs ?>
<div class="page-bg">

	<div class="container">
		<div class="banner"><svg class="svg-pic pic-banner"><use xlink:href="#pic-banner" /></svg></div>
		<div class="hand"><svg class="svg-pic pic-hand"><use xlink:href="#pic-hand" /></svg></div>

		<div class="card">
			<div class="ribbon"><svg class="svg-pic pic-ribbon"><use xlink:href="#pic-ribbon" /></svg></div>
			<div class="card__head">
				<h1><?php bloginfo('name');?></h1>
				<h2><?php bloginfo('description');?></h2>
			</div>

			<div class="card__text">
			<?php
				$text = get_option('landing_text');
				if(empty($text)) {
					$text = 'Наш новый сайт находится в разработке.
Совсем скоро на нем будут доступные новые материалы и сервисы.

Присоейдиняйтесь к нашей группе в WatsApp, чтобы быть в курсе новостей - мы сообщим, когда сайт заработает.';
				}
				echo apply_filters('the_content', $text);
			?>
			</div>
			<div class="card__action">
			<?php
				$core = TSTL_Core::get_instance();
				if(!$core->is_mobile_user_agent()) {
			?>
				<div class="form-area">
					<div id="form-response"></div>
					<form class="tstl-join-group" action="#" method="post">
						<p>Оставьте номер вашего мобильного телефона, мы добавим вас в группу</p>
						<div class="field">
							<input type="text" name="tstl_phone" id="tstl_phone" value="">
							<?php wp_nonce_field('tstl_join_group', '_tstl_nonce'); ?>

							<div class="field__error">Укажите, корректный номер телефона</div>
						</div>
						<div class="submit">
							<button type="submit" class="card__button">
								<span><svg class="svg-icon icon-whatsapp"><use xlink:href="#icon-whatsapp" /></svg>
								Вступить в группу</span>
							</button>
						</div>
					</form>

					<noscript><p>Включите поддержку JS в браузере или зайдите с мобильного телефона, чтобы присоединиться к группе</p></noscript>
				</div>
			<?php
				}
				else {
					$url = $core->button_url();
			?>
				<a href="<?php echo $url;?>" target="_blank" class="card__button">
					<span><svg class="svg-icon icon-whatsapp"><use xlink:href="#icon-whatsapp" /></svg>
					Вступить в группу</span>
				</a>
			<?php
				}
			?>

			</div>
		</div>

		<div class="words"><svg class="svg-pic pic-words"><use xlink:href="#pic-words" /></svg></div>
	</div>

</div>
<?php do_action('tstl_footer');?>
</body>
</html>
