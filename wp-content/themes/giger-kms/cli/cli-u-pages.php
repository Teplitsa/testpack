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

	//Homepage & Archive
	echo 'Updating Home and Archive pages'.chr(10);

	$homepage = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_title = %s", 'Главная'));
	$update = array(
		'ID' 			=> ($homepage) ? $homepage->ID : 0,
		'post_title' 	=> 'Главная',
		'post_type' 	=> 'page',
		'post_name' 	=> 'homepage',
		'post_status'	=> 'publish',
		'meta_input'	=> array('_wp_page_template' => 'page-home.php'),
		'post_content'	=> "<h4>Агентство социальной информации</h4><p>Ведущая экспертная организация российского некоммерческого сектора и профессиональное информационное агентство, специализирующееся на освещении гражданских инициатив. Двойной статус – автономной некоммерческой организации и средства массовой информации – необходим для достижения миссии, которой АСИ руководствуется с момента создания.</p>"
	);

	$home_id = wp_insert_post($update);
	if($home_id){
		update_option('show_on_front', 'page');
		update_option('page_on_front', (int)$home_id);
	}

	unset($homepage);
	unset($update);

	$archive = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_name IN (%s, %s) LIMIT 1", 'archive', 'newswire'));
	$update = array(
		'ID' 			=> ($archive) ? $archive->ID : 0,
		'post_title' 	=> 'Лента новостей',
		'post_type' 	=> 'page',
		'post_name' 	=> 'newswire',
		'post_status'	=> 'publish',
		//'meta_input'	=> array('_wp_page_template' => 'page-home.php')
	);

	$archive_id = wp_insert_post($update);
	if((int)$archive_id > 0){
		update_option('page_for_posts', (int)$archive_id);
	}

	unset($archive);
	unset($update);

	echo 'Home and Archive pages updated. Total time in sec: '.(microtime(true) - $time_start).chr(10);

	//Update utility pages
	echo 'Updating utility pages'.chr(10);
	$utility_ids = array();
	$agency_secton = get_term_by('slug', 'agency', 'section');


	$utility = array(
		'tags' => array(
			'post_title' 	=> 'Теги',
			'post_content' 	=> 'Все теги сайта',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-tags.php')
		),
		'all-regions' => array(
			'post_title' 	=> 'Россия',
			'post_content' 	=> 'Все города и регионы России',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-tags.php')
		),
		'notify' => array(
			'post_title' 	=> 'Прислать новость',
			'post_content'	=> 'page-notify.txt', //to-do
			'about_secton'	=> false
			//'meta_input'	=> array('_wp_page_template' => 'page-home.php')
		),
		'notify-event' => array(
			'post_title' 	=> 'Прислать анонс мероприятия',
			'post_content' 	=> 'page-notify-event.txt',
			'about_secton'	=> false
			//'meta_input'	=> array('_wp_page_template' => 'page-home.php')
		),
		'notify-ngo' => array(
			'post_title' 	=> 'Добавить организацию в каталог НКО',
			'post_content' 	=> 'page-notify-ngo.txt',
			'about_secton'	=> false
			//'meta_input'	=> array('_wp_page_template' => 'page-home.php')
		),
		'subscribe' => array(
			'post_title' 	=> 'Ежедневный дайджест',
			'post_content' 	=> 'page-subscribe.txt',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-newsletter.php')
		),
		'subscribe-weekly' => array(
			'post_title' 	=> 'Еженедельный дайджест',
			'post_content' 	=> 'page-subscribe-weekly.txt',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-newsletter.php')
		),
		'subscribe-special' => array(
			'post_title' 	=> 'Специальные выпуски',
			'post_content' 	=> 'page-subscribe-special.txt',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-newsletter.php')
		),
		'subscribe-rss' => array(
			'post_title' 	=> 'Подписка на RSS',
			'post_content' 	=> '',
			'about_secton'	=> false,
			'meta_input'	=> array('_wp_page_template' => 'page-subscribe-rss.php')
		),
		'sitemap' => array(
			'post_title'	=> 'Карта сайта',
			'post_content'	=> '[tst_sitemap]',
			'about_secton'	=> false
		),
		'editorial' => array(
			'post_title'	=> 'Редакционная политика',
			'post_content'	=> 'Полный текст редакционной политики',
			'meta_input'	=> array('_wp_page_template' => 'page-about.php'),
			'about_secton'	=> true
		),
		'feedback' => array(
			'post_title'	=> 'Обратная связь',
			'post_content'	=> 'page-feedback.txt',
			'about_secton'	=> false
		),
		'team' => array(
			'post_title'	=> 'Сотрудники',
			'post_content'	=> 'Информация о сотрудниках',
			'meta_input'	=> array('_wp_page_template' => 'page-team.php'),
			'about_secton'	=> true
		),
		'contacts' => array(
			'post_title'	=> 'Контакты',
			'post_content'	=> 'page-contacts.txt',
			'about_secton'	=> true,
			'meta_input'	=> array(
								'_wp_page_template' => 'page-about.php',
								'event_marker_latitude' => '55.77470448193568',
								'event_marker_longitude' => '37.62916066501248',
								'event_marker' => array('latitude' => '55.77470448193568', 'longitude' => '37.62916066501248')
								)
		),
		'service' => array(
			'post_title'	=> 'Услуги',
			'post_content'	=> 'page-service.txt', //to-do
			'meta_input'	=> array('_wp_page_template' => 'page-about.php'),
			'about_secton'	=> true
		),
		'advertising' => array(
			'post_title'	=> 'Реклама на сайте',
			'post_content'	=> 'page-advertising.txt', //to-do
			'meta_input'	=> array('_wp_page_template' => 'page-about.php'),
			'about_secton'	=> true
		)
	);

	foreach($utility as $slug => $page_data){
		$page_start = microtime(true);

		$test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_name = %s", $slug));

		if(!$test) { //try by Name
			$title = ($slug == 'projects') ? 'Проекты АСИ' : $page_data['post_title'];
			$test = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_title = %s", $title));
			unset($title);
		}

		//content of page
		if(false !== strpos($page_data['post_content'], '.txt')){

			echo 'Get content from file: '.$page_data['post_content'].chr(10);

			$content_test = file_get_contents($page_data['post_content']);
			if($content_test){
				//correct urls
				$home = home_url('/');
				$content_test = str_replace('http://asi_dev.dev/', $home, $content_test);
				$page_data['post_content'] = $content_test;
			}

			unset($content_test);
		}
		else {
			$page_data['post_content'] = ($test) ? $test->post_content : $page_data['post_content']; //as for now add content only for new pages
		}

		//rest of data
		$page_data['ID'] = ($test) ? $test->ID : 0;
		$page_data['post_name'] = $slug;
		$page_data['post_type'] = 'page';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_status'] = 'publish';

		$uid = wp_insert_post($page_data);
		if($uid)
			$utility_ids[$slug] = $uid;

		if($uid && $agency_secton && $page_data['about_secton']) {
			wp_set_object_terms($uid, $agency_secton->term_id, 'section');
			wp_cache_flush();
		}

		echo 'Page updated: '.$slug.'. Time in sec: '.(microtime(true) - $page_start).chr(10);
	}

	unset($uid);
	unset($slug);
	unset($test);
	unset($page_data);
	unset($page_start);

	echo 'Utility pages updated: '.count($utility_ids).'. Total time in sec: '.(microtime(true) - $time_start).chr(10);

	//Create utility menu for drawer
	echo 'Creating pages menu'.chr(10);
	$menu_name = 'Дополнительное меню - Секции - Подвал';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);
	$drawer_pages = array();

	if(isset($utility_ids['editorial']))
		$drawer_pages['editorial'] = (int)$utility_ids['editorial'];

	if(isset($utility_ids['feedback']))
		$drawer_pages['feedback'] = (int)$utility_ids['feedback'];

	if($archive_id)
		$drawer_pages['newswire'] = (int)$archive_id;

	if(!empty($drawer_pages)){ foreach($drawer_pages as $p) {
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-object-id' => $p,
			'menu-item-object' => 'page',
			'menu-item-parent-id' => 0,
			'menu-item-position' => 0,
			'menu-item-type' => 'post_type',
			//'menu-item-title' => '',
			//'menu-item-url' => '',
			//'menu-item-description' => '',
			//'menu-item-attr-title' => '',
			//'menu-item-target' => '',
			//'menu-item-classes' => 'page-'.$page->post_name,
			//'menu-item-xfn' => '',
			'menu-item-status' => 'publish',
		));

		unset($page);
	}}

	//assign to location
    $locations = get_theme_mod('nav_menu_locations');
    $locations['secondary_sec'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations );

    unset($locations);
	unset($menu_name);
	unset($menu_id);
	unset($drawer_pages);
	unset($p);

	echo 'Drawer pages menu created.'.chr(10);

	//Footer menu
	echo 'Creating footer menus.'.chr(10);
	$footer = array();

	$leyka_page = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'leyka_campaign' AND post_title = %s", 'Поддержать работу агентства'));

	if(isset($utility_ids['about']))
		$footer['footer_1'][] = array('taxonomy' => 'section', 'slug' => 'agency');

	if(isset($utility_ids['editorial']))
		$footer['footer_1'][] = array('post_type' => 'page', 'ID' => $utility_ids['editorial']);

	if(isset($utility_ids['team']))
		$footer['footer_1'][] = array('post_type' => 'page', 'ID' => $utility_ids['team']);

	if(isset($utility_ids['contacts']))
		$footer['footer_1'][] = array('post_type' => 'page', 'ID' => $utility_ids['contacts']);

	if($leyka_page)
		$footer['footer_1'][] = array('post_type' => 'leyka_campaign', 'ID' => $leyka_page->ID, 'title' => 'Поддержать АСИ!');

	//services
	if(isset($utility_ids['advertising']))
		$footer['footer_2'][] = array('post_type' => 'page', 'ID' => $utility_ids['advertising']);

	if(isset($utility_ids['service']))
		$footer['footer_2'][] = array('post_type' => 'page', 'ID' => $utility_ids['service']);

	//activity
	$footer['footer_3'][] = array('taxonomy' => 'project_cat', 'slug' => 'projects');
	$footer['footer_3'][] = array('taxonomy' => 'project_cat', 'slug' => 'publications');
	$footer['footer_3'][] = array('taxonomy' => 'project_cat', 'slug' => 'reports');
	$footer['footer_3'][] = array('taxonomy' => 'category',    'slug' => 'events-asi');

	//Social
	$footer['footer_4'][] = array('custom' => 'custom', 'title' => 'Facebook',  'url' => 'https://www.facebook.com/pages/Агентство-социальной-информации/219811471391342');
	$footer['footer_4'][] = array('custom' => 'custom', 'title' => 'ВКонтакте', 'url' => 'http://vk.com/asi_news');
	$footer['footer_4'][] = array('custom' => 'custom', 'title' => 'Twiiter',   'url' => 'https://twitter.com/ASI_Russia');
	$footer['footer_4'][] = array('custom' => 'custom', 'title' => 'YouTube',   'url' => 'http://www.youtube.com/channel/UCZh9tvByr7uQ8PqU2vmvVfw');

	foreach($footer as $loc_id => $menu_config){

		//create menu
		$menu_name = str_replace('footer_', 'Футер - ', $loc_id);
		if(is_nav_menu($menu_name)){
			wp_delete_nav_menu($menu_name);
		}

		$menu_id = wp_create_nav_menu($menu_name);

		//add items
		if(!empty($menu_config)) { foreach($menu_config as $i => $menu_item) {
			if(isset($menu_item['custom'])){
				wp_update_nav_menu_item($menu_id, 0, array(
					//'menu-item-object-id' => $page->ID,
					//'menu-item-object' => 'page',
					'menu-item-parent-id' => 0,
					'menu-item-position' => 0,
					'menu-item-type' => 'custom',
					'menu-item-title' => $menu_item['title'],
					'menu-item-url' => $menu_item['url'],
					//'menu-item-description' => '',
					//'menu-item-attr-title' => '',
					//'menu-item-target' => '',
					//'menu-item-classes' => 'custom-'.lowerc,
					//'menu-item-xfn' => '',
					'menu-item-status' => 'publish',
				));
			}
			elseif(isset($menu_item['taxonomy'])){
				$term = get_term_by('slug', $menu_item['slug'], $menu_item['taxonomy']);
				if($term){
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-object-id' => $term->term_id,
						'menu-item-object' => $term->taxonomy,
						'menu-item-parent-id' => 0,
						'menu-item-position' => 0,
						'menu-item-type' => 'taxonomy',
						//'menu-item-title' => '',
						//'menu-item-url' => '',
						//'menu-item-description' => '',
						//'menu-item-attr-title' => '',
						//'menu-item-target' => '',
						//'menu-item-classes' => 'term-'.$term->slug,
						//'menu-item-xfn' => '',
						'menu-item-status' => 'publish',
					));
				}

				unset($term);
			}
			elseif(isset($menu_item['post_type'])){

				wp_update_nav_menu_item($menu_id, 0, array(
					'menu-item-object-id' => $menu_item['ID'],
					'menu-item-object' => 'page',
					'menu-item-parent-id' => 0,
					'menu-item-position' => 0,
					'menu-item-type' => 'post_type',
					'menu-item-title' => (isset($menu_item['title'])) ? $menu_item['title'] : '',
					//'menu-item-url' => '',
					//'menu-item-description' => '',
					//'menu-item-attr-title' => '',
					//'menu-item-target' => '',
					//'menu-item-classes' => 'page-'.$page->post_name,
					//'menu-item-xfn' => '',
					'menu-item-status' => 'publish',
				));

				unset($page);
			}
		}}

		//assign to location
		$locations = get_theme_mod('nav_menu_locations');
		$locations[$loc_id] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations );
	}

	unset($locations);
	unset($menu_name);
	unset($menu_id);
	unset($footer);
	unset($menu_config);
	unset($menu_item);

	echo 'Footer menu created'.chr(10);

	//Footer button menu
	echo 'Creating footer buttons menu.'.chr(10);

	$menu_name = 'Футер - Кнопки';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);

	//items
	wp_update_nav_menu_item($menu_id, 0, array(
		//'menu-item-object-id' => $page->ID,
		//'menu-item-object' => 'page',
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'FB',
		'menu-item-url' => 'https://www.facebook.com/pages/Агентство-социальной-информации/219811471391342',
		//'menu-item-description' => '',
		//'menu-item-attr-title' => '',
		//'menu-item-target' => '',
		'menu-item-classes' => 'item-icon-facebook',
		//'menu-item-xfn' => '',
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, 0, array(
		//'menu-item-object-id' => $page->ID,
		//'menu-item-object' => 'page',
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'TW',
		'menu-item-url' => 'https://twitter.com/ASI_Russia',
		//'menu-item-description' => '',
		//'menu-item-attr-title' => '',
		//'menu-item-target' => '',
		'menu-item-classes' => 'item-icon-twitter',
		//'menu-item-xfn' => '',
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, 0, array(
		//'menu-item-object-id' => $page->ID,
		//'menu-item-object' => 'page',
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'VK',
		'menu-item-url' => 'http://vk.com/asi_news',
		//'menu-item-description' => '',
		//'menu-item-attr-title' => '',
		//'menu-item-target' => '',
		'menu-item-classes' => 'item-icon-vk',
		//'menu-item-xfn' => '',
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, 0, array(
		//'menu-item-object-id' => $page->ID,
		//'menu-item-object' => 'page',
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'YT',
		'menu-item-url' => 'http://www.youtube.com/channel/UCZh9tvByr7uQ8PqU2vmvVfw',
		//'menu-item-description' => '',
		//'menu-item-attr-title' => '',
		//'menu-item-target' => '',
		'menu-item-classes' => 'item-icon-youtube',
		//'menu-item-xfn' => '',
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, 0, array(
		//'menu-item-object-id' => $page->ID,
		//'menu-item-object' => 'page',
		'menu-item-parent-id' => 0,
		'menu-item-position' => 0,
		'menu-item-type' => 'custom',
		'menu-item-title' => 'RSS',
		'menu-item-url' => home_url('feed'),
		//'menu-item-description' => '',
		//'menu-item-attr-title' => '',
		//'menu-item-target' => '',
		'menu-item-classes' => 'item-icon-rss',
		//'menu-item-xfn' => '',
		'menu-item-status' => 'publish',
	));

	if($archive_id){
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-object-id' => $archive_id,
			'menu-item-object' => 'page',
			'menu-item-parent-id' => 0,
			'menu-item-position' => 0,
			'menu-item-type' => 'post_type',
			'menu-item-title' => 'Лента',
			//'menu-item-url' => home_url('feed'),
			//'menu-item-description' => '',
			//'menu-item-attr-title' => '',
			//'menu-item-target' => '',
			//'menu-item-classes' => 'item-icon-rss',
			//'menu-item-xfn' => '',
			'menu-item-status' => 'publish',
		));
	}

	//assign to location
	$locations = get_theme_mod('nav_menu_locations');
	$locations['footer_buttons'] = $menu_id;
	set_theme_mod('nav_menu_locations', $locations );

	unset($locations);
	unset($menu_name);
	unset($menu_id);

	echo 'Footer buttons menu created'.chr(10);

	//store leyka theme options
	if($leyka_page)
		set_theme_mod('now_campaign_id', (int)$leyka_page->ID);


	//Service menu for widget on homepage
	$menu_name = 'Дополнительное - Услуги АСИ';
	if(is_nav_menu($menu_name)){
		wp_delete_nav_menu($menu_name);
	}

	$menu_id = wp_create_nav_menu($menu_name);

	if($menu_id) {
		if(isset($utility_ids['advertising'])) {
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-object-id' => $utility_ids['advertising'],
				'menu-item-object' => 'page',
				'menu-item-parent-id' => 0,
				'menu-item-position' => 0,
				'menu-item-type' => 'post_type',
				//'menu-item-title' => (isset($menu_item['title'])) ? $menu_item['title'] : '',
				//'menu-item-url' => '',
				//'menu-item-description' => '',
				//'menu-item-attr-title' => '',
				//'menu-item-target' => '',
				//'menu-item-classes' => 'page-'.$page->post_name,
				//'menu-item-xfn' => '',
				'menu-item-status' => 'publish',
			));
		}

		if(isset($utility_ids['service'])) {
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-object-id' => $utility_ids['service'],
				'menu-item-object' => 'page',
				'menu-item-parent-id' => 0,
				'menu-item-position' => 0,
				'menu-item-type' => 'post_type',
				//'menu-item-title' => (isset($menu_item['title'])) ? $menu_item['title'] : '',
				//'menu-item-url' => '',
				//'menu-item-description' => '',
				//'menu-item-attr-title' => '',
				//'menu-item-target' => '',
				//'menu-item-classes' => 'page-'.$page->post_name,
				//'menu-item-xfn' => '',
				'menu-item-status' => 'publish',
			));
		}

		//assign to location
		$locations = get_theme_mod('nav_menu_locations');
		$locations['secondary_services'] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations );
	}

	echo 'Service menu created'.chr(10);



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