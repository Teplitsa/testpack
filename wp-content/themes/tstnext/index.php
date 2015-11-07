<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bb
 */

get_header(); ?>
<div class="mdl-grid masonry-grid">
	
	<?php
		if(have_posts()){
			while(have_posts()){
				the_post();
				
				$pt = get_post_type();
				if($pt == 'post'){
					$tax = (tst_has_authors()) ? 'auctor' : 'category';
					tst_post_card($post, $tax);
				}
				else {
					$callback = "tst_".$pt."_card";
					if(is_callable($callback)) {
						call_user_func($callback, $post);
					}
					else {
						tst_post_card($post);
					}	
				}
				
			}  		
		}		
	?>
	<div class="mdl-cell mdl-cell--4-col masonry-item movable-widget"><?php get_sidebar(); ?></div>
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
