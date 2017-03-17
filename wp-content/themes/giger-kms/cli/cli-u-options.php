<?php
/**
 * Options values
 * set them all in the beginnings
 **/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//site url
	if(defined('WP_CONTENT_URL') && WP_CONTENT_URL){
		$target = str_replace(array('//', '/wp-content', 'http://', 'https://'), '', WP_CONTENT_URL);
		var_dump($target);
		$target = untrailingslashit($target);
		$siteurl = 'http://'.$target.'/core';
		$home = 'http://'.$target;

		$wpdb->query($wpdb->prepare("UPDATE $wpdb->options SET option_value = %s WHERE option_name = 'siteurl'", $siteurl));
		$wpdb->query($wpdb->prepare("UPDATE $wpdb->options SET option_value = %s WHERE option_name = 'home'", $home));
	}

	//active theme
	update_option('current_theme', 'Dront Theme');
	update_option('template', 'giger-kms');
	update_option('stylesheet', 'giger-kms');

	delete_site_transient('theme_roots');
	delete_site_transient('timeout_theme_roots');
	delete_site_transient('update_themes');
	delete_option('site_icon'); //remove old favicon

	echo 'Updated active theme settings'.chr(10);

	//permalinks
	echo 'Updating permalink structure'.chr(10);
	update_option('permalink_structure', "/news/%year%/%monthnum%/%day%/%postname%/");
	update_option('category_base', "");
	update_option('tag_base', "");
	update_option('posts_per_page', 10);
	update_option('posts_per_rss', 20);

	echo "Updated. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

	//Google Map API
	update_option('google_maps_api_key', 'AIzaSyDILNijQ-sT4LR_OPrQc_UpT_1AnFkQ8Oc'); //change to real one
	echo 'Updated Google Map API Key'.chr(10);

	//Google Analytics Keys
	//update_option('google_analytics_key', 'UA-80377406-5');


	//Update active plugins list

	$active = array(
		"cmb2/init.php",
		"cmb_field_map/cmb-field-map.php",
		"crop-thumbnails/crop-thumbnails.php",
		"cyr3lat/cyr-to-lat.php",
		"disable-comments/disable-comments.php",
		"formidable/formidable.php",
		"pdf-viewer/pdf-viewer.php",
		"posts-to-posts/posts-to-posts.php",
		"responsive-lightbox/responsive-lightbox.php",
		"wordpress-seo/wp-seo.php"
	);

	//update_option('active_plugins', $active);

	// Leyka options where updates needed:
    $leyka_options = array(
        'pm_order' => 'pm_order[]=cp-card&amp;pm_order[]=quittance-bank_order&amp;pm_order[]=text-text_box',
        'quittance-bank_order_label' => 'Платёжная квитанция',
        'cp-card_label' => 'Банковская карта',
        'cp_public_id' => 'pk_ffb1e444b4f84a720234fcdde8f45',
        'cp_test_mode' => '1',
        'text-text_box_description' => '',
        'text_box_details' => 'Реквизиты для банковских переводов:&lt;br&gt;&lt;ul&gt; &lt;li&gt;ОГРН: 1025200007568&lt;/li&gt; &lt;li&gt;КПП: 526001001&lt;/li&gt; &lt;li&gt;ИНН: 5260100542&lt;/li&gt; &lt;li&gt;Расчётный счёт: &lt;/li&gt; &lt;li&gt;Банк: ДО &quot;Окский&quot; Филиала &quot;Нижегородский&quot; АО &quot;АЛЬФA-БАHК&quot;&lt;/li&gt; &lt;li&gt;БИК: 042202824&lt;/li&gt; &lt;li&gt;Корр. счёт: 30101810200000000824&lt;/li&gt; &lt;/ul&gt;',
        'text-text_box_label' => 'Реквизиты для перевода',
        'org_full_name' => 'Нижегородское региональное отделение Международной общественной организации «Международный Социально-экологический союз»',
        'org_face_fio_ip' => 'Каюмов Асхат Абдурахманович',
        'org_face_fio_rp' => 'Каюмова Асхата Абдурахмановича',
        'org_face_position' => 'исполнительный директор',
        'org_address' => '603001, Нижний Новгород, ул. Рождественская, д. 16Д',
        'org_state_reg_number' => '1025200007568',
        'org_kpp' => '526001001',
        'org_inn' => '5260100542',
        'org_bank_account' => '40703810329120000011',
        'org_bank_name' => 'ДО &quot;Окский&quot; Филиала &quot;Нижегородский&quot; АО &quot;АЛЬФA-БАHК&quot;',
        'org_bank_bic' => '042202824',
        'org_bank_corr_account' => '30101810200000000824',
        'pm_available' => 'a:3:{i:0;s:7:"cp-card";i:1;s:20:"quittance-bank_order";i:2;s:13:"text-text_box";}',
        'currency_rur_label' => '₽',
        'agree_to_terms_text_text_part' => 'Я соглашаюсь с',
        'agree_to_terms_text_link_part' => 'условиями сервиса сбора пожертвований',
        'terms_agreed_by_default' => '1',
        'donation_submit_text' => 'Поддержать',
        'donation_sum_field_type' => 'mixed',
        'donations_history_under_forms' => '0',
        'show_campaign_sharing' => '0',
    );

    echo "Leyka options update began...\n";
    foreach($leyka_options as $name => $value) {
        $wpdb->query($wpdb->prepare("UPDATE $wpdb->options SET option_value = %s WHERE option_name = 'leyka_$name'", $value));
    }
    echo "Leyka updates finished. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

	//Plugin options
	$disable_comm = maybe_unserialize('a:5:{s:19:"disabled_post_types";a:3:{i:0;s:4:"post";i:1;s:4:"page";i:2;s:10:"attachment";}s:17:"remove_everywhere";b:1;s:9:"permanent";b:0;s:16:"extra_post_types";b:0;s:10:"db_version";i:6;}');
	update_option('disable_comments_options', $disable_comm);

	$lightbox = maybe_unserialize('a:18:{s:6:"script";s:8:"swipebox";s:8:"selector";s:8:"lightbox";s:9:"galleries";b:1;s:18:"gallery_image_size";s:4:"full";s:19:"gallery_image_title";s:7:"default";s:20:"force_custom_gallery";b:1;s:28:"woocommerce_gallery_lightbox";b:0;s:6:"videos";b:0;s:11:"image_links";b:1;s:11:"image_title";s:7:"caption";s:17:"images_as_gallery";b:1;s:19:"deactivation_delete";b:0;s:13:"loading_place";s:6:"footer";s:19:"conditional_loading";b:1;s:20:"enable_custom_events";b:0;s:13:"custom_events";s:12:"ajaxComplete";s:14:"update_version";i:1;s:13:"update_notice";b:0;}');
	update_option('responsive_lightbox_settings', $lightbox);


	//Update Error 404 text
	$err_link = home_url('contacts');
	$err_text = "<p>К сожалению, эта страница отсутствует! Ошибка 404.</p><p>Если вы перешли сюда по ссылке, пожалуйста, <a href='{$err_link}'>дайте нам знать</a>, чтобы мы исправили это как можно быстрее.</p>";
	update_option('er_text', $err_text);

	//Update theme options
	$footer_text = "";
	update_option('footer_text', $footer_text );

	echo 'Theme options updated for Error404 and Header / Footer text'.chr(10);

	/** Formidable **/
	//Formidable Translation files
	$path_from = BASE_PATH.'artifacts/';
	$path_to = BASE_PATH.'wp-content/languages/plugins/';
	
	/** WDS Simple Page Builder **/
	//WDS Simple Page Builder Translation files
	$wds_path_from = BASE_PATH.'artifacts/wds/';
	$wds_path_to = BASE_PATH.'wp-content/plugins/wds-simple-page-builder/languages/';

	if(!file_exists($path_to)){
		mkdir($path_to, 0775, true);
	}
	
	if(!file_exists($wds_path_to)){
		mkdir($wds_path_to, 0775, true);
	}

	copy($path_from.'formidable-ru_RU.mo' , $path_to.'formidable-ru_RU.mo');
	copy($path_from.'formidable-ru_RU.po' , $path_to.'formidable-ru_RU.po');
	echo "Formidable translation files moved".chr(10);

	copy($wds_path_from.'wds-simple-page-builder-ru_RU.mo' , $wds_path_to.'wds-simple-page-builder-ru_RU.mo');
	copy($wds_path_from.'wds-simple-page-builder-ru_RU.po' , $wds_path_to.'wds-simple-page-builder-ru_RU.po');
	echo "WDS Simple Page Builder translation files moved".chr(10);

	//delete transients
	delete_transient('frm_options');
	delete_transient('frmpro_options');

	$options = array_map('str_getcsv', file('formidable-opt.csv'));

	if(!empty($options)){

		foreach($options as $line) {

			$key = $line[0];
			$opt = $line[1];

			echo "Updated key ".$key.chr(10);
			$test = get_option($key);

			if(!$test){
				$wpdb->insert($wpdb->options, array('option_name' => $key, 'option_value' => $opt), array('%s', '%s'));
			}
			else {
				$wpdb->update($wpdb->options, array('option_value' => $opt), array('option_name' => $key), array('%s'), array('%s'));
			}

		}
	}

	echo "Formidable settings imported".chr(10);

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