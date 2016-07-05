<?php
/** Single program **/

$cpost = get_queried_object(); 




get_header(); ?>
<section class="main-content single-post-section programm">
<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>


<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo tst_breadcrumbs($cpost); ?></div>
		<h1 class="entry-title section-like"><?php echo get_the_title($cpost);?></h1>				
		<div class="mobile-sharing hide-on-medium"><?php echo tst_social_share_no_js();?></div>
		
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
	</header>
	
	<!-- for constuctor -->
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
</div>
</section>
<?php
	$rquery = tst_get_related_query($cpost, 'post_tag', 4); 	
	if($rquery->have_posts()){
?>
	<section class="related-section"><div class="container">
		<h3 class="widget-title">Новости программы</h3>
		<div class="cards-loop sm-cols-2 md-cols-3 lg-cols-4">
		<?php foreach($rquery->posts as $p) { tst_related_post_card($p); }?>
		</div>
	</div></section>
<?php
	}
	
get_footer();