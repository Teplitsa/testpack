<?php

set_time_limit (0);
ini_set('memory_limit','256M');

define( 'DRONT_SITE_URL', 'http://dront.ru');

try {
	$time_start = microtime(true);
	include('cli_common.php');
    include( get_template_directory() . '/inc/class-import.php' );    

	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

    $options = getopt('', array('file:',));

    $fname_csv = isset($options['file']) ? $options['file'] : '';
    printf("Processing %s\n", $fname_csv);

    $file_csv = fopen($fname_csv, 'r');

    if( !$file_csv ) {
        die("File(s) not found: $fname_csv\n");
    }

    $events_num = 0;
    $uploads = wp_upload_dir();

    while(($line = fgetcsv($file_csv)) !== false ) {

        if($line[0] == 'Название') {
            continue;
        }

        $thumb_id = 0;
        if( !empty($line[11]) ) {

            $path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$line[11];
            $test_path = $uploads['path'].'/'.$line[11];

            if( !file_exists($test_path) ) {

                $thumb_id = tst_upload_img_from_path($path);
                echo 'Uploaded thumbnail '.$thumb_id.chr(10);

            } else {
                $thumb_id = tst_register_uploaded_file($test_path);
            }

        }

        $datetime_start = $line[9] ? explode(' ', $line[9]) : '';
        $datetime_end = $line[10] ? explode(' ', $line[10]) : '';

        $event_id = wp_insert_post(array(
            'post_type' => 'event',
            'post_content' => html_entity_decode($line[3], ENT_COMPAT, 'UTF-8'),
            'post_excerpt' => strip_tags($line[2]),
            'post_title' => str_replace(
                array("'",),
                array('"',),
                html_entity_decode($line[0], ENT_COMPAT, 'UTF-8')
            ),
            'post_status' => 'publish',
            'meta_input' => array(
                '_thumbnail_id' => $thumb_id ? $thumb_id : '',
                'event_name' => $line[1], // Short name
                'event_address' => $line[4],
                'event_location' => $line[5],
                'event_contact' => $line[8],
                'event_date_start' => $datetime_start ? strtotime($datetime_start[0]) : '',
                'event_time_start' => $datetime_start ? $datetime_start[1] : '',
                'event_date_end' => $datetime_end ? strtotime($datetime_end[0]) : '',
                'event_time_end' => $datetime_end ? $datetime_end[1] : '',
                'event_marker' => array(
                    'latitude' => floatval($line[6]),
                    'longitude' => floatval($line[7])
                ),
                'event_marker_latitude' => floatval($line[6]),
                'event_marker_longitude' => floatval($line[7]),
            ),
        ));

        if( $event_id && !is_wp_error($event_id) ) {
            $events_num++;
        }

    }


	//Final
	echo 'Events inserted: '.$events_num.chr(10);
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
    
function get_section( $page_url, $sections ) {
    $proper_section = '';
    $proper_section_url = NULL;
    
    foreach( $sections as $section_url => $options ) {
        if( strpos( $page_url, $section_url ) === 0 ) {
            if( strlen( $section_url ) > strlen( $proper_section_url )) {
                $proper_section_url = $section_url;
            }
        }
    }
    
    if( $proper_section_url ) {
        printf( "section: %s\n", $proper_section_url );
        $proper_section = $sections[ $proper_section_url ];
        $proper_section['url'] = $proper_section_url;
    }
    else {
        print( "section not found!!!\n" );
    }
    
    return $proper_section;
}

function get_media_files_links( $content ) {
    $regex = '/https?\:\/\/[^\"\' ]+/i';
    preg_match_all($regex, $content, $matches);
    $docs = $matches[0];
    $inner_docs = array();
    foreach( $docs as $doc ) {
        if( strpos( $doc, DRONT_SITE_URL ) !== FALSE && preg_match( '/\.(png|gif|jpg|jpeg|pdf|rar|docx|zip|xls|pptx|ppt|wav|doc|wma)$/i', $doc ) ) {
            $inner_docs[] = $doc;
        }
    }
    return implode( '|', $inner_docs);
}

function clean_date( $date, $section ) {
    return date( 'Y-m-d', strtotime( $date ) );
}

function clean_content( $content, $section ) {
    $content = remove_script( $content );
    if( is_array( $section["clean_content_regexp"] ) ) {
        foreach( $section["clean_content_regexp"] as $regexp ) {
            if( is_array( $regexp ) ) {
                $limit = $regexp['limit'];
                $regexp = $regexp['regexp'];
            }
            else {
                $limit = -1;
            }
//            echo $regexp . "\n";
//            echo strlen( $content ) . "\n";
            $content = preg_replace( $regexp , "", $content, $limit );
//            echo strlen( $content ) . "\n";
        }
    }
    
    return $content;
}

function urls_rel2abs( $content, $base_url ) {
    $content = preg_replace('/(src|href)\s*=\s*(["\'])\s*(\/(?!\/)[^\"\' ]+)/', '\1=\2' . DRONT_SITE_URL . '\3', $content);
    $content = preg_replace('/(src|href)\s*=\s*(["\'])\s*((?!https?:\/\/)[^\"\' ]+)/', '\1=\2' . $base_url . '/\3', $content);
    return $content;
}

function url2base( $url ) {
    $base_url = preg_replace( '/\/[^\/]*$/', '', $url );
    return $base_url;
}

function remove_script( $html ) {
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    return $html;
}

function get_inner_html(DOMNode $element) { 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    if( $children ) {
        foreach ($children as $child) { 
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }
    }

    return $innerHTML; 
} 

function get_headers_from_curl_response( $header_text ) {
    $headers = array();

    foreach (explode("\r\n", $header_text) as $i => $line) {
        if ($i === 0) {
            $headers['STATUS'] = $line;
            preg_match( "/HTT\w+\/\d+.\d+\s+(\d+)/", $line, $matches );
            $headers['STATUS_CODE'] = trim( $matches[1] );
        }
        else {
            if( $line && strpos( $line, ':' ) ) {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
    }

    return $headers;
}