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
				<li class="work-menu__item">
					<a href="<?php echo get_permalink($l);?>" class="work-menu__link">
					<?php tst_svg_icon($icon_id); ?><span class='work-menu__label'><?php echo get_the_title($l);?></span>
					</a>
				</li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div></section>

	<section class="home-cta">
		<?php wds_page_builder_area('cta'); ?>
	</section>

<?php
	$parnters = get_post_meta($cpost->ID, 'home_partners', true);
	if(!empty($parnters)) {
?>
	<section class="home-partners"><div class="container">
		<h3 class="home-partners__title"><?php _e('Our partners', 'tst');?></h2>
		<div class="home-partners__content">
			<div class="flex-grid--stacked flex-grid--partners centered">
			<?php foreach($parnters as $p) {

				$url = esc_url($p['home_partner_url']);
				$url_label = str_replace(array('http://', 'https://'), '', untrailingslashit($url));
				$tx = apply_filters('tst_the_title', $p['home_partner_title']);
			?>
				<div class="flex-cell flex-md-6 flex-lg-3 partner"><a href="<?php echo $url;?>" class="partner__link">
					<div class="partner__label"><?php echo $url_label;?></div>
					<h4 class="partner__title"><?php echo $tx;?></h4>
				</a></div>
			<?php } ?>
			</div>
		</div>
	</div></section>
<?php } ?>
</article>


<?php get_footer(); ?>