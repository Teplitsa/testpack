<?php
/**
 * Campaign page
 * 
 **/

$cpost = get_queried_object(); 


get_header(); ?>
<section class="main-content single-post-section format-<?php echo $format;?>"><div class="container-narrow">
	
	<header class="entry-header-full">		
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>		
	</header>
	
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
</section>
<?php
get_footer();
