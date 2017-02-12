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

    $options = getopt("", array('file:'));
    
    $fname = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $fname );
    
    $file = file_get_contents($fname, "r");

    if( !$file ) {
        die( "File not found: " . $fname . "\n");
    }

    $data = json_decode($file);
    $csv = fopen('dront_markers_all.csv', 'w');
    fputcsv($csv, array("Название", "Кр. описание", "Описание", "Адрес", "Шир.", "Долг.", "ID на старом сайте"));

    foreach($data as $group) {

        $term = get_terms(array(
            'taxonomy' => 'marker_cat',
            'hide_empty' => false,
            'name' => $group->name,
        ));
        if( !$term ) {
            $term = wp_insert_term($group->name, 'marker_cat');
        } else {
            $term = reset($term);
        }

        foreach($group->items as $marker) {

            $marker_name = str_replace(
                array("'",),
                array('"',),
                html_entity_decode($marker->name, ENT_COMPAT, 'UTF-8')
            );
            fputcsv($csv, array(
                $marker_name,
                strip_tags(html_entity_decode($marker->content, ENT_COMPAT, 'UTF-8')),
                strip_tags(html_entity_decode($marker->longcontent, ENT_COMPAT, 'UTF-8')),
                $marker_name,
                floatval($marker->center[0]),
                floatval($marker->center[1]),
                $marker->id
            ));

        }

    }

    fclose($csv);

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