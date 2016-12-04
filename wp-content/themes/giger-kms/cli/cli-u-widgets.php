<?php
/**
 * Create menus and delete old ones
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;


	$text_widgets = array(
		1 => array(
			'title' => '',
			'text' => 'г. Оренбург, ул. Восточная, 3-52'.chr(10).chr(10).'тел.:  <a href="tel:+73532465656" target="_blank">+7 (3532) 46-56-56<a/>, <a href="tel:+79510315656" target="_blank">+7 (951) 031-56-56</a>'.chr(10).'<a href="mailto:new.life.aids@mail.ru" target="_blank">new.life.aids@mail.ru</a>',
			'filter' => 1
		),
		2 => array(
			'title' => 'Группа взаимопомощи',
			'text' => 'Группа взаимопомощи для ВИЧ-позитивных людей проходит каждый вторник с 19 до 21 ч.'.chr(10).chr(10).'г. Оренбург, ул. Кирова, 30,'.chr(10).'3-й 	этаж, домофон «60»'.chr(10).'тел.: +7-951-031-56-56'.chr(10).chr(10).'<a href="http://vk.com/club15119220" target="_blank">Группа ВКонтакте</a>'.chr(10).'<a href="https://www.facebook.com/newlifeaids/" target="_blank">Группа в Фейсбук</a>',
			'filter' => 1
		),
		3 => array(
			'title' => 'Копилка',
			'text' => 'Для участников Группы взаимопомощи появилась возможность сделать свой вклад в улучшение работы организации.'.chr(10).chr(10).'<a href="#" class="button">Внести вклад</a>',
			'filter' => 1
		),
	);

	update_option('widget_text', $text_widgets);

	$menu_name = 'О нас';
	$menu = wp_get_nav_menu_object($menu_name);
	$menu_widgets = array();

	if($menu) {
		$menu_widgets = array(
			1 => array(
				'title' => 'АНО "Новая жизнь"',
				'nav_menu' => $menu->term_id //or tt_id?
			)
		);
		update_option('widget_nav_menu', $menu_widgets);
	}

	$sidebar = array();
	$sidebar['footer_1-sidebar'][] = 'nav_menu-1';
	$sidebar['footer_1-sidebar'][] = 'text-1';
	$sidebar['footer_2-sidebar'][] = 'text-2';
	$sidebar['footer_3-sidebar'][] = 'text-3';

	update_option('sidebars_widgets', $sidebar);

	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in sec: ' . (microtime(true) - $time_start).chr(10).chr(10);
}
catch (TstNotCLIRunException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (TstCLIHostNotSetException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (Exception $ex) {
	echo $ex;
}