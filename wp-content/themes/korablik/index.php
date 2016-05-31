<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

//number one for featured as for now
$featured = array_slice($posts, 0, 1); 
array_splice($posts, 0, 1);


get_header();
?>

<?php if(is_home() && $paged == 1) { //featured post ?>
<section class="featured-post"><?php krbl_featured_post_card($featured[0]);?></section>
<?php } ?>

<section class="heading">
	<div class="container"><?php krbl_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">
	<?php
		if(have_posts()){
			foreach($posts as $p){
				krbl_post_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging"><?php krbl_paging_nav($wp_query); ?></section>

<?php get_footer();