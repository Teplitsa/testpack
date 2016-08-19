<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();

get_header(); 
?>
<article class="main-content tpl-page-regular"><div class="container-narrow">
	<header class="page-header">
		<h1 class="page-title"><?php echo get_the_title($cpost);?></h1>
	</header>
	
	<div class="entry-content"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?></div>	
	
</div></article>


<?php get_footer(); ?>
