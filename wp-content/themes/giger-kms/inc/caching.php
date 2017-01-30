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

            if( !get_transient('homepage_'.$block) || $existing_ids != $new_ids) {
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

}
add_action('admin_init', 'tst_invalidate_caches_actions');