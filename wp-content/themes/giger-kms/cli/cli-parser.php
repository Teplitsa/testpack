<?php

set_time_limit (0);
ini_set('memory_limit','256M');



define( 'DRONT_SITE_URL', 'http://dront.ru');
$sections = array(
    'http://dront.ru/news/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//h1", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", 
            'date' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//p[@class='date']"
        ), 
        'clean_content_regexp' => array(
            '#.*<p class="date">[.0-9]+</p>#s',
        ),
        "is_files_in_content" => true,
        'post_type' => 'post',
    ),
    
    'http://dront.ru/cheboksarskaya/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//h1", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
            '#<p(.*?)>\s*<a(.*?)>\s*<strong>← Вернуться назад</strong>\s*</a>\s*</p>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//h1", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/faunistika/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//span[@class='path_arrow'][last()]/following-sibling::text()[1]", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./span[@class='path_arrow']]", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            array( 'regexp' => '#.*?(?=<p(.*?)>)#is', 'limit' => 1 ),
            '#<p(.*?)>\s*<a(.*?)>\s*<strong>← Вернуться назад</strong>\s*</a>\s*</p>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/help/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//h1", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
            '#<p(.*?)>\s*<a(.*?)>\s*<strong>Читать другие советы</strong>\s*</a>\s*</p>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
);

$common_clean_regexp = array(
    '#<div class="yashare-auto-init"(.*?)>(.*?)</div>#is',
);

foreach( $sections as $k => $v ) {
    foreach( $common_clean_regexp as $regexp ) {
        $sections[$k]['clean_content_regexp'][] = $regexp;
    }
}

//$sections[''] = $sections['http://dront.ru/faunistika/'];

try {
	$time_start = microtime(true);
	include('cli_common.php');
    include( get_template_directory() . '/inc/class-import.php' );    
    
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

    $options = getopt("", array('file:'));
    
    $fname = isset($options['file']) ? $options['file'] : '';
    printf( "Processing %s\n", $fname );
    
    $file = fopen($fname, "r");

    if( !$file ) {
        die( "File not found: " . $fname . "\n");
    }

    while (!feof($file)) {
        $page = fgets($file);
        $page = trim( $page );
        if( $page ) {
            $pages[] = $page;
        }
    }
    fclose($file);

    $csv = preg_replace( '/(\.\w+)$/', '_content\1', $fname );
    $csv_handler = fopen( $csv, 'w+' );

    $i = 0;
    foreach( $pages as $page_url ) {
        $i += 1;
        printf( "processing link#%d\n", $i );

        $section = get_section( $page_url, $sections );

        if( !$section ) {
            printf( "SKIP NO_SECTION: %s\n", $page_url );
            continue;
        }

        $result = array(
            'post_type' => $section['post_type'],
            'page' => $page_url,
            'title' => '',
            'content' => '',
            'date' => '',
            'files' => array(),
        );

        $dom = new DomDocument;

        $ch = curl_init( $page_url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);

        $headers = get_headers_from_curl_response( $header_text );
        $content = substr($response, $header_size);

    //    print_r( $headers );

        curl_close($ch);

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

        foreach( $section['xpath'] as $k => $v ) {
            if( $v ) {
                $nodes = $xpath->query( $v );
                $node = $nodes ? $nodes->item(0) : NULL;
                $result[$k] = $node ? ( $node->childNodes ? get_inner_html($node) : $node->nodeValue ) : '';
                $result[$k] = trim( $result[$k] );
            }
        }

        if( $result['date'] ) {
            $result['date'] = clean_date( $result['date'], $section );
        }

        if( $result['content'] ) {
            $result['content'] = clean_content( $result['content'], $section );
            $result['content'] = TST_Import::get_instance()->remove_inline_styles( $result['content'] );
        }

        $result['title'] = strip_tags( $result['title'] );

        if( $section['is_files_in_content'] && $result['content'] ) {
            $result['files'] = get_media_files_links( $result['content'] );
        }

        fputcsv($csv_handler, $result);
    }
    fclose($csv_handler);
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
        if( strpos( $doc, DRONT_SITE_URL ) !== FALSE && preg_match( '/\.(png|gif|jpg|jpeg|pdf|rar|docx|zip|html|xls|pptx|ppt|wav|doc|wma|htm)$/i', $doc ) ) {
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
            $content = preg_replace( $regexp , "", $content, $limit );
        }
    }
    
    $content = preg_replace('/(src|href)\s*=\s*(["\'])\s*(\/[^\"\' ]+)/', '\1=\2' . DRONT_SITE_URL . '\3', $content);
    
    return $content;
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