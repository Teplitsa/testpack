<?php
/**
 * People category
 */

$qo = get_queried_object(); 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

if($paged == 1) { //featured post
	$fpost = get_term_meta($qo->term_id, 'featured_action_id', true);
	$fpost = get_post((int)$fpost);
	$cta = get_term_meta($qo->term_id, 'featured_action_сta', true);
	
	if($fpost) {
?>
<section class="featured-action"><?php krbl_featured_action_card($fpost, $cta);?></section>
<?php }} ?>

<section class="heading">
	<div class="container"><?php krbl_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container">
<div class="cards-loop sm-cols-2 md-cols-2 lg-cols-4">
	<?php
		if(have_posts()){
			foreach($posts as $p){
				krbl_person_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging">
<?php krbl_paging_nav($wp_query); ?>
</section>

<?php get_footer();
