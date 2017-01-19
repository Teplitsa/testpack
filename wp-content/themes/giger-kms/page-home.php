<?php
/**
 * Template name: Homepage
 **/


$cpost = get_queried_object();

$block_one_ids = get_post_meta($cpost->ID, 'block_one', true);
$block_one = array();

if($block_one_ids) {
	$block_one = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => 5, //limit?
		'post__in' => array_map('intval', explode(',', $block_one_ids)),
		'orderby' => 'post__in'
	));
}

$block_two_ids = get_post_meta($cpost->ID, 'block_two', true);
$block_two = array();

if($block_two_ids) {
	$block_two = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => 5, //limit?
		'post__in' => array_map('intval', explode(',', $block_two_ids)),
		'orderby' => 'post__in'
	));
}

$news = get_posts(array(
	'post_type' => 'post',
	'posts_per_page' => 1,
));

//тащ ыгззщке
$infosup_one_ids = get_post_meta($cpost->ID, 'infosup_one', true);
$infosup_one = array();
if($infosup_one_ids) {
	$infosup_one = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => -1, //limit?
		'post__in' => array_map('intval', explode(',', $infosup_one_ids)),
		'orderby' => 'post__in'
	));
}

$infosup_two_ids = get_post_meta($cpost->ID, 'infosup_two', true);
$infosup_two = array();
if($infosup_two_ids) {
	$infosup_two = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => -1, //limit?
		'post__in' => array_map('intval', explode(',', $infosup_two_ids)),
		'orderby' => 'post__in'
	));
}

$infosup_three_ids = get_post_meta($cpost->ID, 'infosup_three', true);
$infosup_three = array();
if($infosup_three_ids) {
	$infosup_three = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => -1, //limit?
		'post__in' => array_map('intval', explode(',', $infosup_three_ids)),
		'orderby' => 'post__in'
	));
}

$infosup_four_ids = get_post_meta($cpost->ID, 'infosup_four', true);
$infosup_four = array();
if($infosup_four_ids) {
	$infosup_four = get_posts(array(
		'post_type' => 'item',
		'posts_per_page' => -1, //limit?
		'post__in' => array_map('intval', explode(',', $infosup_four_ids)),
		'orderby' => 'post__in'
	));
}


get_header();?>
<section class="home-section block-one">
	<div class="flex-grid start">
		<?php
			$count = 1;
			if(!empty($block_one)) { foreach($block_one as $i => $cpost) {
				$css = 'flex-cell ';
				if($count > 5)
					$count = 1;

				switch($count){
					case 1:
						$css .= 'flex-sm-7 flex-lg-7 scheme-one';
						break;

					case 2:
						$css .= 'flex-sm-5 flex-lg-5 scheme-two';
						break;

					case 3:
						$css .= 'flex-sm-5 flex-lg-4 scheme-three';
						break;

					case 4:
						$css .= 'flex-sm-7 flex-lg-5 scheme-four';
						break;

					case 5:
						$css .= 'flex-sm-5 flex-lg-3 scheme-five';
						break;
				}

				$count++;
		?>
			<div class="<?php echo $css;?> cell-bg"><?php tst_card($cpost);?></div>
		<?php }} ?>
	</div>
</section>

<section class="home-section news">
<div class="flex-grid row-reverse start">
	<div class="flex-cell flex-md-4 flex-cell--join"><?php do_shortcode( '[tst-join-whatsapp-group]' ) ?></div>
	<div class="flex-cell flex-md-8 flex-cell--news">
		<div class="news-block">
			<div class="news-block__title"><div class="vcenter"><h3>Новости</h3></div></div>
			<div class="news-block__content">
				<?php if(!empty($news)) { tst_cell($news[0], false); } ?>
			</div>
		</div>
	</div>
</div>
</section>

<section class="home-section info-block">
	<div class="info-block__title"><h3>Информационная поддержка</h3></div>
	<div class="info-block__content">

		<div class="flex-grid">
			<!-- one -->
			<div class="flex-cell flex-sm-6 flex-md-3 infosup-cell infosup-cell--one">
				<div class="infosup-cell__icon"><div class='card__icon'><i class='material-icons'>invert_colors</i></div></div>
				<div class="infosup-cell__links">
				<?php if(!empty($infosup_one)) { ?>
					<ul class="infosup-list">
					<?php foreach($infosup_one as $l) { ?>
						<li><a href="<?php echo get_permalink($l);?>"><?php echo get_the_title($l);?></a></li>
					<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>
			<!-- two -->
			<div class="flex-cell flex-sm-6 flex-md-3 infosup-cell infosup-cell--two">
				<div class="infosup-cell__icon"><div class='card__icon'><i class='material-icons'>favorite</i></div></div>
				<div class="infosup-cell__links">
				<?php if(!empty($infosup_two)) { ?>
					<ul class="infosup-list">
					<?php foreach($infosup_two as $l) { ?>
						<li><a href="<?php echo get_permalink($l);?>"><?php echo get_the_title($l);?></a></li>
					<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>
			<!-- three -->
			<div class="flex-cell flex-sm-6 flex-md-3 infosup-cell infosup-cell--three">
				<div class="infosup-cell__icon"><div class='card__icon'><i class='material-icons'>import_contacts</i></div></div>
				<div class="infosup-cell__links">
				<?php if(!empty($infosup_three)) { ?>
					<ul class="infosup-list">
					<?php foreach($infosup_three as $l) { ?>
						<li><a href="<?php echo get_permalink($l);?>"><?php echo get_the_title($l);?></a></li>
					<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>
			<!-- four -->
			<div class="flex-cell flex-sm-6 flex-md-3 infosup-cell infosup-cell--four">
				<div class="infosup-cell__icon"><div class='card__icon'><i class='material-icons'>healing</i></div></div>
				<div class="infosup-cell__links">
				<?php if(!empty($infosup_four)) { ?>
					<ul class="infosup-list">
					<?php foreach($infosup_four as $l) { ?>
						<li><a href="<?php echo get_permalink($l);?>"><?php echo get_the_title($l);?></a></li>
					<?php } ?>
					</ul>
				<?php } ?>
				</div>
			</div>

		</div>

	</div>
</section>

<section class="home-section block-two">
	<div class="flex-grid start">
		<?php
			$count = 1;
			if(!empty($block_one)) { foreach($block_two as $i => $cpost) {
				$css = 'flex-cell ';
				if($count > 3)
					$count = 1;

				switch($count){
					case 1:
						$css .= 'flex-sm-6 flex-lg-4 scheme-one';
						break;

					case 2:
						$css .= 'flex-sm-6 flex-lg-4 scheme-two';
						break;

					case 3:
						$css .= 'flex-sm-12 flex-lg-4 scheme-five';
						break;
				}

				$count++;
		?>
			<div class="<?php echo $css;?> cell-bg"><?php tst_card($cpost);?></div>
		<?php }} ?>
	</div>
</section>


<?php get_footer(); ?>