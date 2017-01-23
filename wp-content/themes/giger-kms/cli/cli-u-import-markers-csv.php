<?php

set_time_limit (0);
ini_set('memory_limit','256M');

try {

    $time_start = microtime(true);
    include('cli_common.php');

    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $options = getopt('', array('file:',));

    $file_csv = fopen('data/markers.csv', 'r');

    if( !$file_csv ) {
        die("File(s) not found: data/markers.csv\n");
    }

    // Insert special marker groups and metadata:
    $special_marker_groups = array(
        array('name' => 'Платные', 'slug' => 'commercial-labs', 'layer_marker_icon' => 'add_circle', 'layer_marker_color' => 'yellow',),
        array('name' => 'Бесплатные', 'slug' => 'free-labs', 'layer_marker_icon' => 'add_circle_outline', 'layer_marker_color' => 'green',),
    );

    $free_group_id = 0;
    $commercial_group_id = 0;
    foreach($special_marker_groups as $group) {

        $group_in_db = get_term_by('name', $group['name'], 'marker_cat');
        if( !$group_in_db ) {

            $group_in_db = wp_insert_term($group['name'], 'marker_cat', array('slug' => $group['slug']));
            update_term_meta($group_in_db['term_id'], 'layer_marker_icon', $group['layer_marker_icon']);
            update_term_meta($group_in_db['term_id'], 'layer_marker_color', $group['layer_marker_color']);

            if($group['name'] == 'Платные') {
                $commercial_group_id = $group_in_db['term_id'];
            } elseif($group['name'] == 'Бесплатные') {
                $free_group_id = $group_in_db['term_id'];
            }

        } else {

            if($group['name'] == 'Платные') {
                $commercial_group_id = $group_in_db->term_id;
            } elseif($group['name'] == 'Бесплатные') {
                $free_group_id = $group_in_db->term_id;
            }

            if( !get_term_meta($group_in_db->term_id, 'layer_marker_icon', true) ) {
                update_term_meta($group_in_db->term_id, 'layer_marker_icon', $group['layer_marker_icon']);
            } elseif( !get_term_meta($group_in_db->term_id, 'layer_marker_color', true) ) {
                update_term_meta($group_in_db->term_id, 'layer_marker_color', $group['layer_marker_color']);
            }
        }

    }

    $markers_num = 0;
    while(($line = fgetcsv($file_csv)) !== false ) {

        if($line[0] == 'Название' || empty($line[0])) { // Skip the first line
            continue;
        }

        $is_free = stripos($line[0], 'ГБУЗ') !== false;

//        $already_inserted = get_posts(array(
//            'post_type' => 'marker',
//            'title' => $line[0],
//        ));
//        if($already_inserted) {
//
//            $marker_post_id = reset($already_inserted)->ID;
//            wp_set_object_terms($marker_post_id, $is_free ? $free_group_id : $commercial_group_id, 'marker_cat');
//            continue;
//
//        }

        $address_full = $line[1].', '.$line[2].', '.$line[3].($line[4] ? ' '.$line[4] : '');
        $marker_post_id = wp_insert_post(array(
            'post_type' => 'marker',
            'post_title' => str_replace(
                array("'",),
                array('"',),
                html_entity_decode($line[0], ENT_COMPAT, 'UTF-8')
            ),
            'post_status' => 'publish',
            'meta_input' => array(
                'marker_phones' => str_replace(',', "\n", $line[5]),
                'marker_address' => $address_full,
                'marker_location' => array(
                    'latitude' => floatval($line[6]),
                    'longitude' => floatval($line[7])
                ),
                'marker_location_latitude' => floatval($line[6]),
                'marker_location_longitude' => floatval($line[7]),
            ),
        ));

        wp_set_object_terms($marker_post_id, $is_free ? $free_group_id : $commercial_group_id, 'marker_cat');

        $markers_num++;

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