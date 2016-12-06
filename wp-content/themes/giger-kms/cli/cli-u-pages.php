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
	}

	unset($homepage);
	unset($update);

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
	if($about && $contact) {
		$content = $about->post_content;
		$content .= '<h4>'.$contact->post_title.'</h4>';
		$content .= chr(10).chr(10).$contact->post_content;

		preg_match('/<script>(.*?)<\/script>/s',$content, $m);
		if(isset($m[1]) && !empty($m[1])){
			$content = str_replace('<script>'.$m[1].'</script>', '', $content);
		}

		$page_data = array();
		$page_data['ID'] = $about->ID;
		$page_data['post_title'] = $about->post_title;
		$page_data['post_content'] = $content;
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_name'] = 'about-us';

		$uid = wp_insert_post($page_data);

		if($uid) {
			wp_delete_post($contact->ID);
			wp_cache_flush();
		}
	}

	echo "Update About page ".chr(10);

	// Upd for partners
	if($partners) {
		$page_data = array();
		$page_data['ID'] = $partners->ID;
		$page_data['post_title'] = $partners->post_title;
		$page_data['post_parent'] = 0;
		$page_data['post_type'] = 'page';
		$page_data['post_content'] = $partners->post_content;
		$page_data['post_status'] = 'publish';

		$uid = wp_insert_post($page_data);
	}

	echo "Update Partners page ".chr(10);

	// Update for projects
	if($projects) {
		$page_data = array();
		$page_data['ID'] = $projects->ID;
		$page_data['post_title'] = 'Проекты';
		$page_data['post_parent'] = 0;
		$page_data['post_content'] = 'Проекты и мероприятия, реализованные АНО "Новая жизнь"';
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_name'] = 'projects';

		$uid = wp_insert_post($page_data);
	}

	echo "Update Projects page ".chr(10);

	// Aadd to about section
	if($about_section) {
		wp_set_object_terms($about->ID, $about_section->term_id, 'section');
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

		$add = file_get_contents('complex.txt');
		if($add)
			$page_data['post_content'] .= chr(10).chr(10).$add;

		$uid = wp_insert_post($page_data);
	}

	echo "Updated Complex programmm page page ".chr(10);


	//Impport projects
	$handle = file('projects.tsv');
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

	//Sitemap
	$join = get_page_by_path('join-us');
	if(!$join) {
		$page_data = array();

		$page_data['ID'] = 0;
		$page_data['post_type'] = 'page';
		$page_data['post_status'] = 'publish';
		$page_data['post_parent'] = 0; //all top level
		$page_data['post_title'] = 'Присоединяйся';
		$page_data['post_name'] = 'join-us';
		$page_data['post_content'] = 'Присоединяйся к группе и помоги нашей работе';

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