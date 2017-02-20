<?php
/**
 * The template for landings
 *
 * @package bb
 */


//var_dump($wp_query->posts);
$cpost = get_queried_object();
$about = get_query_var('item_about');

get_header();?>
<?php
	if($about && $about == 1)  { //print about page markup
		//$cpost = $wp_query->posts[0]; var_dump($cpost)
?>

<article class="landing-about">
	<div class="container">
		<h1><?php printf(__('%s - What are we doing', 'tst'),  get_the_title($cpost)); ?></h1>
		<div class=""><?php echo apply_filters('tst_entry_the_content', get_post_meta($cpost->ID, 'landing_content', true)); ?></div>
	</div>
</article>

<?php }  else { ?>

<article class="landing">
	<?php wds_page_builder_area( 'page_builder_default' );?>
</article>

<?php }

get_footer();