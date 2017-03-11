<?php
/**
 * Micro elements
 **/

/* Picture with label */
function tst_card_linked($cpost, $args = array()) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$defaults = array(
		'size' => 'block-2col',
		'show_desc' => (in_array($cpost->post_type, array('landing'))) ? true : false
	);

	$args = wp_parse_args($args, $defaults);
	$pl = $cpost->post_type == 'attachment' ? wp_get_attachment_url($cpost->ID) : get_permalink($cpost);
	$desc = '';

	if($args['show_desc']) {
		$desc = tst_get_post_excerpt($cpost, 10, true);
	}

	$meta = tst_get_post_meta($cpost);
?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__thumbnail">
		<?php echo tst_get_the_post_thumbnail($cpost, $args['size']); ?>
	</div>

	<div class="card__label">
		<?php if(!empty($meta)) {?>
			<div class="card__meta"><?php echo $meta;?></div>
		<?php } ?>

		<h4><?php echo get_the_title($cpost);?></h4>

		<?php if(!empty($desc)) {?>
			<div class="card__insummary"><?php echo apply_filters('tst_the_title', $desc);?></div>
		<?php } ?>
	</div>
</a>
<?php
}

/* Colored panel **/
function tst_card_colored($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$pl = $icon = $title = $summary = '';

	if($cpost->post_type == 'attachment') {
		$pl = wp_get_attachment_url($cpost->ID);
		$icon = tst_svg_icon('icon-pdf', false);
		$title = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : $cpost->post_title;
		$summary = $cpost->post_content;

	}
	else {
		$pl = get_permalink($cpost);
		$title = get_the_title($cpost);
		$summary = tst_get_post_excerpt($cpost, 20);
	}

?>
<a href="<?php echo $pl;?>" class="card-link <?php if(!empty($icon)) { echo 'has-icon'; }?>">
	<div class="card__title">
		<h4><?php echo apply_filters('tst_the_title', $title);?></h4>
	</div>

	<?php if(!empty($summary)) { ?>
	<div class="card__summary">
		<?php echo apply_filters('tst_the_content', $summary); ?>
	</div>
	<?php } ?>

	<?php if(!empty($icon)) { ?>
	<div class="card__icon">
		<?php echo $icon; ?>
	</div>
	<?php }?>
</a>
<?php
}

/* Title with text and link  */
function tst_card_text($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$args = array();

	//link
	$args['action_url'] = get_permalink($cpost);
	$args['action_text'] = get_post_meta($cpost->ID, 'action_text', true);
	if(empty($args['action_text']))
		$args['action_text'] = __('Details', 'tst');

	//title
	$args['title'] = get_the_title($cpost);
	$args['subtitle'] = tst_get_post_meta($cpost);

	//text
	$args['summary'] = tst_get_post_excerpt($cpost, 20);

	tst_card_text_markup($args);
}

/* Markup helper for text card */
function tst_card_text_markup($args = array()) {

	$defaults = array(
		'title' => '',
		'subtitle' => '',
		'summary' => '',
		'action_text' => '',
		'action_url' => ''
	);

	$args = wp_parse_args($args, $defaults);

	if(!empty($args['title'])) { ?>
		<div class="card__title card__title--text"><h4><?php echo apply_filters('tst_the_title', $args['title']);?></h4></div>
	<?php } ?>

	<?php if(!empty($args['subtitle'])) { ?>
		<div class="card__subtitle"><?php echo apply_filters('tst_the_title', $args['subtitle']);?></div>
	<?php } ?>

	<?php if(!empty($args['summary'])) { ?>
		<div class="card__summary"><?php echo apply_filters('tst_the_title', $args['summary']);?></div>
	<?php } ?>

	<?php if(!empty($args['action_url']) && !empty($args['action_text'])) { ?>
		<div class="card__action">
			<a href="<?php echo $args['action_url'];?>">
				<?php echo apply_filters('tst_the_title', $args['action_text']);?>&nbsp;&gt;
			</a>
		</div>
	<?php }
}




/** == Cards by context type == **/

/* News in news block */
function tst_news_card($cpost, $mod = 'pictured') {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$pl = get_permalink($cpost);
?>
<a href="<?php echo $pl;?>" class="card-link">
	<?php if($mod == 'pictured' && has_post_thumbnail($cpost)) { ?>
		<div class="card__thumbnail">
			<?php echo tst_get_the_post_thumbnail($cpost, "block-1col"); ?>
		</div>
	<?php } ?>

	<div class="card__title">
		<h4><?php echo get_the_title($cpost);?></h4>
	</div>

	<div class="card__summary">
		<?php echo apply_filters('tst_the_content', tst_get_post_excerpt($cpost, 20)); ?>
	</div>

	<div class="card__meta"><?php echo tst_get_post_meta($cpost); ?></div>
</a>
<?php
}

/** Text card in News archive **/
function tst_card_news_text($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);


?>
<a href="<?php echo get_permalink($cpost);?>" class="card-link">
	<div class="card__meta"><?php echo wp_trim_words(tst_get_post_meta($cpost), 20);?></div>
	<div class="card__title card__title--newstext"><h4><?php echo get_the_title($cpost);?></h4></div>

	<div class="card__summary"><?php echo tst_get_post_excerpt($cpost, 20);?></div>
</a>
<?php
}

/* News in sidebars */
function tst_news_apart_card($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$pl = get_permalink($cpost);
?>
<a href="<?php echo $pl;?>" class="card-link card--news-apart">
	<div class="card__thumbnail">
		<?php echo tst_get_the_post_thumbnail($cpost, "block-1col"); ?>
	</div>

	<div class="card__label">
		<div class="card__title">
			<h4><?php echo get_the_title($cpost);?></h4>
		</div>
		<div class="card__summary">
			<?php echo apply_filters('tst_the_content', tst_get_post_excerpt($cpost, 20)); ?>
		</div>

		<div class="card__meta">
			<?php echo tst_get_post_meta($cpost); ?>
		</div>
	</div>
</a>
<?php
}

/* Person in list of people */
function tst_person_card($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);


	$name = apply_filters('tst_the_title', $cpost->post_title);
	$role = apply_filters('tst_the_title', $cpost->post_excerpt);

	$thumb = get_the_post_thumbnail($cpost, 'thumbnail');
?>
<article class="person-item">
	<div class="person-item__thumbnail"><?php echo $thumb;?></div>
	<h4 class="person-item__title"><a href="<?php echo get_permalink($member);?>"><?php echo $name;?></a></h4>
	<div class="person-item__role"><?php echo $role;?></div>
</article>

<?php
}

/* Person as single item in card */
function tst_single_person_card($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);


	$name = apply_filters('tst_the_title', $cpost->post_title);
	$role = apply_filters('tst_the_title', $cpost->post_excerpt);

	$thumb = get_the_post_thumbnail($cpost, 'thumbnail');

	$content = $cpost->post_content;
?>
<article class="single-person-item">
	<a class="back_to_list" href="<?php echo home_url('nashi-lyudi') ?>">Назад к списку сотрудников</a>
	<div class="single-person-item__thumbnail"><?php echo $thumb;?></div>
	<h4 class="single-person-item__title"><a href="<?php echo get_permalink($member);?>"><?php echo $name;?></a></h4>
	<div class="single-person-item__role"><?php echo $role;?></div>
	<div class="single-person-item__content">
		<?php echo $content;?>

	</div>
</article>

<?php
}

/* Square block for sidebars */
function tst_card_iconic($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$pl = get_permalink($cpost);

	if(has_term('departments', 'section', $cpost)){
		$test_file = get_template_directory().'/src/svg/icon-item-departments.svg';
		$icon_id = 'icon-item-departments';
	}
	else {
		$test_file = get_template_directory().'/src/svg/icon-item-'.$cpost->post_name.'.svg';
		$icon_id = 'icon-item-'.$cpost->post_name;
	}


	if(file_exists($test_file)){
		$icon = tst_svg_icon($icon_id, false);
	}
	else {
		$icon = tst_svg_icon('icon-item-default', false);
	}
?>
<a href="<?php echo $pl;?>" class="card-link">

	<div class="card__icon">
		<?php echo $icon; ?>
	</div>
	<div class="card__title">
		<h4><?php echo get_the_title($cpost);?></h4>
	</div>
</a>
<?php
}


/* Card for help section - picture with badge  */
function tst_linked_help_card($help, $img_id = 0, $args = array()){

	$defaults = array(
		'size' => 'block-2col',
		'title' => __('Support our programms', 'tst'),
		'button' => __('Donate', 'tst'),
	);

	$args = wp_parse_args($args, $defaults);

	$pl = get_permalink($help);
	$thumbnail = '';

	if($img_id > 0){
		$thumbnail = tst_get_picture_markup($img_id, $args['size']);
	}
	else {
		$thumbnail = tst_get_the_post_thumbnail($help, $args['size']);
	}
?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__thumbnail">
		<?php echo $thumbnail; ?>
	</div>

	<div class="card__label">
		<h4><?php echo $args['title'];?></h4>
		<div class="card__button"><?php echo $args['button'];?></div>
	</div>
</a>
<?php
}


/* Card for help section - colored panel */
function tst_colored_help_card($help_id) {

	$pl = get_permalink($help_id); //volunteer url here
?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__title">
		<h4><?php _e('Join our actions', 'tst');?></h4>
	</div>

	<div class="card__button">
		<?php _e('Become volunteer', 'tst');?>
	</div>
</a>
<?php
}



/** == Helpers == **/

/** Excerpt **/
function tst_get_post_excerpt($cpost, $l = 30, $force_l = false){

	if(is_int($cpost))
		$cpost = get_post($cpost);

	if($cpost->post_type == 'landing'){
		$e = get_post_meta($cpost, 'landing_excerpt', true);

	}
	elseif($cpost->post_type == 'project'){
		$e = get_post_meta($cpost, 'project_excerpt', true);

	}
	else {
		$e = $cpost->post_excerpt;
	}

	if(empty($e) && $cpost->post_type == 'landing'){
		$e = wp_trim_words(strip_shortcodes(get_post_meta($cpost, 'landing_content', true)));
	}

	$e = (!empty($e)) ? $e : wp_trim_words(strip_shortcodes($cpost->post_content), $l);

	if($force_l)
		$e = wp_trim_words($e, $l);

	return $e;
}

/** Meta **/
function tst_get_post_meta($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$meta = '';

	if($cpost->post_type == 'post'){
		$meta = get_the_date('d.m.Y', $cpost);
	}
	elseif($cpost->post_type == 'event') {
		$meta = tst_event_card_meta($cpost);
	}
	elseif($cpost->post_type == 'project') {
		$meta = get_post_meta($cpost->ID, 'subtitle_meta', true);
	}

	//page, landing, campaign???

	return $meta;
}


/* short title */
function tst_get_post_title_excerpt($cpost, $l = 30, $force_l = false){

	if(is_string($cpost))
		$cpost = get_post($cpost);
	$e = (!empty($cpost->post_title)) ? $cpost->post_title : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);

	return $e;
}

/* tags list */
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

/* icon for card */
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
	$meta = tst_get_post_meta($cpost);

	$s_meta = array();
	if(!empty($cats))
		$s_meta[] = $cats;

	if(!empty($meta))
		$s_meta[] = $meta;
?>
<a href="<?php echo $pl;?>" class="card-link">

	<div class="card__title">
		<h4><?php echo apply_filters('tst_the_title', tst_get_post_title_excerpt($cpost, 25, true));?></h4>
	</div>

	<div class="card__meta">
		<?php echo implode(', ', $s_meta); ?>
	</div>

	<div class="card__summary">
		<?php echo apply_filters('tst_the_content', tst_get_post_excerpt($cpost, 20)); ?>
	</div>

</a>
<?php
}

function tst_get_search_cats(WP_Post $cpost) {

	$terms = get_the_terms($cpost, 'section');
	$list = array();

	if(!empty($terms)){ foreach($terms as $t) {
		$list[] = "<a href='".get_term_link($t)."'>".apply_filters('tst_the_title', $t->name)."</a>";
	}} else {

		if($cpost->post_type == 'post'){
			$list[] = "<a href='".home_url('news')."'>".__('News', 'tst')."</a>";
		}
		elseif($cpost->post_type == 'event'){
			$list[] = "<a href='".home_url('item/dront-events/')."'>".__('Events', 'tst')."</a>";
		}

		//add further logic
	}

	$out = (!empty($list)) ? "<span class='category'>".implode(',', $list)."</span>" : '';
	return $out;
}


/** Events */
function tst_card_event(WP_Post $cpost, $args = array()) {

    $defaults = array(
        'thumb_position' 	=> 'left', //right
        'show_summary'		=> true,
        'show_meta' 		=> true,
    );

    $args = wp_parse_args($args, $defaults);
    $pl = get_permalink($cpost);

    $event = new TST_Event($cpost);
    $thumb = $event->post_thumbnail_card_markup();

    $figure_css = (false !== strpos($thumb, 'logo-frame')) ? 'card-event__thumbnail--logo' : 'card-event__thumbnail';
    $figure_css .= ' thumb-'.$args['thumb_position'];

    $thumb_markup = "<figure class='{$figure_css}'><a href='{$pl}' class='thumbnail-link'>";
    $thumb_markup .= $thumb;
    $thumb_markup .= "</a></figure>";

    $thumb_markup = "<div class='bit mf-12 sm-4'>{$thumb_markup}</div>";?>

    <article class="card-event <?php echo $event->is_expired() ? 'event-expired' : '';?>">
        <?php $event->schema_markup();?>
        <div class="frame">

            <?php if($args['thumb_position'] == 'left') { echo $thumb_markup; } ?>

            <div class="bit mf-12 sm-8">
                <h4 class="card-event__title">
                    <a href="<?php echo $pl;?>"><?php echo apply_filters('tst_the_title', get_the_title($cpost));?></a>
                </h4>

                <?php if($args['show_summary']) { ?>
                    <div class="card-event__summary"><?php echo tst_get_post_excerpt($cpost, 25, true);?></div>
                <?php } ?>

                <?php if($args['show_meta']) { ?>
                    <div class="card-event__meta"><?php echo tst_event_card_meta($cpost);?></div>
                <?php } ?>
            </div>

            <?php if($args['thumb_position'] == 'right') { echo $thumb_markup; } ?>
        </div>
    </article>
    <?php
}

function tst_event_card_meta(WP_Post $cpost) {

    $event = new TST_Event($cpost);
    $sep = tst_get_sep('&middot;');

    $meta = $event->get_regular_card_meta();

    return implode($sep, $meta);

}

function tst_event_card_date(WP_Post $cpost) {

    $event = new TST_Event($cpost);
    $sep = tst_get_sep('&middot;');

    return $event->get_start_date_mark();

}
