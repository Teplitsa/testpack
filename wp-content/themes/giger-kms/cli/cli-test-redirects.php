<?php

set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $is_skip_first_line = True;

    $time_start = microtime(true);
    include('cli_common.php');

    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $options = getopt("", array('file:'));
    $file = isset($options['file']) ? $options['file'] : '';
    
    if( !$file ) {
        die( "File not defined!\n\n" );
    }
    
    if( !is_file( $file ) ) {
        die( sprintf( "File not found: %s\n\n", $file ) );
    }
    
    $handle = @fopen($file, "r");
    
    $ngo2_auth_cred = "test:testhouse";
    $old_site_home = 'http://dront.ru';
    
    $opts = array(
        TST_URL::get_site_protocol() => array(
            'method'=>"GET",
            'header' => "Authorization: Basic " . base64_encode($ngo2_auth_cred)
        )
    );
    
    $site_domain_with_path = TST_URL::get_site_domain_with_path();
    if( preg_match( '/ngo2\.ru/', $site_domain_with_path ) ) {
        $new_site_home = 'https://' . $ngo2_auth_cred . '@' . $site_domain_with_path;
    }
    else {
        $new_site_home = TST_URL::add_protocol( '//' . $site_domain_with_path );
        unset( $opts[ TST_URL::get_site_protocol() ]['header'] );
    }
    
//     print_r( $new_site_home );
    
    $context = stream_context_create($opts);
    
    $output = fopen('php://output', 'w');
    if ($handle) {
    	$counter = 0;
        while(($url = fgets($handle, 4096)) !== false) {
    		$url = trim($url);
    		
    		if( !$url ) {
    			continue;
    		}

    		$old_url = str_replace( $old_site_home, '', $url );
    		$old_url = $old_site_home . $old_url;
    		
    		$new_url = str_replace( $old_site_home, '', $url );
    		$new_url = $new_site_home . $new_url;
    		
    		$res = get_headers( $new_url, 1 );
    		$status_code = !is_array( $res[0] ) ? preg_replace( '/HTTP\/1\.\d+ (\d+) .*/', '\1', $res[0]) : "";
    		
    		$h_status = '';
    		$new_url = str_replace( $ngo2_auth_cred . '@', '', $new_url);
    		
    		if( ( $status_code == '302' || $status_code == '301' ) ) {
    			
    			$h_old = '';
    			$matches = array();
    			if( preg_match( '/<h1[^>]*?(?:page-title|entry-title)[^>]*?>(.*?)<\/h1>/is', file_get_contents( $old_url ), $matches ) ) {
//     				print_r( $matches );
    				$h_old = isset( $matches[1] ) ? $matches[1] : '';
    				$h_old = trim( strip_tags( $h_old ) );
    			}
    			
    			$h_new = '';
    			$matches = array();
    			$new_content = file_get_contents( $new_url, false, $context );
    			if( preg_match( '/<h1[^>]*?>(.*?)<\/h1>/is', $new_content, $matches ) ) {
//     				print_r( $matches );
    				$h_new = isset( $matches[1] ) ? $matches[1] : '';
    				$h_new = trim( strip_tags( $h_new ) );
    			}
    			
    			// echo 'h_old=' . $h_old . "\n";
    			// echo 'h_new=' . $h_new . "\n";
    			
    			if( $h_old && $h_new )  {
    				if( $h_old == $h_new ) {
    					$h_status = 'H_SAME';
    				}
    				else {
    					$h_status = 'H_DIFF';
    				}
    			}
    			else {
    				if( !$h_new && !$h_old ) {
    					$h_status = 'H_BOTH_EMPTY';
    				}
    				elseif( !$h_new ) {
    					$h_status = 'H_NEW_EMPTY';
    				}
    				else {
    					$h_status = 'H_OLD_EMPTY';
    				}
    			}
    		}
    		
    		$val_data = array(
    			$counter++,
    		    $old_url, #str_replace( $ngo2_auth_cred . '@', '', $new_url),
    			$status_code,
    			$h_status,
    			isset( $res['Location'] ) ? is_array( $res['Location'] ) ? $res['Location'][0] : $res['Location'] : '',
    		);
    		
    		fputcsv( $output, $val_data );
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }
    else {
    	die("open file error\n");
    }
    #echo "done\n";


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