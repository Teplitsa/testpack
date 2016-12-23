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
            'content' => array( ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h2]"), 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
            array( 'regexp' => '#<p(.*?)>\s*<a(.*?)>\s*<strong.*?>.*?Вернуться назад.*?</strong>\s*</a>\s*</p>#i', 'limit' => 1 ),
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
        'is_tree' => true,
    ),
    'http://dront.ru/faunistika/' => array( "xpath" => array( 
            'title' => ".//body//div[@class='container']//div[contains(@class, 'left_row')]//span[@class='path_arrow'][last()]/following-sibling::text()[1]", 
            'content' => ".//body//div[@class='container']//div[contains(@class, 'left_row')][./span[@class='path_arrow']]", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            array( 'regexp' => '#.*?(?=<p(.*?)>)#is', 'limit' => 1 ),
            array( 'regexp' => '#<p(.*?)>\s*<a(.*?)>\s*<strong.*?>.*?Вернуться назад.*?</strong>\s*</a>\s*</p>#i', 'limit' => 1 ),
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
            '#<p.*?>\s*<a.*?>\s*<strong>Читать другие советы</strong>\s*</a>\s*</p>#i',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/' => array( "xpath" => array( 
            'title' => array( 
                ".//body//div[@class='container']//div[contains(@class, 'left_row')]//h1", 
                ".//body//div[@class='container']//div[contains(@class, 'left_row')]//span[@class='path_arrow'][last()]/following-sibling::text()[1]", 
                ".//body/div[5]/div/div[4]/h2",
                ".//body//div[@class='container']//div//h2", 
            ),
            'content' => array( 
                ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h1]", 
                ".//body//div[@class='container']//div[contains(@class, 'left_row')][./h2]",
                ".//body//div[@class='container']//div[contains(@class, 'left_row')][.//span[@class='path_arrow']]",
                ".//body//div[@class='container']//div[@class='left'][./h2][1]",
            ),
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/pravo/' => array( "xpath" => array( 
            'title' => ".//*[@id='content']//p[1]/u", 
            'content' => ".//*[@id='content']", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            array( 'regexp' => '#<p*?>.*?</p>#is', 'limit' => 1 ),
        ),
        "is_files_in_content" => true,
        'is_tree' => true,
        'post_type' => 'import',
        'charset' => 'windows-1251',
    ),
    'http://dront.ru/ur-clinic/' => array( "xpath" => array( 
            'title' => ".//div[@class='zaglavok_big']", 
            'content' => ".//body//td[.//div[@class='zaglavok_big']]",
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            array( 'regexp' => '#<div class="zaglavok_big">.*?</div>#is', 'limit' => 1 ),
        ),
        "is_files_in_content" => true,
        'is_tree' => true,
        'post_type' => 'import',
    ),
    'http://dront.ru/old/' => array( "xpath" => array( 
            'title' => array( "(.//body/b[.//p])[1]", "(.//body/p[.//b])[1]", "(.//body/blockquote/p)[1]", "(.//body/p)[1]", ".//title", ), 
            'content' => ".//body", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
        ),
        'clean_content_xpath' => array(
            "(.//body/table[.//img[contains(@src, 'dront1.gif')]])[1]",
            "(.//body/table[.//a[contains(@href, 'english.ru.html')]])[1]",
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
        'charset' => 'KOI8-R',
    ),
    'http://dront.ru/old/news/' => array( "xpath" => array( 
            'title' => array( "(.//body//*[@class='red'])[1]", "(.//body/blockquote/p[.//font])[1]", "(.//body/blockquote/p[.//b])[1]", ),
            'content' => ".//body", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*<p class="date">[.0-9]+</p>#s',
        ),
        'clean_content_xpath' => array(
            "(.//body/table[.//img[contains(@src, 'dront1.gif')]])[1]",
            "(.//body/table[.//a[contains(@href, 'english.ru.html')]])[1]",
            "(.//body//*[@class='green'])[1]", 
        ),
        "is_files_in_content" => true,
        'is_date_from_url' => true,
        'date_from_url_rules' => array(
            array(
                'regexp' => '/(\d+-\d+-\d+).\w+$/i',
                'pattern' => '%y-%m-%d',
            ),
        ),
        'post_type' => 'post',
        'charset' => 'KOI8-R',
    ),
    
    'http://dront.ru/old/aee/' => array( "xpath" => array( 
            'title' => array( "(.//body//p[.//b])[1]", "(.//body//p[.//font])[1]", "(.//body//font[.//b])[1]", ),
            'content' => ".//body", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
        ),
        'clean_content_xpath' => array(
            '(.//body/table)[1]',
            '(.//body/table)[2]',
            '(.//body/table)[3]',
        ),
        "is_files_in_content" => true,
        'is_date_from_url' => true,
        'date_from_url_rules' => array(
            array(
                'regexp' => '/(\d+-\d+-\d+).\w+$/i',
                'pattern' => '%y-%m-%d',
            ),
        ),
        'post_type' => 'import',
    ),
    'http://dront.ru/old/strix/' => array( "xpath" => array( 
            'title' => array( ".//body//h4//text()", ".//body//b[1]//text()" ), 
            'content' => ".//body", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h4.*?>.*?</h4>#is',
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
        'is_tree' => true,
        'charset' => 'KOI8-R',
    ),
    'http://dront.ru/old/lr/' => array( "xpath" => array( 
            'title' => array( 
                ".//body//p[.//font[@size='4']]", 
                ".//body//h1", 
                ".//body//b[.//font[@size='4']//p]", 
                ".//body//font[@size='3']/b/i", 
                ".//body//font[@size='4'][1]" 
            ), 
            'content' => ".//body", 
            'date' => ""
        ), 
        'clean_content_regexp' => array(
            '#.*?<h1.*?>.*?</h1>#is',
            '#.*?<h4.*?>.*?</h4>#is',
            /*
            '#<table.*?>.*?Предыдущий.*?раздел.*?</table>#is',
            '#<table.*?>.*?<h1>.*?</table>#is',
             * 
             */
        ),
        "is_files_in_content" => true,
        'post_type' => 'import',
        'is_tree' => true,
        'charset' => 'KOI8-R',
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

$sections['http://dront.ru/cpt/'] = $sections['http://dront.ru/'];
$sections['http://dront.ru/cpt/']['is_tree'] = true;

$sections['http://dront.ru/help/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/nooar/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/orni-lab/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/obereg/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/public-office/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/real-world/'] = $sections['http://dront.ru/cpt/'];
$sections['http://dront.ru/sopr/'] = $sections['http://dront.ru/cpt/'];

$sections['http://dront.ru/old/aist2004/'] = $sections['http://dront.ru/old/'];
$sections['http://dront.ru/old/aist2004/']['is_tree'] = TRUE;
        
$sections['http://dront.ru/old/cinema/'] = $sections['http://dront.ru/old/aist2004/'];
unset( $sections['http://dront.ru/old/cinema/']['charset'] );

$sections['http://dront.ru/old/obereg/'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/sopr/'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/strix/'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/vezdehod/'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/works/'] = $sections['http://dront.ru/old/aist2004/'];

$sections['http://dront.ru/old/clnct/'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/clnct/']['charset'] = 'windows-1251';
$sections['http://dront.ru/old/clnct/']['is_tree'] = FALSE;

$sections['http://dront.ru/old/cent-ens.ru.htm'] = $sections['http://dront.ru/old/aist2004/'];
$sections['http://dront.ru/old/cent-ens.ru.htm']['is_tree'] = FALSE;
$sections['http://dront.ru/old/cent-ens.ru.htm']['charset'] = 'windows-1251';

$sections['http://dront.ru/old/index.html'] = $sections['http://dront.ru/old/'];
unset( $sections['http://dront.ru/old/index.html']['clean_content_regexp'] );
#unset( $sections['http://dront.ru/old/index.html']['clean_content_xpath'] );

//$sections[''] = $sections['http://dront.ru/'];


try {
	$time_start = microtime(true);
	include('cli_common.php');
    
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
        
        $page_base_url = url2base( $page_url );

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
            'files' => '',
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
//        echo $content;

    //    print_r( $headers );

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
        
        foreach( $section['xpath'] as $k => $v ) {
//            printf( "%s\n", $v );
            if( $v ) {
                if( is_array( $v ) ) {
                    foreach( $v as $v1 ) {
                        $nodes = $xpath->query( $v1 );
                        if( $nodes && $nodes->item(0) ) {
                            break;
                        }
                    }
                }
                else {
                    $nodes = $xpath->query( $v );
                }
                
                $node = $nodes ? $nodes->item(0) : NULL;
//                printf( "%s\n", $k );
//                printf( "%s\n", $v );
//                print_r( $node->childNodes );
                $result[$k] = $node ? ( $node->childNodes ? TST_Import::get_instance()->get_inner_html($node) : $node->nodeValue ) : '';
                $result[$k] = trim( $result[$k] );
            }
        }

        if( $result['date'] ) {
            $result['date'] = clean_date( $result['date'], $section );
        }
        
        if( isset( $section['is_date_from_url'] ) && $section['is_date_from_url'] ) {
            $file_date = TST_Import::get_instance()->get_exact_date_from_url( $page_url, $section['date_from_url_rules'] );
            if( $file_date ) {
                $result['date'] = $file_date;
            }
        }

        /*
        error_reporting( E_ALL );
        //echo $result['content'] . "\n*******************************************\n";
        $res = preg_match( '#(<p.*?>\s*?<span.*?>\s*?<a.*?>.*?Решение.*?</a>\s*?</span>\s*?</p>)#i', $result['content'], $matches );
        print_r( $matches );
        $result['content'] = preg_replace( '#<p(.*?)>\s*<a(.*?)>\s*<strong>.*?</strong>\s*</a>\s*</p>#i', '', $result['content'], 1 );
        echo $result['content'] . "\n*******************************************\n";
        exit();*/

        if( $result['content'] ) {
            $result['content'] = clean_content( $result['content'], $section );
            $result['content'] = urls_rel2abs( $result['content'], $page_base_url );
            $result['content'] = TST_Import::get_instance()->remove_inline_styles( $result['content'] );
        }
//        echo $result['content'];

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

        fputcsv($csv_handler, $result);
    }
    fclose($csv_handler);
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
    
    $content = TST_Import::get_instance()->clean_content_regexp( $content, $section );
    $content = TST_Import::get_instance()->clean_content_xpath( $content, $section );
    
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