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
		<div class="nav-links__link nav-links__link--next">
			<a href="<?php echo get_permalink($parent);?>"><span><?php tst_svg_icon('icon-prev');?></span><span><?php echo get_the_title( $parent ); ?></span></a>
		</div>
	<?php }?>
	<?php if($connected) { ?>
		<div class="nav-links__link nav-links__link--prev">
			<a href="<?php echo get_permalink($connected[0]);?>"><span><?php tst_svg_icon('icon-next');?></span><span><?php echo get_the_title( $connected[0] ); ?></span></a>
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




/** == OLD == **/
function tst_single_breadcrubms() {

	$cpost = get_queried_object();

	$list = array();

	$list[] = "<a href='".home_url('/')."'>".__('Home', 'tst')."</a>";
	$list[] = "<a href='".home_url('news')."'>".__('News', 'tst')."</a>";

	$sep = tst_get_sep('&gt;');

	return "<div class='crumbs'>".implode($sep, $list)."</div>";
}

function tst_item_breadcrubms() {

	$cpost = get_queried_object();
	$sep = tst_get_sep('&gt;');

	$list[] = "<a href='".home_url('/')."'>".__('Home', 'tst')."</a>";

	$section = get_the_terms($cpost, 'section');
	if(!empty($section)) {
		$list[] = "<a href='".get_term_link($section[0])."'>".$section[0]->name."</a>";
	}

	if($cpost->parent > 0) {
		$list[] = "<a href='".get_permalink($cpost->parent)."'>".get_the_title($cpost->parent)."</a>";
	}

	return "<div class='crumbs'>".implode($sep, $list)."</div>";
}

function tst_related_list($posts) {

	if(empty($posts))
		return;

?>
	<ul class="realted-posts">
	<?php foreach($posts as $p) { ?>
		<li class="related-posts__item">
			<a href="<?php echo get_permalink($p);?>" class="related-posts__link">
				<?php echo get_the_title($p);?> <span class="related-posts__date"><?php echo get_the_date('d.m.Y', $p);?></span>
			</a>
		</li>
	<?php }?>
	</ul>
<?php
}

function tst_single_post_meta(WP_Post $cpost) {

	$list = array();
	$list[] = "<span class='date'>".get_the_date('d.m.Y', $cpost)."</span>";

	$tags = tst_get_tags_list($cpost);
	if(!empty($tags))
		$list[] = $tags;

	return implode(' ', $list);
}




function tst_single_thumbnail(WP_Post $cpost) {

	$thumb = $cap = '';

	$cap = tst_get_post_thumbnail_cation($cpost);
	$cap = apply_filters('tst_the_content', $cap);

	$thumb_args = array(
		'placement_type'	=> 'medium-medium-large-large-large',
		'aspect_ratio' 		=> 'standard',
		'crop' 				=> 'flex'
	);

	//$thumb = tst_get_post_thumbnail_picture($cpost, $thumb_args);

	$thumb = get_the_post_thumbnail($cpost, 'block-single');
?>
	<figure class="fixed-thumbnail--single">
		<?php echo $thumb; ?>
		<?php if($cap) { ?>
			<figcaption><?php echo $cap; ?></figcaption>
		<?php } ?>
	</figure>
<?php
}