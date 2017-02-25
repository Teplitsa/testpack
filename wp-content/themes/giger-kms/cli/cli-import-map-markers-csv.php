<?php

set_time_limit (0);
ini_set('memory_limit','256M');

define('DRONT_SITE_URL', 'http://dront.ru');

try {
	$time_start = microtime(true);
	include('cli_common.php');
    include( get_template_directory() . '/inc/class-import.php' );    

	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

//    $options = getopt('', array('file-orig:', 'file-csv:',));
    
    $fname_orig = 'dront_map_points.json';
    $fname_csv = 'dront_markers_verified.csv';
    printf("Processing %s and %s\n", $fname_orig, $fname_csv);

    $file_orig = file_get_contents($fname_orig, "r");
    $file_csv = fopen($fname_csv, 'r');

    if( !$file_orig || !$file_csv ) {
        die("File(s) not found: $fname_orig or $fname_csv\n");
    }

    // Insert special marker groups and metadata:
    $special_marker_groups = array(
        array('name' => 'Архив', 'slug' => 'archive-markers', 'layer_marker_icon' => 'archive',),
        array('name' => 'Объекты', 'slug' => 'objects', 'layer_marker_icon' => 'location_on',),
        array('name' => 'Проблемы', 'slug' => 'problems', 'layer_marker_icon' => 'report_problem',),
        array('name' => 'Решенные проблемы', 'slug' => 'solved-problems', 'layer_marker_icon' => 'thumb_up',),
    );

    $objects_group_id = 0;
    $problems_group_id = 0;
    foreach($special_marker_groups as $group) {

        $group_in_db = get_term_by('name', $group['name'], 'marker_cat');
        if( !$group_in_db ) {

            $group_in_db = wp_insert_term($group['name'], 'marker_cat', array('slug' => $group['slug']));
            update_term_meta($group_in_db['term_id'], 'layer_marker_icon', $group['layer_marker_icon']);

            if($group['name'] == 'Объекты') {
                $objects_group_id = $group_in_db['term_id'];
            } elseif($group['name'] == 'Проблемы') {
                $problems_group_id = $group_in_db['term_id'];
            }

        } elseif( !get_term_meta($group_in_db->term_id, 'layer_marker_icon', true) ) {
            update_term_meta($group_in_db->term_id, 'layer_marker_icon', $group['layer_marker_icon']);
        }
        // Update marker parent groups' colors?

    }

    $data = json_decode($file_orig);

    $markers_num = 0;
    while(($line = fgetcsv($file_csv)) !== false ) {

        if($line[0] == 'Название') {
            continue;
        }

        foreach($data as $index => $group) {

            $term = get_terms(array(
                'taxonomy' => 'marker_cat',
                'hide_empty' => false,
                'name' => $group->name,
            ));

            if( !$term ) {

                $term = wp_insert_term($group->name, 'marker_cat');
                $term = $term['term_id'];

            } else {

                $term = reset($term);
                $term = $term->term_id;

            }

            if( !get_term_meta($term, 'layer_marker_icon', true) ) {
                switch($group->name) {
                    case 'Пункты приема вторсырья':
                        update_term_meta($term, 'layer_marker_icon', 'autorenew');
                        wp_update_term($term, 'marker_cat', array('parent' => $objects_group_id));
                        break;
                    case 'Экологические организации и ведомства':
                        update_term_meta($term, 'layer_marker_icon', 'business_center');
                        wp_update_term($term, 'marker_cat', array('parent' => $objects_group_id));
                        break;

                    case 'Вырубка зеленых насаждений':
                        update_term_meta($term, 'layer_marker_icon', 'nature');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Другие экологические проблемы':
                        update_term_meta($term, 'layer_marker_icon', 'help');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Загрязнение воды':
                        update_term_meta($term, 'layer_marker_icon', 'invert_colors');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Загрязнение воздуха':
                        update_term_meta($term, 'layer_marker_icon', 'cloud');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Зоны накопленного экологического ущерба':
                        update_term_meta($term, 'layer_marker_icon', 'view_quilt');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Проблемные проекты':
                        update_term_meta($term, 'layer_marker_icon', 'business');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    case 'Свалки':
                        update_term_meta($term, 'layer_marker_icon', 'delete_sweep');
                        wp_update_term($term, 'marker_cat', array('parent' => $problems_group_id));
                        break;
                    default:
                }
            }

            // Update marker groups' colors here..

            foreach($group->items as $marker_index => $marker) {

                if($marker->id == $line[6]) { // $line[6] - old ID

                    $already_inserted = get_posts(array(
                        'post_type' => 'marker',
                        'meta_query' => array(array('key' => '_old_id', 'value' => $line[6])),
                    ));
                    if($already_inserted) {

                        $marker_post_id = reset($already_inserted)->ID;
                        wp_set_object_terms($marker_post_id, array($term), 'marker_cat');
                        break 2;

                    }
                    
                    $marker_title = str_replace(
                            array("'",),
                            array('"',),
                            html_entity_decode($marker->name, ENT_COMPAT, 'UTF-8')
                        );
                    $market_post_name = sanitize_title( $marker_title );

                    $marker_post_id = wp_insert_post(array(
                        'post_type' => 'marker',
                        'post_content' => html_entity_decode($marker->longcontent, ENT_COMPAT, 'UTF-8'),
                        'post_excerpt' => strip_tags($marker->content),
                        'post_title' => $marker_title,
                        'post_name' => $market_post_name,
                        'post_status' => 'publish',
                        'meta_input' => array(
                            'marker_address' => $marker->name,
                            'marker_location' => array(
                                'latitude' => floatval($marker->center[0]),
                                'longitude' => floatval($marker->center[1])
                            ),
                            'marker_location_latitude' => floatval($marker->center[0]),
                            'marker_location_longitude' => floatval($marker->center[1]),
                            '_old_id' => $marker->id,
                        ),
                    ));

                    wp_set_object_terms($marker_post_id, array($term), 'marker_cat');

                    unset($group->items[$marker_index]);
                    $markers_num++;
                    break 2;

                }

            }

        }

    }


	//Final
	echo 'Markers inserted: '.$markers_num.chr(10);
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