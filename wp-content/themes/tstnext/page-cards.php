<?php
/**
 * Template Name: Cards
 */

$cards_categories = get_field('object_categories', get_the_ID());

//body class
function tst_cards_body_classes( $classes ) {
	global $cards_categories;
		
	$test = false;
	
	if(isset($cards_categories[0]) && (!empty($cards_categories[0]['category_name']) || !empty($cards_categories[0]['category_text']))){
		$test = true;
	}
	
	if($test)
		$classes[] = 'cards-page-preface';
		
	return $classes;
}
add_filter( 'body_class', 'tst_cards_body_classes' ); 

get_header();
?>
<div class="mdl-grid cards-gallery">
<?php
	$cards_categories = get_field('object_categories', get_the_ID());	
	if(!empty($cards_categories)){ foreach($cards_categories as $category){
		
		if(empty($category['objects']))
			continue;
		
		if(!empty($category['category_name']) || !empty($category['category_text'])){
	?>
		<header class="mdl-cell mdl-cell--12-col"><div class="entry-content">
			<?php if(!empty($category['category_name'])){ ?>
				<h3><?php echo apply_filters('tst_the_title', $category['category_name']);?></h3>
			<?php } ?>
			<?php if(!empty($category['category_text'])){ ?>
				<div class="entry-summary"><?php echo apply_filters('tst_the_content', $category['category_text']);?></div>
			<?php } ?>
		</div></header>
	<?php
		}		
		foreach($category['objects'] as $obj) {
			tst_general_card($obj);
		}
	}} //foreach
?>
</div>




<?php get_footer(); ?>
