<?php
/**
 * Events Template
 **/

global $wp_query;

$posts = $wp_query->posts;
$found = $wp_query->found_posts;
$title = __( 'Events', 'tst' );

include( get_template_directory() . '/partial-posts-archive.php' );
