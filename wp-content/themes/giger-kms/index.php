<?php
/**
 * The main template file.
 */

$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;
$title = __('News', 'tst');

if(is_tag()){
	$title = '#'.get_queried_object()->name;
}

get_header();
?>
<section class="main"><div class="container-narrow">


	<?php if(!empty($posts)) { foreach($posts as $i => $cpost) { ?>
		<div class="layout-section__item layout-section__item--card"><?php tst_cell($cpost);?></div>

	<?php
		}
		if ($paged) {
		?>
			<div class="nav-links"><?php
				previous_posts_link('<span class="meta-nav">&larr; Пред. страница</span>');
				next_posts_link('<span class="meta-nav">След. страница &rarr;</span>');
			?></div>
			<?php
		}
		}
		else {
			echo "<p>".__('Nothing found under your request', 'tst')."</p>";
		}
	?>




</div></section>

<?php get_footer();