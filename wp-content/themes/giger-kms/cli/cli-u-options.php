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
	update_option('current_theme', 'NewLife Theme');
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

	echo "Updated. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

	//Google Map API
	update_option('google_maps_api_key', 'AIzaSyDILNijQ-sT4LR_OPrQc_UpT_1AnFkQ8Oc'); //change to real one
	echo 'Updated Google Map API Key'.chr(10);

	//Google Analytics Keys
	//update_option('google_analytics_key', 'UA-80377406-5');

	//Update active plugins list
	$active = array(
		"cmb2-post-search-field/cmb2_post_search_field.php",
		"cmb2/init.php",
		"cmb_field_map/cmb-field-map.php",
		"crop-thumbnails/crop-thumbnails.php",
		"cyr3lat/cyr-to-lat.php",
		"disable-comments/disable-comments.php",
		"pdf-viewer/pdf-viewer.php",
		"post-type-converter/post-type-converter.php",
		"responsive-lightbox/responsive-lightbox.php",
		"term-management-tools/term-management-tools.php",
		"wordpress-seo/wp-seo.php",
	    "formidable-forms/formidable.php"
	);

	update_option('active_plugins', $active);
	echo 'Active plugins updated'.chr(10);

	//Leyka options ???

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

	$header_text = '<span>Звоните</span> +7(951)031-56-56';
	update_option('header_text', $header_text);

	echo 'Theme options updated for Error404 and Header / Footer text'.chr(10);

	//No admin username
	$wpdb->update($wpdb->users, array('user_login' => 'awedlog', 'display_name' => 'Елена'), array('ID' => 1), array('%s', '%s'), array('%d'));

	// formidable options
	update_option( 'frm_options', 'O:11:"FrmSettings":33:{s:11:"option_name";s:11:"frm_options";s:4:"menu";s:10:"Формы";s:7:"mu_menu";i:0;s:15:"preview_page_id";i:0;s:8:"use_html";s:1:"1";s:10:"jquery_css";i:0;s:12:"accordion_js";i:0;s:11:"success_msg";s:48:"Форма отправлена. Спасибо!";s:9:"blank_msg";s:48:"Поле необходимо заполнить";s:10:"unique_msg";s:26:"This value must be unique.";s:11:"invalid_msg";s:74:"Во время отправки формы возникли ошибки!";s:10:"failed_msg";s:48:"Вы уже отправили эту форму";s:12:"submit_value";s:18:"Отправить";s:9:"login_msg";s:45:"You do not have permission to view this form.";s:16:"admin_permission";s:37:"You do not have permission to do that";s:8:"email_to";s:13:"[admin_email]";s:10:"load_style";s:3:"all";s:12:"custom_style";b:1;s:6:"pubkey";s:0:"";s:7:"privkey";s:0:"";s:7:"re_lang";s:0:"";s:6:"re_msg";s:39:"The reCAPTCHA was not entered correctly";s:8:"re_multi";i:0;s:14:"frm_view_forms";a:1:{i:0;s:13:"administrator";}s:14:"frm_edit_forms";a:1:{i:0;s:13:"administrator";}s:16:"frm_delete_forms";a:1:{i:0;s:13:"administrator";}s:19:"frm_change_settings";a:1:{i:0;s:13:"administrator";}s:16:"frm_view_entries";a:1:{i:0;s:13:"administrator";}s:18:"frm_delete_entries";a:1:{i:0;s:13:"administrator";}s:18:"frm_create_entries";s:13:"administrator";s:16:"frm_edit_entries";s:13:"administrator";s:16:"frm_view_reports";s:13:"administrator";s:17:"frm_edit_displays";s:13:"administrator";}' );
	
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