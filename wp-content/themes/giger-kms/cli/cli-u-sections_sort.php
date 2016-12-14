<?php
/**
 * Sort out posts ander new sections
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//upload folder
	$uploads = wp_upload_dir();

	//sections
	$sections_raw = get_terms(array('taxonomy' => 'section', 'hide_empty' => 0));
	$sections = array();
	if(!empty($sections_raw)) { foreach($sections_raw as $s) {
		$sections[$s->slug] = $s;
	}}

	//landings
	$move_to_items = array();

	$move_to_items[] = array(
		'ID' =>  false,
		'slug' => 'events',
		'post_title' => 'Мероприятия',
		'post_content' => 'Профильные конференции и мероприятия', //add some text
		'section' => 'work',
		'parent' => 0,
		//'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'date_range'),
		//'thumb' => 'events.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'law',
		'post_title' => 'Юридическая защита',
		'post_content' => 'Общественная приемная, ссылки на доп.сайты, задать вопрос',
		'section' => 'work',
		'parent' => 0,
		'thumb' => 'landing-law.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'education',
		'post_title' => 'Экопросвещение',
		'post_content' => 'Эколагеря, творчество, публикации, пособия',
		'section' => 'work',
		'parent' => 0,
		'thumb' => 'landing-education.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'researches',
		'post_title' => 'Исследования',
		'post_content' => 'Публикации, статистика',
		'section' => 'work',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'activist',
		'post_title' => 'Активизм',
		'post_content' => 'Кейсы, действующие и прошлые акции',
		'section' => 'work',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'publications',
		'post_title' => 'Публикации',
		'post_content' => 'Публикации из раздела "публикации", Газета Берегиня, публикации фотостудии',
		'section' => 'work',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'ecomap',
		'post_title' => 'Экокарта',
		'post_content' => 'Карта с маркерами экологических проблем, сервис "сообщить о проблеме"',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'cheboksarges',
		'post_title' => 'Чебоксарская ГЭС',
		'post_content' => 'Кейс Чебоксарской ГЭС',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'nizhaes',
		'post_title' => 'Нижегородская АЭС',
		'post_content' => 'Кейс угрозы Нижегородской АЭС',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'rares',
		'post_title' => 'Охрана редких видов',
		'post_content' => 'Красная книга, публикации',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'oopt',
		'post_title' => 'ООПТ',
		'post_content' => 'Информация о территориях, что мы делаем, документы',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'birds',
		'post_title' => 'Птицы',
		'post_content' => 'Выдержки из проектов, фото, акции, публикации',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'urban',
		'post_title' => 'Город',
		'post_content' => 'Ссылка на лонгрид по зеленым территориям, публикации и высказываиня',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'locals',
		'post_title' => 'Проблемные территории',
		'post_content' => 'Информация о территориях, что мы делаем, документы',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'health',
		'post_title' => 'Здоровье',
		'post_content' => 'О влиянии экологии на здоровье',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'resources',
		'post_title' => 'Ресурсы',
		'post_content' => 'Ресурсы, энергосбережение, климат',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'garbage',
		'post_title' => 'Отходы',
		'post_content' => 'Разделльный сбор, проблема свалок, свалки и пункты на карте',
		'section' => 'ecoproblems',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'bereginya',
		'post_title' => 'Ежемесячная газета "Берегиня"',
		'post_content' => 'Все выпуски газеты',
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'sbereg-center',
		'post_title' => 'Центр природосберегающих технологий',
		'post_content' => 'О центре, список проектов, отчеты',
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'sopr',
		'post_title' => 'Нижегородское отделение Союза охраны птиц России',
		'post_content' => 'Текстовое описание проектов, отчеты, партнеры, фото',
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'ornotologlab',
		'post_title' => 'Орнитологическая лаборатория',
		'post_content' => 'Исследования, публикации, отчеты', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'lawcenter',
		'post_title' => 'Нижегородский эколого-правовой центр',
		'post_content' => 'Тексты, проекты, документы, образцы', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'striks',
		'post_title' => 'Информационно-консультационный центр "Стрикс"',
		'post_content' => 'Проекты, публикации (список), отчеты', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'reptiles',
		'post_title' => 'Нижегородское общество охраны амфибий и рептилий',
		'post_content' => 'Текст, отчеты, публикации', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'jungle',
		'post_title' => 'Научно-просветительская организация "Джунгли"',
		'post_content' => 'Текст, отчеты, публикации', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'folks',
		'post_title' => 'Нижегородский фольклорный клуб',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'obereg',
		'post_title' => 'Эколого-просветительский центр "Оберег"',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'zavojzhje',
		'post_title' => 'Фонд "Нижегородское Заволжье"',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'realworld',
		'post_title' => 'Фотовидеостудия "Реальный мир"',
		'post_content' => 'Текст, пПубликации, фотогалереи', //add some text
		'section' => 'departments',
		'parent' => 0
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'rivercleaners',
		'post_title' => 'Движение "Чистильщики рек"',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0,

	);

	$count = 0;
	foreach($move_to_items as $i_obj) {

		$page_data = array();

		//thumbnail
		$thumb_id = false;
		if(isset($i_obj['thumb'])){
			$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$i_obj['thumb'];
			var_dump($path);

			$test_path = $uploads['path'].'/'.$i_obj['thumb'];
			if(!file_exists($test_path)) {
				$thumb_id = tst_upload_img_from_path($path);
				echo 'Uploaded thumbnail '.$thumb_id.chr(10);
			}
			else {
				$a_url = $uploads['url'].'/'.$i_obj['thumb'];
				$thumb_id = attachment_url_to_postid($a_url);
			}
		}

		$old_page = get_posts(array(
			'post_type' => 'landing',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'name' => $i_obj['slug'],
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows' => false,
			'suppress_filters' => true,
			'fields' => 'ids',
		));

		$page_data['ID'] = ($old_page) ? (int)$old_page[0] : 0;
		$page_data['post_type'] = 'landing';
		$page_data['post_status'] = 'publish';
		$page_data['post_title'] = ($i_obj['post_title']) ? $i_obj['post_title'] : $old_page->post_title;

		if(is_string($i_obj['parent'])){
			$item = get_posts(array('post_type' => 'landing', 'posts_per_page' => 1, 'name' => $i_obj['parent']));
			$page_data['post_parent'] = ($item) ? $item[0]->ID : 0;
		}
		else{
			$page_data['post_parent'] = (int)$i_obj['parent'];
		}

		if(!empty($i_obj['slug'])){
			$page_data['post_name'] = $i_obj['slug'];
		}

		if(empty($i_obj['post_content']) && $old_page) {
			$page_data['post_content'] = $old_page->post_content;

		}
		elseif(false !== strpos($i_obj['post_content'], '.txt')) {
			echo 'Get content from file: '.$i_obj['post_content'].chr(10);

			$content = file_get_contents($i_obj['post_content']);
			if($content){

				//correct urls
				//$home = home_url('/');
				//$content_test = str_replace('http://asi_dev.dev/', $home, $content_test);
				$page_data['post_content'] = $content;
			}

			unset($content);
		}
		else {
			$page_data['post_content'] = $i_obj['post_content'];
		}

		if(isset($i_obj['meta_input'])){
			$page_data['meta_input'] = $i_obj['meta_input'];
			if($thumb_id)
				$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		if(isset($i_obj['menu_order'])) {
			$page_data['menu_order'] = (int)$i_obj['menu_order'];
		}

		$uid = wp_insert_post($page_data);

		$key = $i_obj['section'];

		if($uid && isset($sections[$key])) {
			wp_set_object_terms((int)$uid, $sections[$key]->term_id, 'section');
			wp_cache_flush();
		}

		echo "Update page ".$i_obj['slug'].' new ID '.$uid.chr(10);


		$count++;
	}

	echo 'Updated pages: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	//echo 'Memory '.memory_get_usage(true).chr(10).chr(10);

	$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}p2p");
	$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}p2pmeta");

	$connectons = array(
		'bereginya'		=> array('publications'),
		'sbereg-center' => array('activist', 'health'),
		'sopr'			=> array('researches', 'activist', 'birds'),
		'ornotologlab' 	=> array('researches', 'birds'),
		'lawcenter' 	=> array('law'),
		'striks' 		=> array('education'),
		'reptiles' 		=> array('researches', 'education', 'rares'),
		'jungle' 		=> array('education'),
		'folks' 		=> array('education'),
		'obereg' 		=> array('education', 'urban', 'resources'),
		'zavojzhje' 	=> array('activist', 'oopt'),
		'realworld'		=> array('publications'),
		'rivercleaners'	=> array('activist')
	);

	if(!empty($connectons)) { foreach($connectons as $dep => $connect) {

		$from = get_posts(array('post_type' => 'landing', 'posts_per_page' => 1, 'name' => $dep));
		if($from && !empty($connect)) { foreach($connect as $connect_slug) {
			$to = get_posts(array('post_type' => 'landing', 'posts_per_page' => 1, 'name' => $connect_slug));
			if($to) {
				$c = p2p_type('landing_landing')->connect($from[0]->ID, $to[0]->ID, array('date' => current_time('mysql')));
			}
		}}

	}}

	echo "Connections added ".chr(10);


	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
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