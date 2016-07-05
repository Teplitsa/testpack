<?php
/** Campaign **/

$cpost = get_queried_object(); 




get_header(); ?>
<section class="main-content single-post-section campaign">

<div class="container">
	<header class="entry-header-full">		
		<h1 class="entry-title section-like"><?php echo get_the_title($cpost);?></h1>				
			
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
	</header>
	
	<!-- for constuctor -->
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
</div>
</section>
<?php
get_footer();
