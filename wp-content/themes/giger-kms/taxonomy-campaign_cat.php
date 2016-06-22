<?php
/**
 * Campaing cards archives
 **/

$qo = get_queried_object(); 
$posts = $wp_query->posts;

$back_url = get_term_meta($qo->term_id, 'term_back_url', true);
$back_text = get_term_meta($qo->term_id, 'term_back_text', true);

get_header();
?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
	<?php if(!empty($qo->description)) { ?>
		<div class="category-description"><?php echo apply_filters('rdc_the_content', $qo->description);?></div>
	<?php } ?>
	<?php if(!empty($back_url) && !empty($back_text)) { ?>
		<div class="category-backlink"><a href="<?php echo esc_url($back_url);?>"><?php echo apply_filters('rdc_the_title', $back_text);?>&nbsp;&rarr;</a></div>
	<?php } ?>
</section>

<section class="main-content cards-holder"><div class="container">
<div class="cards-loop sm-cols-2 md-cols-2 lg-cols-4">
	<?php
		if(!empty($posts)){
			foreach($posts as $p){				
				tst_leyka_campaign_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging">
<?php rdc_paging_nav($wp_query); ?>
</section>

<?php get_footer();