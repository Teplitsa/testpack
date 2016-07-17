<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();

get_header(); 
?>
<section class="page-header-simple"><div class="container-narrow">
	<h1 class="page-title"><?php echo get_the_title($cpost);?></h1>	
</div></section>

<section class="main-content page-content-simple">

<div class="container-narrow">	
	<div class="entry-content"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?></div>	
</div>
</section>


<?php get_footer(); ?>
