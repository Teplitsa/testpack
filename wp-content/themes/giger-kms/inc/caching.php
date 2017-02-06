<?php
/**
 * Page fragments and data caching.
 **/

if( !function_exists('tst_get_homepage_block') ) {
    function tst_get_homepage_block($homepage_id, $block_name) {

        $result = get_transient('homepage_'.$block_name);
        if( !$result ) {

            $ids = get_post_meta($homepage_id, $block_name, true);
            $result = $ids ? get_posts(array(
                'post_type' => 'item',
                'posts_per_page' => 5, //limit?
                'post__in' => array_map('intval', explode(',', $ids)),
                'orderby' => 'post__in'
            )) : array();

            if($result) {
                set_transient('homepage_'.$block_name, $result);
            }

        }

        return $result;

    }
}

if( !function_exists('tst_get_default_map_markers') ) {
    function tst_get_default_map_markers() {

        $result = get_transient('default_map_markers');
        if( !$result ) {

            $result = get_posts(array(
                'post_type' => 'marker',
                'posts_per_page' => -1,
                'orderby'   => 'meta_value',
                'meta_key'  => 'marker_city',
                'order'   => 'ASC',
            ));
            set_transient('default_map_markers', $result);

        }

        return $result;

    }
}

if( !function_exists('tst_get_map_marker_data') ) {
    function tst_get_map_marker_data($marker_id) {

        $marker_id = intval($marker_id);
        $result = get_transient('map_marker_data_'.$marker_id);
        if( !$result ) {

            $result = array(
                'lat' => get_post_meta($marker_id, 'marker_location_latitude', true),
                'lng' => get_post_meta($marker_id, 'marker_location_longitude', true),
                'city' => get_post_meta($marker_id, 'marker_city', true),
                'address' => get_post_meta($marker_id, 'marker_address', true),
                'phones' => get_post_meta($marker_id, 'marker_phones', true),
                'groups' => get_the_terms($marker_id, 'marker_cat'),
            );
            set_transient('map_marker_data_'.$marker_id, $result);

        }

        return $result;

    }
}


function tst_invalidate_caches_actions(){

    function tst_invalidate_homepage_blocks($post_id){

        if(wp_is_post_revision($post_id) || $post_id != get_option('page_on_front')) {
            return;
        }

        // Main page blocks:
        $blocks = array('block_one', 'block_two', 'infosup_one', 'infosup_two', 'infosup_three', 'infosup_four',);

        foreach($blocks as $block) { // Check the vaidity of block cache and refresh it if needed

            $existing_ids = array_map('intval', explode(',', get_post_meta($block)));
            $new_ids = array_map('intval', explode(',', $block));

            if( !get_transient('homepage_'.$block) || count(array_intersect($existing_ids, $new_ids)) != count($new_ids) ) {
                set_transient('homepage_'.$block, get_posts(array(
                    'post_type' => 'item',
                    'posts_per_page' => 5, // limit?
                    'post__in' => array_map('intval', $new_ids),
                    'orderby' => 'post__in'
                )));
            }

        }

    }
    add_action('save_post', 'tst_invalidate_homepage_blocks');

    function tst_invalidate_map_markers($marker_id){

        if(wp_is_post_revision($marker_id) || get_post($marker_id)->post_type != 'marker') {
            return;
        }

        if( !get_transient('default_map_markers') ) {
            set_transient('default_map_markers', get_posts(array(
                'post_type' => 'marker',
                'posts_per_page' => -1,
                'orderby'   => 'meta_value',
                'meta_key'  => 'marker_city',
                'order'   => 'ASC',
            )));
        }

        $marker_data = get_post_meta($marker_id, '', true);
        if($marker_data) { // Marker is added/updated
            set_transient('map_marker_data_'.$marker_id, array(
                'lat' => get_post_meta($marker_id, 'marker_location_latitude', true),
                'lng' => get_post_meta($marker_id, 'marker_location_longitude', true),
                'city' => get_post_meta($marker_id, 'marker_city', true),
                'address' => get_post_meta($marker_id, 'marker_address', true),
                'phones' => get_post_meta($marker_id, 'marker_phones', true),
                'groups' => get_the_terms($marker_id, 'marker_cat'),
            ));
        } else {
            delete_transient('map_marker_data_'.$marker_id);
        }

    }
    add_action('save_post', 'tst_invalidate_map_markers');
    add_action('delete_post', 'tst_invalidate_map_markers');

}
add_action('admin_init', 'tst_invalidate_caches_actions');