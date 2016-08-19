<?php
/**
 * Template Name: Fullwidth
 **/

$qo = get_queried_object(); 

get_header();?>

<article class="main-content tpl-page-fullwidth">
	<div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php echo get_the_title($qo);?></h1>
        </header>
		<div class="entry-content"><?php echo apply_filters('the_content', $qo->post_content); ?></div>
	</div>
</article>

<?php get_footer();