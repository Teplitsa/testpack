<?php
/**
 * Projects Template
 **/

global $wp_query;

$loop_1 = $wp_query->posts;
$found = $wp_query->found_posts;
$title = __( 'Projects', 'tst' );

include( get_template_directory() . '/partial-posts-archive.php' );