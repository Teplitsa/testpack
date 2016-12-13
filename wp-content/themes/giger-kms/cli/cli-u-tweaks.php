<?php
/**
 * Various DB tweaks that we forget include into proper stage
 * or they appears just a bit later
 *
 **/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;


	/** Formidable **/
	//Formidable Translation files
	$path_from = BASE_PATH.'artifacts/';
	$path_to = BASE_PATH.'wp-content/languages/plugins/';

	if(!file_exists($path_to)){
		mkdir(dirname($path_to), 0775, true);
	}

	copy($path_from.'formidable-ru_RU.mo' , $path_to.'formidable-ru_RU.mo');
	copy($path_from.'formidable-ru_RU.po' , $path_to.'formidable-ru_RU.po');
	echo "Formidable translation files moved".chr(10);

	//Update existing from keys
	$wpdb->update($wpdb->prefix."frm_forms", array('form_key' => 'contactus'), array('id' => 7), array('%s'), array('%d'));
	$wpdb->update($wpdb->prefix."frm_forms", array('form_key' => 'notify'), array('id' => 6), array('%s'), array('%d'));

	//delete transients
	delete_transient('frm_options');
	delete_transient('frmpro_options');

	$options = file_get_contents('formidable-opt.txt');
	if(!empty($options)){
		$options = maybe_unserialize($options);

		if(is_array($options)){
			foreach($options as $key => $opt) {
				echo "Updated key ".$key.chr(10);
				update_option($key, $opt);
			}
		}
	}

	echo "Formidable settings imported".chr(10);

	//Change category slugs
	$changes = array(
		'blagotvoritel-nost-i-dobrovol-chestvo' => 'charity',
		'gorod' 								=> 'urban',
		'granty-i-konkursy' 					=> 'grants',
		'zdorov-e' 								=> 'health',
		'invalidy' 								=> 'handicap',
		'korporativnaya-otvetstvennost' 		=> 'csr',
		'kul-tura-i-prosveshhenie' 				=> 'culture',
		'nekommercheskij-sektor' 				=> 'nonprofit',
		'obrazovanie' 							=> 'education',
		'okruzhayushhaya-sreda' 				=> 'ecology',
		'prava-cheloveka' 						=> 'human-rights',
		'sem-ya-i-deti' 						=> 'family',
		'starshee-pokolenie' 					=> 'ageing'
	);

	$cats = get_terms(array('taxonomy' => 'category', 'hide_empty' => 0));
	if(!empty($cats)) { foreach($cats as $c){

		if(isset($changes[$c->slug])){
			wp_update_term($c->term_id, $c->taxonomy, array('slug' => $changes[$c->slug]));
			wp_cache_flush();
		}
	}}

	echo 'Changed category slugs'.chr(10);



	//regenerate permalinks
	flush_rewrite_rules();

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
