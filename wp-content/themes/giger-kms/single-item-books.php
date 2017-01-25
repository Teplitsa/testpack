<?php
/** Single Template for items **/

$cpost = get_queried_object();
$item = new TST_Item($cpost);


get_header();
?>

<section class="main">
	<div class="single-item--title"><h1><?php echo apply_filters('tst_the_title', $item->get_root_title());?></h1></div>

	<div class="frame">
	<?php
    	$menu = $item->get_menu();
    	$sidebar = $item->get_sidebar();
    	
    	$books = get_posts(array(
    	    'post_type' => 'book',
    	    'posts_per_page' => -1,
    	    'no_found_rows' => true,
    	    'cache_results' => true,
    	    'update_post_meta_cache' => false,
    	    'update_post_term_cache ' => false,
    	));
    		
	?>
    	
		<div class="bit md-8 lg-8">
			<div class="layout-section layout-section--card">
			<?php
			    foreach($books as $i => $cpost) {
			?>
				<div class="layout-section__item layout-section__item--card"><?php tst_book_item( $cpost );?></div>
			<?php
				}
			?>
				<div class="sharing"><?php tst_social_share($item->post_object);?></div>
			</div>
		</div>
		<div class="bit md-3 lg-3 lg-offset-1 single-side">
			<?php if( $item->post_name == 'your-question' ):?>
				<?php do_shortcode( '[tst_join_whatsapp_group]' ) ?>
			<?php else:?>
				<?php echo $sidebar;?>
			<?php endif?>
		</div>
		
	</div>
</section>

<?php
get_footer();