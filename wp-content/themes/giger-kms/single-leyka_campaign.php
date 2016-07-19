<?php
/** Campaign **/

$cpost = get_queried_object(); 




get_header(); ?>
<section class="main-content single-post-section campaign">

<div class="container">
		
	<!-- for constuctor -->
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
</div>
</section>
<?php
get_footer();
