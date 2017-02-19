<?php
/**
 * Micro elements
 **/

function tst_card_linked($cpost, $args = array()) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$defaults = array(
		'size' => 'block-2col'
	);

	$args = wp_parse_args($args, $defaults);
	$pl = get_permalink($cpost);

?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__thumbnail">
		<?php echo get_the_post_thumbnail($cpost, $args['size']); ?>
	</div>

	<div class="card__label">
		<h4><?php echo get_the_title($cpost);?></h4>
	</div>
</a>
<?php
}


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
		$summary = $cpost->post_excerpt;
	}

?>
<a href="<?php echo $pl;?>" class="card-link">
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

//tst_event_card_meta($cpost)
function tst_card_text($cpost) {

	if(is_int($cpost))
		$cpost = get_post($cpost);

	$args = array();

	$args['action_url'] = get_permalink($cpost);

	$args['action_text'] = get_post_meta($cpost->ID, 'action_text', true);
	//add to calendar for events
	if(empty($args['action_text']))
		$args['action_text'] = __('Details', 'tst');

	$args['title'] = get_the_title($cpost);

	$args['subtitle'] = ($cpost->post_type == 'event') ? tst_event_card_meta($cpost) : apply_filters('tst_the_title', get_post_meta($cpost->ID, 'subtitle_meta', true));

	$args['summary'] = (!empty($cpost->post_excerpt)) ? apply_filters('tst_the_content', $cpost->post_excerpt) : '';

	tst_card_text_markup($args);
}

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


/** by type **/
function tst_news_card($cpost, $mod = 'pictured') {

	if(is_int($cpost))
		$cpost = get_post($cpost);

?>
<a href="<?php echo $pl;?>" class="card-link">

	<?php if($mod == 'pictured' && has_post_thumbnail($cpost)) { ?>
		<div class="card__thumbnail">
			<?php echo get_the_post_thumbnail($cpost, "block-small"); ?>
		</div>
	<?php } ?>

	<div class="card__title">
		<h4><?php echo apply_filters('tst_the_title', get_the_title($cpost));?></h4>
	</div>

	<div class="card__summary">
		<?php echo apply_filters('tst_the_content', tst_get_post_excerpt($cpost, 20)); ?>
	</div>

	<div class="card__meta">
		<?php echo get_the_date('d.m.Y', $cpost); ?>
	</div>

</a>
<?php
}


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




 /* Old */
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
/** short title **/
function tst_get_post_title_excerpt($cpost, $l = 30, $force_l = false){

	if(is_string($cpost))
		$cpost = get_post($cpost);
	$e = (!empty($cpost->post_title)) ? $cpost->post_title : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
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
// var_dump($tags);
?>
<article class="cell">
	<h4 class="card-search__title cell__title">
		<a href="<?php echo $pl;?>"><?php echo tst_get_post_title_excerpt($cpost, 30, true);?></a>
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
		$list[] = "<a href='".get_term_link($t)."'>".apply_filters('tst_the_title', $t->name)."</a>";
	}}
	elseif($cpost->post_type == 'landing') {
		$list[] = "<a href='".home_url('landing')."'>Лендинги</a>";
	}
	elseif($cpost->post_type == 'project') {
		$list[] = "<a href='".home_url('project')."'>Проекты</a>";
	}
	elseif($cpost->post_type == 'event') {
		$list[] = "<a href='".home_url('event')."'>Анонсы</a>";
	}
	elseif($cpost->post_type == 'person') {
		$list[] = "<a href='".home_url('person')."'>Люди</a>";
	}
	elseif($cpost->post_type == 'archive_page') {
		$list[] = "<a href='".home_url('person')."'>Архив</a>";
	}
	elseif($cpost->post_type == 'marker') {
		$list[] = "<a href='".home_url('person')."'>Маркеры</a>";
	} else {
		$list[] = "<a href='".home_url('news')."'>Новости</a>";
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


function tst_event_thumbnail_img($post_id, $size = 'post-thumbnail') {

    $event = new TST_Event($post_id);
    $thumb = $event->post_thumbnail($size);

    return $thumb;
}

/** Nearest events block **/
//function tst_nearest_events_posts($num = 5, $query_args = array()) {
//    //when $query_args empty just get alll neares events
//    $today_stamp = strtotime('today midnight');
//
//    // pre query 1
//    $qv = array(
//        'post_type' => 'event',
//        'fields' => 'ids',
//        'posts_per_page' => TST_EVENTS_SHORT_LIST_LIMIT,
//        'orderby'  => array('date' => 'DESC'),
//    );
//    $qv = array_merge($qv, $query_args);
//    $tmp_posts = get_posts($qv);
//    $posts_id = array_values( $tmp_posts );
//
//    if( !count( $posts_id ) ) {
//        return array();
//    }
//
//    // pre query 2
//    $qv = array(
//        'post_type' => 'event',
//        'fields' => 'ids',
//        'include' => $posts_id,
//        'meta_query' => array(
//            array(
//                'key' => 'event_date_end',
//                'value' => $today_stamp,
//                'compare' => '>=',
//                'type' => 'numeric'
//            )
//        ),
//        'cache_results'  => false,
//        'update_post_meta_cache' => false,
//        'update_post_term_cache' => false,
//        'no_found_rows' => true
//    );
//    $tmp_posts = get_posts($qv);
//    $posts_id = array_values( $tmp_posts );
//
//    if( !count( $posts_id ) ) {
//        return array();
//    }
//
//    // main query
//    $qv = array(
//        'post_type' => 'event',
//        'posts_per_page' => $num,
//        'orderby'  => array('menu_order' => 'DESC', 'meta_value' => 'ASC'),
//        'meta_key' => 'event_date_start',
//
//        'numberposts' => $num, // IMPORTANT!!! DO NOT USE posts_per_page HERE!!!
//        'query_id' => 'nearest_events_posts_query',
//        'include' => $posts_id,
//
//        'cache_results'  => false,
//        'update_post_meta_cache' => false,
//        'update_post_term_cache' => false,
//        'no_found_rows' => true
//    );
//
//    $posts = get_posts($qv);
//
//    return $posts;
//}
//
// function tst_nearest_events_markup($num = 5, $last_border = true, $query_args = array()) {

    /*$posts = tst_nearest_events_posts($num, $query_args);

    if(empty($posts))
        return;


    ob_start();
    ?>
    <ul class="nearest-events">
        <?php
        foreach($posts as $i => $p) {
            $num = $i+1;

            $event = new TST_Event($p);
            $thumb = $event->post_thumbnail_widget_markup();
            ?>
            <li class="nearest-events__item<?php if(!$last_border && $num == count($posts)) { echo ' nearest-events__item--no-border'; } ?>">
                <?php $event->schema_markup();?>
                <a href="<?php echo get_permalink($p);?>" class="nearest-events__link">
                    <div class="nearest-events__thumb"><?php echo $thumb;?></div>
                    <div class="nearest-events__title"><?php echo get_the_title($p);?></div>
                    <div class="nearest-events__meta"><?php echo tst_cell_post_meta($p);?></div>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
    $out = ob_get_contents();
    ob_end_clean();

    return $out;*/
// }