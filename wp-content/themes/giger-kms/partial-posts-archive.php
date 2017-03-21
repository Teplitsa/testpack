<?php
/**
 * Common archive template
 */

get_header();
?>
<article class="landing landing--news">
	<section class="section-intro">
		<header class="landing-header landing-header--news">
			<div class="cover-general__title container">
				<h1 class="landing-header__title"><?php echo apply_filters( 'tst_the_title', $title );?></h1>
				<?php if(!empty($desc)) { ?>
					<div class="landing-header__tagline"><?php echo $desc;?></div>
				<?php } ?>

				<?php if(!empty($crumb)) {?>
					<div class="landing-header__links"><?php echo $crumb;?></div>
				<?php } ?>
			</div>

		</header>
	</section>

	<?php
	    $archive_query = $wp_query;
	    include( get_template_directory() . '/partial-posts-archive-items.php' );
	?>
	
</article>
<?php get_footer();