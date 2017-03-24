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
    
);

try {
	$time_start = microtime(true);
	include('cli_common.php');
    
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

    $options = getopt("", array('file:'));
    
    
    $params = tst_get_latest_attachments_query_params( 'publication', '', -1, 'ids' );
    unset( $params['date_query'] );
    $all_posts_ids = get_posts( $params );
    $all_posts_urls = array();
    foreach( $all_posts_ids as $post_id ) {
        $post_url = wp_get_attachment_url( $post_id );
        $all_posts_urls[ $post_url ] = $post_id;
    }
    
    $pages = array(
        home_url( '/archive/di-stati-i-publikatsii/' ),
    );
    
    $i = 0;
    $all_urls_with_tags = array();
    foreach( $pages as $page_url ) {
        $i += 1;
        printf( "processing link#%d\n", $i );
        
        $page_base_url = url2base( $page_url );

        $dom = new DomDocument;

        $ch = curl_init( $page_url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($response, 0, $header_size);

        $headers = get_headers_from_curl_response( $header_text );
        $content = substr($response, $header_size);
//        echo $content;

    //    print_r( $headers );

        curl_close($ch);

//         if( isset( $section['charset'] ) ) {
//             $content = mb_convert_encoding($content, 'UTF-8', $section['charset'] );
//         }
//         $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

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
        
        $nodes = $xpath->query( '//*[@id="site_content"]/article/div/div/div' );
        $node = $nodes->item(0);
        $li_order = 0;
        foreach( $node->childNodes as $child ) {
            if( $child->nodeName == 'h2' ) {
                $year = $child->nodeValue;
                printf( "year: %d\n", $year );
                $li_order = 0;
            }
            
            if( $child->nodeName == 'ul' ) {
                
                $ul_dom = new DomDocument;
                $ul_content = '<ul>'.TST_Import::get_instance()->get_inner_html( $child ).'</ul>';
                $ul_content = mb_convert_encoding($ul_content, 'HTML-ENTITIES', 'UTF-8');
                $ul_dom->loadHTML( $ul_content, LIBXML_NOWARNING | LIBXML_NOERROR );
                $ul_xpath = new DomXPath( $ul_dom );
                $nodes = $ul_xpath->query( './/li' );
                
                foreach( $nodes as $li ) {
                    
//                     printf( "  %s\n", $li->nodeValue );
                    
                    $li_dom = new DomDocument;
                    $li_content = '<li>'.TST_Import::get_instance()->get_inner_html( $li ).'</li>';
                    $li_content = mb_convert_encoding($li_content, 'HTML-ENTITIES', 'UTF-8');
                    $li_dom->loadHTML( $li_content, LIBXML_NOWARNING | LIBXML_NOERROR );
                    $li_xpath = new DomXPath( $li_dom );
                    
                    $a = $li_xpath->query("//a");
                    if( $a ) {
                        $a = $a->item(0);
                        if( $a ) {
//                             printf( "    %s\n", $a->nodeValue );
                            $attr_href = $a->attributes->getNamedItem( 'href' );
                            if( $attr_href ) {
                                $attr_href = $attr_href->nodeValue;
//                                 printf( "    %s\n", $attr_href );
                                $all_urls_with_tags[ $attr_href ] = array( 'li_text' => $li->nodeValue, 'a_text' => $a->nodeValue, 'year' => $year, 'order' => $li_order );
                                $li_order += 1;
                            }
                        }
                    }
                    
                }
            }
        }
        
    }
    
    $not_found_li = array();
    foreach( $all_posts_urls as $url => $post_id ) {
        if( !isset( $all_urls_with_tags[ $url ] ) ) {
            $not_found_li[] = $url;
            TST_Import::get_instance()->set_file_date( $post_id, $url );
            
            $post_date = get_post_meta( $post_id, 'file_date', true );
            wp_update_post( array(
                'ID' => $post_id,
                'post_date' => $post_date,
                'post_date_gmt' => get_gmt_from_date( $post_date ),
            ) );
            
        }
        else {
            $post = get_post( $post_id );
            update_post_meta( $post_id, 'original_attachment_title', $post->post_title );
            
            $link_data = $all_urls_with_tags[ $url ];
            
            $date = $link_data['year'] . '-12-31 00:00:00';
            $time = strtotime( $date );
            $time -= 24 * 3600 * $link_data['order'];
            
            $post_date = date( 'Y-m-d', $time );
            
            $post_content = trim( $link_data['li_text'] );
            $post_content = str_replace( array("\n","\r"), '', $post_content );
            $post_content = trim( $post_content );
            
            if( preg_match( '/\.pdf$/i', $url ) ) {
                $post_content = preg_replace( '/(\(.*?)doc(.*?\))/i', '\1pdf\2', $post_content );
            }
            
            wp_update_post( array(
                'ID' => $post_id,
                'post_content' => $post_content,
                'post_date' => $post_date,
                'post_date_gmt' => get_gmt_from_date( $post_date ),
            ) );
            
        }
    }
    
    printf( "all pubs: %d\n", count( $all_posts_urls ) );
    printf( "all li tags: %d\n", count( $all_urls_with_tags ) );
    printf( "not found li: %d\n", count( $not_found_li ) );
    
    print( "\n" );
    printf( "Data parsed: %d pages\n", $i );

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