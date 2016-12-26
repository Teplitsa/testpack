<?php

set_time_limit (0);
ini_set('memory_limit','256M');

define( 'DRONT_SITE_URL', 'http://dront.ru');
$sections = array(
    'http://www.seu.ru/members/bereginya/' => array( 
        'clean_content_regexp' => array(
        ),
        'clean_content_xpath' => array(
            "(.//body/table[.//img[contains(@src, 'dront1.gif')]])[1]",
            "(.//body/table[.//a[contains(@href, 'english.ru.html')]])[1]",
        ),
        'charset' => 'windows-1251',
        'is_date_from_url' => true,
    ),
);

try {
	$time_start = microtime(true);
	include('cli_common.php');
    
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;
    
    $pages = TST_ImportOldBereginya::get_instance()->get_pages( $sections );

    $i = 0;
    foreach( $pages as $page_url ) {
        $i += 1;
        printf( "processing link#%d: %s\n", $i, $url );
        
        $page_base_url = TST_Import::get_instance()->url2base( $page_url );

        $section = $sections['http://www.seu.ru/members/bereginya/'];

        if( !$section ) {
            printf( "SKIP NO_SECTION: %s\n", $page_url );
            continue;
        }

        $dom = new DomDocument;

        $ch = curl_init( $page_url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);

        $headers = TST_Import::get_instance()->get_headers_from_curl_response( $header_text );
        $content = substr($response, $header_size);

        curl_close($ch);

        if( isset( $section['charset'] ) ) {
            $content = mb_convert_encoding($content, 'UTF-8', $section['charset'] );
        }
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

        if( $headers['STATUS_CODE'] != '200' ) {
            printf( "SKIP STATUS: %s %s\n", $headers['STATUS_CODE'], $page_url );
            continue;
        }

        if( !preg_match( '#text/html#', $headers['Content-Type'] ) ) {
            printf( "SKIP CONTENT: %s %s\n", $headers['Content-Type'], $page_url );
            continue;
        }

        libxml_use_internal_errors(true);
        $dom->loadHTML( $content, LIBXML_NOWARNING | LIBXML_NOERROR );

        $xpath = new DomXPath($dom);
        
        if( isset( $section['is_date_from_url'] ) && $section['is_date_from_url'] ) {
            $file_date = TST_Import::get_instance()->get_exact_date_from_url( $page_url, $section['date_from_url_rules'] );
            if( $file_date ) {
                $result['date'] = $file_date;
            }
        }

        if( $result['content'] ) {
            $result['content'] = TST_Import::get_instance()->clean_content( $result['content'], $section );
            $result['content'] = TST_Import::get_instance()->urls_rel2abs( $result['content'], $page_base_url, DRONT_SITE_URL );
            $result['content'] = TST_Import::get_instance()->remove_inline_styles( $result['content'] );
        }

        $result['title'] = TST_Import::get_instance()->clean_string( $result['title'] );

        if( $section['is_files_in_content'] && $result['content'] ) {
            $result['files'] = get_media_files_links( $result['content'] );
        }
        
        if( isset( $section['is_tree'] ) && $section['is_tree'] ) {
            $result['parent_url'] = $section['url'];
        }
        else {
            $result['parent_url'] = '';
        }
        
    }
    print( "\n" );
    printf( "Data parsed: %d pages\n", $i );
    printf( "Result: %s\n", $csv );

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
