<?php
/**
 * Template Name: Partners
 */

get_header();

$partners = new WP_Query(array(
	'post_type' => 'org',
	'posts_per_page' => -1,
	'orderby' => 'rand'
));

if($partners->have_posts()) {
?>
<div class="mdl-grid">
<?php
	foreach($partners->posts as $p){
		tst_org_card($p);
	}
?>	
</div>
<?php } ?>

<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	<div class="mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet">
	<?php
		while(have_posts()){
			the_post();
			the_content();
		}
	?>
	</div>
	<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
</div>

<?php get_footer(); ?>
