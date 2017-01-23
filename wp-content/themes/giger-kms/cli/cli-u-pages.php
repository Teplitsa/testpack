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

	$uploads = wp_upload_dir();

	//Homepage
	echo 'Create static homepage'.chr(10);
	$homepage = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_title = %s", 'Главная'));
	$update = array(
		'ID' 			=> ($homepage) ? $homepage->ID : 0,
		'post_title' 	=> 'Главная',
		'post_type' 	=> 'page',
		'post_name' 	=> 'homepage',
		'post_status'	=> 'publish',
		'meta_input'	=> array('_wp_page_template' => 'page-home.php'),
		'post_content'	=> '<h4>АНО "Новая жизнь"</h4><p>Организация создана в 2002 году лидерами сообщества людей, живущих с ВИЧ с целью  всесторонней поддержки ВИЧ-позитивных людей и защиты их интересов на местном и региональном уровне.</p>'
	);

	$home_id = wp_insert_post($update);

	if($home_id){
		update_option('show_on_front', 'page');
		update_option('page_on_front', (int)$home_id);


		//Homepage settings
		$blocks = array(
			'block_one' => array('У меня ВИЧ?', 'Группа взаимопомощи', 'Задать вопрос', 'Где сдать анализы', 'Вебинары'),
			'block_two' => array('Книги и брошюры', 'Молчание вредит вашему здоровью', 'Итоги мероприятий'),
			'infosup_one' => array('Пройти тест на ВИЧ'),
			'infosup_two' => array('Знакомства+', 'Беременность+'),
			'infosup_three' => array('Право на здоровье', 'Тайна диагноза'),
			'infosup_four' => array('АРВ-терапия', 'Гепатит, туберкулез'),
		);

		foreach($blocks as $key => $names) {

			$ids = array();
			foreach($names as $n) {
				$p = get_page_by_title($n, OBJECT, 'item');
				if($p)
					$ids[] = $p->ID;
			}
			$ids = implode(',', $ids);
			update_post_meta($home_id, $key, $ids);
		}
	}

	unset($homepage);
	unset($update);
	unset($ids);

	echo 'Homepage updated. Total time in sec: '.(microtime(true) - $time_start).chr(10);

	// About
	$about = get_post(39);
	$contact = get_post(182);
	$partners = get_post(117); //update content
	$projects = get_post(33);
	$about_section = get_term_by('slug', 'about', 'section');

	// Delete empty page - принципы
	wp_delete_post(219);

	//Add contacts to about
	if($about) {

		//thumbnail id
		$thumb_id = false;
		//$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/about.jpg';
		//var_dump($path);
		//
		//$test_path = $uploads['path'].'/about.jpg';
		//if(!file_exists($test_path)) {
		//	$thumb_id = tst_upload_img_from_path($path, trim($about->post_title).' заставка');
		//	echo 'Uploaded thumbnail '.$thumb_id.chr(10);
		//}
		//else {
		//	$a_url = $uploads['url'].'/about.jpg';
		//	$thumb_id = attachment_url_to_postid($a_url);
		//	if(!$thumb_id) {
		//		$thumb_id = tst_register_uploaded_file($test_path, trim($about->post_title).' заставка');
		//	}
		//}

		$page_data = array();
		$page_data['ID'] = $about->ID;
		$page_data['post_title'] = $about->post_title;
		$page_data['post_content'] = file_get_contents('data/about.txt');
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_name'] = 'about-us';
		$page_data['meta_input'] = array('_wp_page_template' => 'page-about.php', 'icon_id' => 'library_books');

		if($thumb_id){
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		$uid = wp_insert_post($page_data);
	}

	if($contact) {
		wp_delete_post($contact->ID);
		wp_cache_flush();
	}

	echo "Update About page ".chr(10);

	// Upd for partners
	if($partners) {

		//thumbnail id
		$thumb_id = false;
		//$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/partners.jpg';
		//var_dump($path);
		//
		//$test_path = $uploads['path'].'/partners.jpg';
		//if(!file_exists($test_path)) {
		//	$thumb_id = tst_upload_img_from_path($path, trim($partners->post_title).' заставка');
		//	echo 'Uploaded thumbnail '.$thumb_id.chr(10);
		//}
		//else {
		//	$a_url = $uploads['url'].'/partners.jpg';
		//	$thumb_id = attachment_url_to_postid($a_url);
		//	if(!$thumb_id) {
		//		$thumb_id = tst_register_uploaded_file($test_path, trim($partners->post_title).' заставка');
		//	}
		//}

		$page_data = array();
		$page_data['ID'] = $partners->ID;
		$page_data['post_title'] = $partners->post_title;
		$page_data['post_parent'] = 0;
		$page_data['post_type'] = 'page';
		$page_data['post_content'] = file_get_contents('data/partners.txt');
		$page_data['post_status'] = 'publish';
		$page_data['meta_input'] = array('_wp_page_template' => 'page-about.php', 'icon_id' => 'contacts');
		if($thumb_id){
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		$uid = wp_insert_post($page_data);
	}

	echo "Update Partners page ".chr(10);

	// Update for projects
	if($projects) {

		//thumbnail id
		$thumb_id = false;
		//$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/projects.jpg';
		//var_dump($path);
		//
		//$test_path = $uploads['path'].'/projects.jpg';
		//if(!file_exists($test_path)) {
		//	$thumb_id = tst_upload_img_from_path($path, trim($projects->post_title).' заставка');
		//	echo 'Uploaded thumbnail '.$thumb_id.chr(10);
		//}
		//else {
		//	$a_url = $uploads['url'].'/projects.jpg';
		//	$thumb_id = attachment_url_to_postid($a_url);
		//	if(!$thumb_id) {
		//		$thumb_id = tst_register_uploaded_file($test_path, trim($projects->post_title).' заставка');
		//	}
		//}

		$page_data = array();
		$page_data['ID'] = $projects->ID;
		$page_data['post_title'] = 'Проекты';
		$page_data['post_parent'] = 0;
		$page_data['post_content'] = 'Проекты и мероприятия, реализованные АНО "Новая жизнь"';
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_name'] = 'our-projects';
		$page_data['meta_input'] = array('_wp_page_template' => 'page-about.php', 'icon_id' => 'business_center');

		if($thumb_id){
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
		}

		$uid = wp_insert_post($page_data);
	}

	echo "Update Projects page ".chr(10);

	// Add to about section
	if($about_section) {
		wp_set_object_terms($about->ID,    $about_section->term_id, 'section');
		wp_set_object_terms($partners->ID, $about_section->term_id, 'section');
		wp_set_object_terms($projects->ID, $about_section->term_id, 'section');

		wp_cache_flush();
	}

	//Projects
	$sub_programm = get_post(1403);

	if($sub_programm) {
		$page_data = array();

		$page_data['ID'] = $sub_programm->ID;
		$page_data['post_type'] = 'project';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = 'Комплексная помощь семьям с детьми, затронутыми ВИЧ';
		$page_data['post_excerpt'] = $sub_programm->post_title;
		$page_data['post_name'] = 'complex-help';

		preg_match('/<script>(.*?)<\/script>/s', $sub_programm->post_content, $m);
		if(isset($m[1]) && !empty($m[1])){
			$page_data['post_content'] = str_replace('<script>'.$m[1].'</script>', '', $sub_programm->post_content);
		}
		else {
			$page_data['post_content'] = $sub_programm->post_content;
		}

		$add = file_get_contents('data/complex.txt');
		if($add)
			$page_data['post_content'] .= chr(10).chr(10).$add;

		$uid = wp_insert_post($page_data);
	}

	echo "Updated Complex programmm page page ".chr(10);


	//Impport projects
	$handle = file('data/projects.tsv');
	$csv = array();
	if($handle) { foreach($handle as $line) {
		//$csv = array_map('str_getcsv', file('projects.csv'));
		$csv[] = str_getcsv($line, "\t");
	}}

	var_dump(count($csv));

	$count = 0;
	foreach($csv as $i => $line) {

		if($i == 0)
			continue;

		$page_data = array();

		$page_data['ID'] = 0;
		$page_data['post_type'] = 'project';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = $line[0];
		$page_data['post_excerpt'] = $line[1];
		$page_data['post_content'] = $line[2];

		$uid = wp_insert_post($page_data);
		if($uid)
			$count++;
	}

	echo "Imported projects ".$count.chr(10);

	//Sitemap
	$sitemap = get_page_by_path('sitemap');
	if(!$sitemap) {
		$page_data = array();

		$page_data['ID'] = 0;
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = 'Карта сайта';
		$page_data['post_name'] = 'sitemap';
		$page_data['post_content'] = '[tst_sitemap]';

		$uid = wp_insert_post($page_data);
	}

	echo "Sitemap page created ".$count.chr(10);

	//Join
	$join = get_page_by_path('join-us');
	if(!$join) {
		$page_data = array();

		$page_data['ID'] = 0;
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = 'Вступай';
		$page_data['post_name'] = 'join-us';
		$page_data['post_content'] = file_get_contents('data/join.txt');
		$page_data['meta_input'] = array('_wp_page_template' => 'page-join.php');

		$uid = wp_insert_post($page_data);
	}


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