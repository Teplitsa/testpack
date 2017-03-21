<?php

//var_dump($wp_query->posts);
global $wp_query;
$cpost = get_queried_object();

$query_vars = array(
    'post_type' => 'project',
);
$archive_query = new WP_Query( $query_vars );

$posts = $archive_query->posts;
$found = $archive_query->found_posts;

get_header();?>

<article class="landing dront-publication">
    <?php wds_page_builder_area( 'page_builder_default' );?>
    
	<?php
	    include( get_template_directory() . '/partial-posts-archive-items.php' );
	?>
    
    <section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>

<?php get_footer();