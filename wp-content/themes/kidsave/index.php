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

<section class="intro">
<?php
	if(is_home() && $paged == 1) { //featured post
		kds_featured_post_card($featured[0]);
	}
	else {
		echo '<div class="container for-title">';
		kds_section_title();
		echo '</div>';
	} ?>	
</section>

<section class="main-content"><div class="container">

<div class="frame">
	<div class="bit md-12 hide-on-large">
		<aside class="sidebar mobile">		
			<?php dynamic_sidebar( 'right_top-sidebar' ); ?>
		</aside>
	</div>
	
	<div class="bit lg-9 bit-no-margin">
		<main class="loop">
		<?php
			if(have_posts()){
				foreach($posts as $p){
					kds_post_card($p);
				}
			}
			else {
				echo '<p>Ничего не найдено</p>';
			}
			
			kds_paging_nav($wp_query);
		?>		
		</main>
	</div>
	
	<div class="bit lg-3 hide-upto-large">	
		<aside class="sidebar">		
			<?php dynamic_sidebar( 'right_top-sidebar' ); ?>
			<?php dynamic_sidebar( 'right_bottom-sidebar' ); ?>			
		</aside>
	</div>
</div><!-- frame -->

</div></section>

<?php
	$pquery = new WP_Query(array('post_type'=> 'programm', 'posts_per_page' => 4, 'orderby' => 'rand'));
	if($pquery->have_posts()){
?>
<section class="addon"><div class="container">
	<?php kds_more_section($pquery->posts, __('More about our programms', 'kds'), 'programms'); ?>
</div></section>
<?php }

get_footer();