<?php
/** Update post_type/term structure 
 *
 *  permalink structure
 *  sections
 *  category tree
 *  build main menu
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	// Sections
	echo 'Creating sections'.chr(10);
	// sections import implemented in landings import from csv
	$sections = array(
		'Наша работа'	=> 'work',
		'Экопроблемы'	=> 'ecoproblems',
		'Подразделения'	=> 'departments',
		'О нас'			=> 'about',
		'Как помочь'	=> 'supportus',
	);

	//print_r(get_taxonomies());

	$count = 0;

	foreach($sections as $s_name => $s_key) {
	    
	    $res = get_term_by( 'slug', $s_key, 'section' );
	    
	    if( !$res ) {
	        $res = wp_insert_term( $s_name, 'section', array( 'slug' => $s_key ) );
	        if(!is_wp_error($res)){
	            $count++;
	            echo "Section created: ".$s_name.chr(10);
	        }
	        else {
	            echo $res->get_error_message();
	        }
	    }
	    else {
	        printf( "Section exist: %s - %s\n", $s_name, $s_key );
	    }
	    
	    echo "\n";
	}

	echo $count." sections created. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

	wp_cache_flush();

	//Cleanup
	echo 'Flush rewrite rules'.chr(10);
	flush_rewrite_rules(false);


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