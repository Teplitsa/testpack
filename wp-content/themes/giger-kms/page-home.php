<?php
/**
 * Template name: Homepage
 **/


$cpost = get_queried_object();

get_header();?>

<article class="landing landing--home">
	<section class="home-intro"><?php wds_page_builder_area( 'page_builder_default' );?></section>

	<section class="home-about"><div class="container">
		<h2 class="home-about__title"><?php echo get_the_title($cpost);?></h2>
		<div class="home-about__text"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		<?php
			$lands = get_posts(array(
				'post_type' => 'landing',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'section',
						'field' => 'slug',
						'terms' => 'work'
					)
				)
			));

			if(!empty($lands)) {
		?>
		<div class="home-about__menu">
			<ul class="work-menu">
			<?php foreach($lands as $l) { $icon_id = 'icon-item-'.$l->post_name;; ?>
				<li class="work-menu__item"><?php tst_svg_icon($icon_id); ?><span class='work-menu__label'><?php echo get_the_title($l);?></span></li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div></section>

	<section class="home-cta"><div class="container">
		<?php wds_page_builder_area('cta'); ?>
	</div></section>

<?php
	$parnters = get_post_meta($cpost->ID, 'home_partners', true);
	if(!empty($parnters)) {
?>
	<section class="home-partners"><div class="container">
		<h3 class="home-partners__title"><?php _e('Our partners');?></h2>
		<div class="home-partners__content">
			<div class="flex-grid--stacked flex-grid--partners centered">
			<?php foreach($parnters as $p) { ?>
				<div class="flex-cell--stacked sm-12 md-4 lg-3 partner">

				</div>
			<?php } ?>
			</div>
		</div>
	</div></section>
<?php } ?>
</article>


<?php get_footer(); ?>