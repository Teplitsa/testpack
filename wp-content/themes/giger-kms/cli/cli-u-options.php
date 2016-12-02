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
		$target = 'http://'.$target.'/core';
		$wpdb->query($wpdb->prepare("UPDATE $wpdb->options SET option_value = %s WHERE option_name = 'siteurl'", $target));
	}


	//active theme
	update_option('current_theme', 'NewLife Theme');
	update_option('template', 'giger-kms');
	update_option('stylesheet', 'giger-kms');

	delete_site_transient('theme_roots');
	delete_site_transient('timeout_theme_roots');
	delete_site_transient('update_themes');

	echo 'Updated active theme settings'.chr(10);

	//permalinks
	echo 'Updating permalink structure'.chr(10);
	update_option('permalink_structure', "/news/%year%/%monthnum%/%day%/%postname%/");
	update_option('category_base', "");
	update_option('tag_base', "");
	update_option('posts_per_page', 15);

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
		"leyka/leyka.php",
		"pdf-viewer/pdf-viewer.php",
		"post-type-converter/post-type-converter.php",
		"responsive-lightbox/responsive-lightbox.php",
		"term-management-tools/term-management-tools.php",
		"wordpress-seo/wp-seo.php"
	);

	update_option('active_plugins', $active);
	echo 'Active plugins updated'.chr(10);


	//Update Error 404 text
	$err_link = home_url('contacts');
	$err_text = "<p>К сожалению, эта страница отсутствует! Ошибка 404.</p><p>Если вы перешли сюда по ссылке, пожалуйста, <a href='{$err_link}'>дайте нам знать</a>, чтобы мы исправили это как можно быстрее.</p>";
	update_option('er_text', $err_text);

	//Update theme options
	$footer_text = "";
	set_theme_mod('footer_text', $footer_text );

	echo 'Theme options updated for Error404 and Footer text'.chr(10);

	//No admin username
	$wpdb->update($wpdb->users, array('user_login' => 'awedlog'), array('ID' => 1), array('%s'), array('%d'));


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