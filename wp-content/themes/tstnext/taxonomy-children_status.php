<?php
/**
 * Children status
 */

//body class
function tst_children_status_body_classes( $classes ) {
	
	if(is_tax('children_status')){
		$qo = get_queried_object();
		if(!empty($qo->description))
			$classes[] = 'cards-page-preface';
	}
	
	return $classes;
}
add_filter( 'body_class', 'tst_children_status_body_classes' );  


get_header(); ?>
<div class="mdl-grid masonry-grid">	
<?php
	$qo = get_queried_object(); 
	if(in_array($qo->slug, array('remember', 'health'))) {
?>
	<div class="mdl-cell mdl-cell--12-col tax-preface-top"><div class="entry-content">
		<?php echo apply_filters('tst_the_content', $qo->description); ?>
	</div></div>
<?php
	}

	if(have_posts()){
		while(have_posts()){
			the_post();
			tst_children_card($post);			
		}  		
	}
	
	if(!in_array($qo->slug, array('remember', 'health'))) {
?>
		<div class="mdl-cell mdl-cell--4-col masonry-item movable-widget"><?php get_sidebar(); ?></div>
	<?php } ?>
</div>

<?php
	$p = tst_paging_nav();
	if(!empty($p)) {
?>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--12-col"><?php echo $p; ?></div>
	</div>
<?php } ?>

<?php get_footer(); ?>