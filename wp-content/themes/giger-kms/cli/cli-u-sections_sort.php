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

	//clear current upload folder
	$path_12 = WP_CONTENT_DIR.'/uploads/2016/12/*';
	array_map('unlink', glob($path_12));

	$move_to_items = array();

	$move_to_items[] = array(
		'ID' => 1241,
		'slug' => 'forum-patients',
		'post_title' => '',
		'post_content' => '',
		'section' => 'resources',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'supervisor_account'),
		'thumb' => 'forum-patient.jpg'
	);

	$move_to_items[] = array(
		'ID' =>  false,
		'slug' => 'events-results',
		'post_title' => 'Итоги мероприятий',
		'post_content' => 'Профильные конференции и мероприятия', //add some text
		'section' => 'resources',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'date_range'),
		'thumb' => 'events.jpg'
	);

	$move_to_items[] = array(
		'ID' => 119,
		'slug' => 'memory-day-2009',
		'post_title' => '',
		'post_content' => '',
		'section' => 'resources',
		'parent' => 'events-results',
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 1400,
		'slug' => 'december-day-2008',
		'post_title' => '',
		'post_content' => '',
		'section' => 'resources',
		'parent' => 'events-results',
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 2,
		'slug' => 'support-group',
		'post_title' => 'Группа взаимопомощи',
		'post_content' => '',
		'section' => 'services',
		'parent' => 0,
		'menu_order' => 100,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'group')
	);

	$move_to_items[] = array(
		'ID' => 196,
		'slug' => '',
		'post_title' => '',
		'post_content' => '',
		'section' => 'services',
		'parent' => 0,
		'join_to' => 2
	);

	$move_to_items[] = array(
		'ID' => 286,
		'slug' => 'group-stat',
		'post_title' => '',
		'post_content' => '',
		'section' => 'services',
		'parent' => 2,
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 222,
		'slug' => 'group-testimonials',
		'post_title' => '',
		'post_content' => '',
		'section' => 'services',
		'parent' => 2,
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 194,
		'slug' => 'group-finance',
		'post_title' => '',
		'post_content' => '',
		'section' => 'services',
		'parent' => 2,
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 104,
		'slug' => 'dating',
		'post_title' => 'Знакомства+',
		'post_content' => '',
		'section' => 'resources',
		'menu_order' => 100,
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'loyalty')
	);

	$move_to_items[] = array(
		'ID' => 46,
		'slug' => 'health-rights',
		'post_title' => '',
		'post_content' => '',
		'section' => 'advices',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'pan_tool')
	);

	$move_to_items[] = array(
		'ID' => 102,
		'slug' => 'hiv-test',
		'post_title' => 'Где сдать анализы',
		'post_content' => 'Карта адресов лабораторий',
		'section' => 'services',
		'parent' => 0,
		'meta_input' => array('icon_id' => 'add_location')
	);

	$move_to_items[] = array(
		'ID' => 106,
		'slug' => 'diagnosis-privacy',
		'post_title' => '',
		'post_content' => '',
		'section' => 'advices',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'opacity')
	);

	$move_to_items[] = array(
		'ID' => 111,
		'slug' => 'books',
		'post_title' => 'Книги и брошюры',
		'post_content' => 'books.txt',
		'section' => 'resources',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'import_contacts')
	);

	$move_to_items[] = array(
		'ID' => 114,
		'slug' => 'therapy',
		'post_title' => 'АРВ-терапия',
		'post_content' => 'АРВ-терапия',
		'section' => 'advices',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'security')
	);

	$move_to_items[] = array(
		'ID' => 96,
		'slug' => 'webinars',
		'post_title' => 'Вебинары',
		'post_content' => '',
		'section' => 'resources',
		'parent' => 0,
		'menu_order' => 90,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'cast')
	);

	$move_to_items[] = array(
		'ID' => 98,
		'slug' => 'co-infections',
		'post_title' => 'Гепатит, туберкулез',
		'post_content' => '',
		'section' => 'advices',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on')
	);

	$move_to_items[] = array(
		'ID' => 100,
		'slug' => '',
		'post_title' => '',
		'post_content' => '',
		'section' => 'advices',
		'parent' => 0,
		'join_to' => 98
	);

	$move_to_items[] = array(
		'ID' => 108,
		'slug' => 'pregnancy',
		'post_title' => 'Беременность+',
		'post_content' => 'pregnancy.txt',
		'section' => 'advices',
		'parent' => 0,
		'menu_order' => 80,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'pregnant_woman')
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'your-question',
		'post_title' => 'Задать вопрос',
		'post_content' => 'Задать вопрос', //add some text
		'section' => 'services',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'forum')
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'no-silence',
		'post_title' => 'Молчание вредит вашему здоровью',
		'post_content' => 'Молчание вредит вашему здоровью', //add some text
		'section' => 'services',
		'parent' => 0,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'speaker_notes_off')
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'first-time',
		'post_title' => 'У меня ВИЧ?',
		'post_content' => 'Только узнали о своем статусе?', //add some text
		'section' => 'advices',
		'parent' => 0,
		'menu_order' => 100,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'accessibility')
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'have-test',
		'post_title' => 'Пройти тест на ВИЧ',
		'post_content' => 'Как пройти тест на ВИЧ и почему это важно', //add some text
		'section' => 'advices',
		'parent' => 0,
		'menu_order' => 90,
		'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'colorize')
	);

	$terms['services'] = get_term_by('slug', 'services', 'section');
	$terms['advices'] = get_term_by('slug', 'advices', 'section');
	$terms['resources'] = get_term_by('slug', 'resources', 'section');

	$count = 0;
	foreach($move_to_items as $i_obj) {

		if(isset($i_obj['join_to']) && $i_obj['ID']){ //join to another page
			$page = get_post($i_obj['ID']);
			$to = get_post($i_obj['join_to']);

			if($to) {
				$content = $to->post_content;
				$content .= '<h4>'.$page->post_title.'</h4>';
				$content .= chr(10).chr(10).$page->post_content;

				preg_match('/<script>(.*?)<\/script>/s',$content, $m);
				if(isset($m[1]) && !empty($m[1])){
					$content = str_replace('<script>'.$m[1].'</script>', '', $content);
				}

				$page_data['ID'] = $to->ID;
				$page_data['post_content'] = $content;
				$uid = wp_update_post($page_data);

				if($uid) {
					wp_delete_post($page->ID);
					wp_cache_flush();

					echo "Update page ".$page->post_title.chr(10);
				}
			}
		}
		else { // create
			$page_data = array();

			//thumbnail
			$thumb_id = false;
			if(isset($i_obj['thumb'])){
				$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$i_obj['thumb'];
				var_dump($path);
				$thumb_id = tst_upload_img_from_path($path);
				echo 'Uploaded thumbnail '.$thumb_id.chr(10);
			}

			$old_page = ($i_obj['ID']) ?  get_post((int)$i_obj['ID']) : false;

			$page_data['ID'] = ($i_obj['ID']) ? (int)$i_obj['ID'] : 0;
			$page_data['post_type'] = 'item';
			$page_data['post_status'] = 'publish';
			$page_data['post_title'] = ($i_obj['post_title']) ? $i_obj['post_title'] : $old_page->post_title;


			if(is_string($i_obj['parent'])){
				$item = get_posts(array('post_type' => 'item', 'posts_per_page' => 1, 'name' => $i_obj['parent']));
				$page_data['post_parent'] = ($item) ? $item[0]->ID : 0;
			}
			else{
				$page_data['post_parent'] = (int)$i_obj['parent'];
			}

			if(!empty($i_obj['slug'])){
				$page_data['post_name'] = $i_obj['slug'];
			}

			if(empty($i_obj['post_content'])) {
				preg_match('/<script>(.*?)<\/script>/s', $old_page->post_content, $m);
				if(isset($m[1]) && !empty($m[1])){
					$page_data['post_content'] = str_replace('<script>'.$m[1].'</script>', '', $old_page->post_content);
				}
				else {
					$page_data['post_content'] = $old_page->post_content;
				}
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

			if($uid && isset($terms[$key])) {
				wp_set_object_terms((int)$uid, $terms[$key]->term_id, 'section');
				wp_cache_flush();
			}

			$n = ($i_obj['ID']) ? $i_obj['ID'] : $i_obj['slug'];
			echo "Update page ".$n.' new ID '.$uid.chr(10);
		}

		$count++;
	}

	echo 'Updated pages: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	//echo 'Memory '.memory_get_usage(true).chr(10).chr(10);





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