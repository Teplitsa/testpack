<?php
/**
 * People category
 */

$qo = get_queried_object(); 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

if($paged == 1) { //featured post
?>
<section class="featured-action"><?php echo apply_filters('rdc_the_content', $qo->description);?></section>
<?php } ?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container">
<div class="cards-loop sm-cols-2 md-cols-2 lg-cols-4">
	<?php
		if(have_posts()){
			foreach($posts as $p){
				rdc_person_card($p);
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
