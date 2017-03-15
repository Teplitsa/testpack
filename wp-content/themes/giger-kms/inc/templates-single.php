<?php
/**
 * Elements of single templates
*/


/** next/previous post  */
function tst_single_post_nav(WP_Post $cpost) {

	$previous = get_adjacent_post(false, '', true);
	$next = get_adjacent_post(false, '', false);

	$parent = $cpost->post_parent;

	$connected = get_posts( array(
		'posts_per_page' => 1,
		'post_type' => 'project',
		'orderby' => 'title',
		'order' => 'ASC',
		'post_parent' => $cpost->ID
	));

	if(!$previous && !$next) {
		return;
	}?>
	<div class="nav-links">
	<?php if($parent) { ?>
		<div class="nav-links__link nav-links__link--next <?php if($parent && $connected) echo "nav-links__link--double-nav" ?>">
			<a href="<?php echo get_permalink($parent);?>"><span><?php tst_svg_icon('icon-prev');?></span><span class="link"><?php echo get_the_title( $parent ); ?></span></a>
		</div>
	<?php }?>
	<?php if($connected) { ?>
		<div class="nav-links__link nav-links__link--prev <?php if($parent && $connected) echo "nav-links__link--double-nav" ?>">
			<a href="<?php echo get_permalink($connected[0]);?>"><span><?php tst_svg_icon('icon-next');?></span><span class="link"><?php echo get_the_title( $connected[0] ); ?></span></a>
		</div>
	<?php }?>
	</div>
<?php
}

function tst_single_cta(WP_Post $cpost, $mod = 'pictured') {

	$page = null;

	$types = array('donate', 'problem', 'volunteer', 'corporate');
	$r = array_rand($types);
	$type = $types[$r];


	if($type == 'donate') {
		$page = get_page_by_path($type, 'OBJECT', 'leyka_campaign');
		$label = __('Donate', 'tst');
	}
	else {
		$page = get_page_by_path($type);
		$label = get_the_title($page);
	}
	if($type == 'problem') {
		$page = get_page_by_path('dront-ecomap', 'OBJECT', 'landing');
		$label = __('Inform about problem', 'tst');
	}

	if(empty($page))
		return;

	$pl = get_permalink($page->ID);

?>
<a href="<?php echo $pl;?>" class="card-link card--cta">
	<?php if($mod == 'pictured' && has_post_thumbnail($page)) { ?>
		<div class="card__thumbnail">
			<?php echo get_the_post_thumbnail($page, "block-small"); ?>
		</div>
	<?php } ?>

	<div class="card__label--iconic"><div class="card__label-wrap">
		<div class="card__icon">
			<?php tst_svg_icon('icon-'.$type); ?>
		</div>
		<h4><?php echo apply_filters('tst_the_title', $label);?></h4>
	</div></div>
</a>

<?php
}


function tst_single_thumbnail(WP_Post $cpost) {

	$thumb = $cap = '';

	$cap = tst_get_post_thumbnail_caption($cpost);
	$cap = apply_filters('tst_the_content', $cap);

	$img_id = get_post_thumbnail_id($cpost);
	$thumb = tst_get_picture_markup($img_id, 'block-single');
?>
	<figure class="post-thumbnail--single">
		<?php echo $thumb; ?>
		<?php if($cap) { ?>
			<figcaption><?php echo $cap; ?></figcaption>
		<?php } ?>
	</figure>
<?php
}