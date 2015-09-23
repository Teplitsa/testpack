<?php
/**
 * Template Name: Cards
 */

get_header();?>


<div class="mdl-grid cards-gallery">
<?php
	$cards_categories = get_field('object_categories', get_the_ID());	
	if(!empty($cards_categories)){ foreach($cards_categories as $category){
		
		if(empty($category['objects']))
			continue;
		
		if(!empty($category['category_name'])){
	?>
		<header class="mdl-cell mdl-cell--12-col">
			<h3><?php echo apply_filters('tst_the_title', $category['category_name']);?></h3>
			<?php if(!empty($category['category_text'])){ ?>
				<div class="entry-summary"><?php echo apply_filters('tst_the_content', $category['category_text']);?></div>
			<?php } ?>
		</header>
	<?php
		}		
		foreach($category['objects'] as $obj) {
			tst_general_card($obj);
		}
	}} //foreach
?>
</div>




<?php get_footer(); ?>
