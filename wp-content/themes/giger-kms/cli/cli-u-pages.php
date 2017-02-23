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
	include( get_template_directory() . '/inc/class-import.php' );

	//upload folder
	$uploads = wp_upload_dir();

	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	// Sections
	$about_section = get_term_by('slug', 'about', 'section');
	$support_section = get_term_by('slug', 'supportus', 'section');

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
		'post_content'	=> 'Экологический центр «Дронт» был создан в 1989 году для осуществления различных природоохранных программ и проектов.</p>'
	);

	$home_id = wp_insert_post($update);

	if($home_id){
		update_option('show_on_front', 'page');
		update_option('page_on_front', (int)$home_id);
	}

	unset($homepage);
	unset($update);

	echo 'Homepage updated. Total time in sec: '.(microtime(true) - $time_start).chr(10);

	//news
	echo 'Create static news page'.chr(10);
	$news = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_title = %s", 'Новости'));
	$update = array(
		'ID' 			=> ($news) ? $news->ID : 0,
		'post_title' 	=> 'Новости',
		'post_type' 	=> 'page',
		'post_name' 	=> 'news',
		'post_status'	=> 'publish',
		'post_content'	=> ''
	);

	$news_id = wp_insert_post($update);

	if($news_id){
		update_option('page_for_posts', (int)$news_id);

		wp_set_object_terms((int)$news_id, $about_section->term_id, 'section');
		wp_cache_flush();
	}

	unset($homepage);
	unset($update);

	echo 'Homepage updated. Total time in sec: '.(microtime(true) - $time_start).chr(10);

	// Upd for about page
	$page_title = 'Об экоцентре';
	$about_page = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_title = %s", $page_title));
	
	$page_data = array();
	$page_data['ID'] = $about_page ? $about_page->ID : 0;
	$page_data['post_title'] = $page_title;
	$page_data['post_parent'] = 0;
	$page_data['post_type'] = 'page';
	$page_data['post_content'] = file_get_contents('data/txt/about.txt');
	$page_data['post_status'] = 'publish';
	//$page_data['meta_input'] = array('_wp_page_template' => 'page-about.php');

	$uid = wp_insert_post($page_data);
	if($uid && $about_section) {
		wp_set_object_terms((int)$uid, $about_section->term_id, 'section');
		wp_cache_flush();
	}

	echo "Update About page ".chr(10);
	unset($uid);

	$pages = array(
		'team' => array(
			'post_data' => array(
				'post_title' => 'Наши люди',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => 'Наша команда',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'about'
		),
		'about-dront' => array(
			'post_data' => array(
				'post_title' => 'Cудьба дронта',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => 'import | http://dront.ru/dront/',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'about'
		),
		'reports' => array(
			'post_data' => array(
				'post_title' => 'Отчеты',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => 'Наши отчеты',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'about'
		),
		'contacts' => array(
			'post_data' => array(
				'post_title' => 'Контакты',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => file_get_contents('data/txt/contacts.txt'),
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'about'
		),
		'volunteers' => array(
			'post_data' => array(
				'post_title' => 'Стань волонтером',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => 'Оставить заявку и стать волонтером',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'supportus'
		),
		'company' => array(
			'post_data' => array(
				'post_title' => 'Помощь компаний',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => 'Оставить заявку и стать волонтером',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => 'supportus'
		),
		'sitemap' => array(
			'post_data' => array(
				'post_title' => 'Карта сайта',
				'post_type' => 'page',
				'post_parent' => 0,
				'post_status' => 'publish',
				'post_content' => '[tst_sitemap]',
				//'meta_input' => array('_wp_page_template' => 'page-about.php')
			),
			'section' => ''
		)
	);

	foreach($pages as $slug => $obj) {

		$page_data = $obj['post_data'];

		if(false !== strpos($page_data['post_content'], 'import | ')) {
			echo "Import fromt URL ".$page_data['post_content'].chr(10);

			$old_url = str_replace('import | ', '', $page_data['post_content']);
			$old_post = TST_Import::get_instance()->get_post_by_old_url($old_url);

			if($old_post) {
				$page_data['post_content'] = $old_post->post_content;
			}
		}

		$test = get_page_by_path($slug);
		$page_data['ID'] = ($test) ? $test->ID: 0;
		$page_data['post_name'] = $slug;
		$uid = tst_get_pb_post( $page_data, 'page' );

		if($uid && isset($obj['section']) && !empty($obj['section'])) {
			$section = ($obj['section'] == 'supportus') ? $support_section : $about_section;

			wp_set_object_terms((int)$uid, $section->term_id, 'section');
			wp_cache_flush();
		}
	}


	//Thumbnails
	$thumbnails = array(
		'news' 				=> 'news2.jpg',
		'nashi-lyudi'		=> 'nashi-lyudi.jpg',
		'otchety' 			=> 'otchety.jpg',
		'stan-volonterom'	=> 'stan-volonterom.jpg',
		'pomoshh-kompanij'	=> 'pomoshh-kompanij.jpg'
	);

	foreach($thumbnails as $slug => $file) {

		$thumb_id = false;
		$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$file;
		var_dump($path);

		$test_path = $uploads['path'].'/'.$file;
		if(!file_exists($test_path)) {
			$thumb_id = tst_upload_img_from_path($path);
			echo 'Uploaded thumbnail '.$thumb_id.chr(10);
		}
		else {
			$a_url = $uploads['url'].'/'.$file;
			$thumb_id = attachment_url_to_postid($a_url);
		}

		$page = get_page_by_path($slug);
		if($page && $thumb_id){
			update_post_meta($page->ID, '_thumbnail_id', (int)$thumb_id);
		}

		echo 'Updated thumbnail for page '.$slug.chr(10);
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