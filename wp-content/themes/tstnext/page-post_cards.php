<?php
/**
 * Template Name: Cards from posts
 */
 
 
//body class
function tst_pcards_body_classes( $classes ) {
	global $post;
	
	$test = get_post_meta($post->ID, 'page_content_on', true);
	if($test == 'top' && !empty($post->post_content))
		$classes[] = 'cards-page-preface';
		
	return $classes;
}
add_filter( 'body_class', 'tst_pcards_body_classes' ); 


$post_type =  get_post_meta(get_the_ID(), 'cards_post_type', true);
$tax = ($post_type == 'children') ? 'children_status' : '';
$term_id =  get_post_meta(get_the_ID(), 'filter_'.$tax, true); 
$text_place = get_post_meta(get_the_ID(), 'page_content_on', true);

get_header();

$args = array(
	'post_type' => $post_type,
	'posts_per_page' => -1,
	'orderby' => 'rand'
);

if(!empty($term_id)){
	$args['tax_query'] = array(
		array(
			'taxonomy' => $tax,
			'field' => 'id',
			'terms' => $term_id
		)
	);	
}

$query = new WP_Query($args);


if($query->have_posts()) {
?>
<div class="mdl-grid">
<?php if($text_place == 'top' && !empty($post->post_content)) { ?>
	<div class="mdl-cell mdl-cell--12-col cards-preface-top"><div class="entry-content">
	<?php
		while(have_posts()){
			the_post();
			the_content();
		}
	?>
	</div></div>
<?php
	}
	
	foreach($query->posts as $p){
		$callback = "tst_".$p->post_type."_inpage_card";
		if(is_callable($callback)) {
			call_user_func($callback, $p);
		}
		else {
			tst_general_inpage_card($p);
		}	
	}
	
	if($text_place == 'bottom' && !empty($post->post_content)) { ?>
		<div class="mdl-cell mdl-cell--12-col cards-preface-bottom"><div class="entry-content">
		<?php
			while(have_posts()){
				the_post();
				the_content();
			}
		?>
		</div></div>
<?php
	}
?>	
</div>
<?php } ?>

<?php get_footer(); ?>