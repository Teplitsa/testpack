<?php
/**
 * Micro elements
 **/

function tst_cell(WP_Post $cpost) {

	$pl = get_permalink($cpost);
	$tags = tst_get_tags_list($cpost);
	$ex = tst_get_post_excerpt($cpost, 25);

?>
	<article class="cell">
		<h4 class="cell__title">
			<a href="<?php echo $pl;?>"><?php echo get_the_title($cpost);?></a>
			<span class="date"><?php echo get_the_date('d.m.Y', $cpost);?></span>
		</h4>
		<div class="cell__text">
			<p><?php echo apply_filters('tst_the_title', $ex);?></p>
			<p><?php echo $tags;?></p>
		</div>
		<?php if(has_post_thumbnail($cpost)) { ?>
			<div class="cell__thumb"><a href="<?php echo $pl;?>" class="thumbnail-link">Preview</a></div>
		<?php }?>
	</article>
<?php
}

function tst_card(WP_Post $cpost) {

	$pl = get_permalink($cpost);
?>
	<article class="card"><a href="<?php echo $pl;?>" class="card__link">
		<div class="card__thumb">Thumb</div>
		<h4 class="card__title"><?php echo get_the_title($cpost);?></h4
	</a></article>
<?php
}

/** == Helpers == **/

/** Excerpt **/
function tst_get_post_excerpt($cpost, $l = 30, $force_l = false){

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$e = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);

	return $e;
}

function tst_get_tags_list(WP_Post $cpost) {

	$tags = get_the_terms($cpost, 'post_tag');
	if(empty($tags))
		return '';

	$list = array();
	foreach($tags as $tag){
		$l = get_term_link($tag);
		$list[] = "<a href='{$l}' class='tag'>#".$tag->name."</a>";
	}

	return "<span class='tags-list'>".implode(', ', $list)."</span>";
}