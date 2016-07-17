<?php
/** Single program **/

$cpost = get_queried_object(); 




get_header(); ?>
<section class="main-content single-post-section programm">
<div id="tst_sharing" class="regular-sharing hide-upto-medium"><?php echo tst_social_share_no_js();?></div>


<div class="container">
	<header class="entry-header-full">
		<?php echo tst_breadcrumbs($cpost); ?>
		<h1 class="entry-title section-like"><?php echo get_the_title($cpost);?></h1>
		<div class="lead"><?php echo apply_filters('tst_the_content', $cpost->post_excerpt); ?></div>
		
		<div class="mobile-sharing hide-on-medium"><?php echo tst_social_share_no_js();?></div>		
	</header>
	
	<!-- for constuctor -->
	<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
</div>
</section>
<?php
	$rquery = tst_get_related_query($cpost, 'post_tag', 4); 	
	if($rquery->have_posts()){
		$tag = get_the_terms($cpost, 'post_tag');
		$all_link = ($tag) ? get_term_link($tag[0]) : '';
?>
	<section class="related-section"><div class="container">
		<h3 class="widget-title">Новости программы <?php if(!empty($all_link)) { ?><a href="<?php echo esc_url($all_link);?>">Все&nbsp;&rarr;</a><?php } ?></h3>
		<div class="cards-loop sm-cols-2 md-cols-3 lg-cols-4">
		<?php foreach($rquery->posts as $p) { tst_related_post_card($p); }?>
		</div>
	</div></section>
<?php
	}
	
get_footer();