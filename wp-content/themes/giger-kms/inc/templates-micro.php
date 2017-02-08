<?php
/**
 * Micro elements
 **/

function tst_cell(WP_Post $cpost) {

	$pl = get_permalink($cpost);
	$tags = tst_get_tags_list($cpost);
	$ex = tst_get_post_excerpt($cpost, 25);

	$thumb_mark = '';
	if(has_post_thumbnail($cpost)) {
		$cap = tst_get_post_thumbnail_cation($cpost);

		$thumb_args = array(
			'placement_type'	=> 'small-medium-medium-medium-medium',
			'aspect_ratio' 		=> 'standard',
			'crop' 				=> 'fixed'
		);

		$thumb = tst_get_post_thumbnail_picture($cpost, $thumb_args);

		//build thumbnail markup
		ob_start();
		
?>
		<figure class="cell_picture">
			<a href="<?php echo $pl;?>" class="thumbnail-link"><?php echo $thumb;?></a>
			<?php if($cap) { ?>
				<figcaption><?php echo $cap; ?></figcaption>
			<?php } ?>
		</figure>
<?php
		$thumb_mark = ob_get_contents();
		ob_end_clean();
	} else {
		// if no thumbnail		
		$output = preg_match_all('/<img(.*?)src=("|\'|)(.*?)("|\'| )(.*?)>/s', $cpost->post_content, $match);
		if($output) {
			$file_url = $match[3][0];
			$found_attachment_id = TST_Import::get_instance()->get_attachment_id_by_url( $file_url );
			$cap = set_post_thumbnail($cpost,$found_attachment_id);
		}
	}
?>

	<article class="cell">
		<div class="frame">
			<div class="bit sm-8">
				<h4 class="cell__title">
					<a href="<?php echo $pl;?>"><?php echo get_the_title($cpost);?></a>
					<span class="date"><?php echo get_the_date('d.m.Y', $cpost);?></span>
				</h4>
				<div class="cell__text">
					<p><?php echo apply_filters('tst_the_title', $ex);?></p>
					<p><?php echo $tags;?></p>
				</div>
			</div>
			<div class="bit sm-4">
				<?php if(!empty($thumb_mark)) { ?>
					<div class="cell__thumb"><?php echo $thumb_mark;?></div>
				<?php }?>
			</div>
	</article>
<?php
}

function tst_project_cell(WP_Post $cpost) {

	$ex = tst_get_post_excerpt($cpost, 25);

?>
	<article class="cell cell--project">
		<h4 class="cell__title cell__title--project">
			<?php echo get_the_title($cpost);?>
		</h4>
		<div class="cell__subtitle"><?php echo apply_filters('tst_the_title', $ex);?></div>
		<div class="cell__text cell__text--project">
			<?php echo apply_filters('tst_the_content', $cpost->post_content);?>
		</div>

	</article>
<?php
}

function tst_card(WP_Post $cpost, $show_icon = true) {

	$pl = get_permalink($cpost);
	$thumb_mark = tst_get_card_icon($cpost);
	$css = 'has-icon';


	if(!$show_icon || empty($thumb_mark)){
		$css = 'has-thumb';

		$thumb_args = array(
			'placement_type'	=> 'small-medium-small-small-small',
			'aspect_ratio' 		=> 'standard',
			'crop' 				=> 'fixed'
		);

		$thumb_mark = tst_get_post_thumbnail_picture($cpost, $thumb_args);
		$thumb_mark = "<div class='card__thumb'>{$thumb_mark}</div>";
	}

?>
<article class="card <?php echo $css;?>"><a href="<?php echo $pl;?>" class="card__link">
	<?php echo $thumb_mark;?>
	<h4 class="card__title"><?php echo get_the_title($cpost);?></h4>
</a></article>
<?php
}

function tst_news_card() {

?>
<article class="card has-icon card--news"><a href="<?php echo home_url('news');?>" class="card__link">
	<div class='card__icon'><i class='material-icons'>receipt</i></div>
	<h4 class="card__title"><?php _e('News', 'tst');?></h4>
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

function tst_get_card_icon($cpost) {

	$icon_id = get_post_meta($cpost->ID, 'icon_id', true);
	if(!$icon_id)
		return '';

	$out = "<div class='card__icon'><i class='material-icons'>{$icon_id}</i></div>";
	return $out;

}

/** Search card **/
function tst_card_search(WP_Post $cpost) {

	$pl = get_permalink($cpost);
	$tags = tst_get_tags_list($cpost);
	$cats = tst_get_search_cats($cpost);


?>
<article class="cell">
	<h4 class="card-search__title cell__title">
		<a href="<?php echo $pl;?>"><?php echo get_the_title($cpost);?></a>
	</h4>
	<?php if(!empty($cats)) { ?>
		<div class="card-search__meta"><?php echo $cats;?></div>
	<?php } ?>
	<div class="card-search__summary"><?php echo tst_get_post_excerpt($cpost, 25, true);?></div>
	<?php if(!empty($tags)) { ?>
		<div class="card-search__meta"><?php echo $tags;?></div>
	<?php } ?>
</article>
<?php
}

function tst_get_search_cats(WP_Post $cpost) {

	$terms = get_the_terms($cpost, 'section');
	$list = array();

	if(!empty($terms)){ foreach($terms as $t) {
		if($t->slug == 'about'){
			$list[] = "<a href='".home_url('about-us')."'>О нас</a>";
		}
		else {
			$list[] = "<a href='".get_term_link($t)."'>".apply_filters('tst_the_title', $t->name)."</a>";
		}
	}}
	elseif($cpost->post_type == 'book') {
		$item = get_page_by_title('Книги и брошюры', OBJECT, 'item');
		if($item)
			$list[] = "<a href='".get_permalink($item)."'>".apply_filters('tst_the_title', $item->post_title)."</a>";
	}
	elseif($cpost->post_type == 'project') {
		$item = get_page_by_title('Проекты', OBJECT, 'page');
		if($item)
			$list[] = "<a href='".get_permalink($item)."'>".apply_filters('tst_the_title', $item->post_title)."</a>";
	}
	elseif($cpost->post_type == 'story') {
		$list[] = "<a href='".home_url('stories')."'>Истории</a>";
	}




	$out = (!empty($list)) ? "<span class='category'>".implode(',', $list)."</span>" : '';
	return $out;
}