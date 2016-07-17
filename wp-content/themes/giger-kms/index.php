<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

if(is_home() && $paged == 1) { //featured posts
	//2 for featured 
	$featured = array_slice($posts, 0, 1); 
	array_splice($posts, 0, 1);
?>
<section class="featured-post"><div class="container">
<div class="cards-loop sm-cols-1">
	<?php
		foreach($featured as $f){
			tst_featured_post_card($f);
		}
	?>
</div>
</div></section>
<?php } ?>

<section class="heading">
	<div class="container">
		<?php tst_section_title(); ?>
		
	</div>
</section>

<section class="main-content cards-holder"><div class="container">
	
		<div class="cards-loop sm-cols-2 md-cols-3">
			<?php
				if(!empty($posts)){
					foreach($posts as $p){
						tst_post_card($p);
					}
				}
				else {
					echo '<p>Ничего не найдено</p>';
				}
			?>
		</div>
		
		<div class="paging"><?php tst_paging_nav($wp_query); ?></div>
</div></section>

<?php get_footer();