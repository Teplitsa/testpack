<?php
/**
 * Micro elements
 **/

function tst_cell(WP_Post $cpost, $show_thumb = true) {

	$pl = get_permalink($cpost);
	$tags = tst_get_tags_list($cpost);
	$ex = tst_get_post_excerpt($cpost, 25);

	//thumb
	$thumb_mark = '';
	if($show_thumb && has_post_thumbnail($cpost)) {
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
	}//has thumb
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
		<?php if(!empty($thumb_mark)) { ?>
			<div class="cell__thumb"><?php echo $thumb_mark;?></div>
		<?php }?>
	</article>
<?php
}

function tst_cell_story(WP_Post $cpost, $show_thumb = true) {

    $pl = get_permalink($cpost);
    $tags = tst_get_tags_list($cpost);
    $ex = tst_get_post_excerpt($cpost, 25);

?>
	<article class="cell cell-inlist-story">
		<div  class="cell-ex">
			<a href="<?php echo $pl;?>"><?php echo apply_filters('tst_the_title', $ex);?></a>
		</div>
		<div class="cell__text">
			<p><?php echo get_the_title($cpost);?></p>
			<p><?php echo $tags;?></p>
		</div>
	</article>
<?php
}

function tst_project_cell(WP_Post $cpost) {

	$ex = tst_get_post_excerpt($cpost, 25);

?>
	<article class="cell cell--project " id="<?php echo $cpost->ID; ?>">
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
	<div class="card__link_content"><?php echo $thumb_mark;?>
	<h4 class="card__title"><?php echo get_the_title($cpost);?></h4></div>
</a></article>
<?php
}

function tst_card_team_member(array $member) {

    // thumb
    $thumb_mark = '';
    if($member['image_id']) {

        ob_start();?>

        <div class="member-pic">
            <?php $base = wp_get_attachment_image_src($member['image_id'], 'thumbnail-small-fixed');?>
            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $base[0];?>" class="lazyload">
        </div>

        <?php $thumb_mark = ob_get_contents();
        ob_end_clean();
    } // has thumb ?>

    <div class="team-member">
        <?php if($thumb_mark) {?>
            <?php echo $thumb_mark;?>
        <?php }?>
        <div class="member-name"><?php echo esc_attr($member['name']);?></div>
        <div class="member-position"><?php echo esc_attr($member['position']);?></div>

    </div>
<?php }

function tst_card_story(WP_Post $cpost, $show_icon = true) {

    $pl = get_permalink($cpost);
    $ex = tst_get_post_excerpt($cpost, 15);

    ?>
<article class="card has-thumb"><a href="<?php echo $pl;?>" class="card__link">
	<div class="card__link_content"><div class="card__excerpt"><?php echo $ex?></div>
	<h4 class="card__title"><?php echo get_the_title($cpost);?></h4></div>
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


/* story card */
function tst_story_card(WP_Post $cpost) {

    $pl = get_permalink($cpost);
    $ex = tst_get_post_excerpt($cpost, 25);
    $author_name = get_post_meta( $cpost->ID, 'story_author_name', true );
    $author_age = trim( get_post_meta( $cpost->ID, 'story_author_age', true ) );
    $author_gender = get_post_meta( $cpost->ID, 'story_author_gender', true );

    $author = array( $author_name );
    if( trim( $author_age ) ) {
        $author[] = $author_age;
    }

    $author_text = implode( ', ', $author );

    $icon = TST_Stories::get_story_unique_icon( $cpost->ID, $author_gender, 3 );
?>
	<article class="cell">
		<a href="<?php echo $pl?>" class="cell-story">
    		<span href="<?php echo $pl?>" class="story-author">
    			<span class="story-author-ava"><?php tst_svg_icon( $icon );?></span>
    			<span class="story-author-name"><?php echo $author_text;?></span>
    		</span>
			<span class="story-ex"><?php echo apply_filters('tst_the_title', $ex);?></span>
		</a>
	</article>
<?php
}

/* book item */
function tst_book_item( WP_Post $cpost, $show_thumb = true ) {

	$pl = get_permalink($cpost);
	$tags = tst_get_tags_list($cpost);
	$ex = tst_get_post_excerpt($cpost, 25);
	$book_att_id = get_post_meta( $cpost->ID, 'book_att_id', true );
	$book_download_url = $book_att_id ? wp_get_attachment_url( $book_att_id ) : "";

	//thumb
	$thumb_mark = '';
	if($show_thumb) {

	    if( has_post_thumbnail($cpost) ) {

	        $cap = tst_get_post_thumbnail_cation($cpost);

	        $thumb_args = array(
	            'placement_type'	=> 'small-medium-medium-medium-medium',
	            'aspect_ratio' 		=> 'cover',
	            'crop' 				=> 'fixed'
	        );

	        $thumb = tst_get_post_thumbnail_picture($cpost, $thumb_args);

	    }
	    else {

	        $cap = '';
	        $book_icon_src = get_template_directory_uri() . '/assets/img/book-cover.png';
	        ob_start();
	        ?>
	        <div class="tst-thumbnail tst-book-no-cover">
	        	<div class="tst-thumbnail__frame">
					<i class="material-icons">import_contacts</i>
        		</div>
        	</div>
	        <?php
	        $thumb = ob_get_clean();
	    }

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
	}//has thumb
?>
	<article class="cell">
		<div class="frame">
			<div class="bit mf-4">
    		<?php if(!empty($thumb_mark)) { ?>
    			<div class="cell__cover"><?php echo $thumb_mark;?></div>
    		<?php }?>
			</div>

			<div class="bit mf-8">
        		<h4 class="cell__title">
        			<a href="<?php echo $pl;?>"><?php echo get_the_title($cpost);?></a>
        		</h4>
        		<div class="cell__text">
        			<p><?php echo apply_filters('tst_the_title', $ex);?></p>
        			<p><?php echo $tags;?></p>
        			<?php if( $book_download_url ): ?>
        				<p><a class="book-download-link" href="<?php echo $book_download_url?>"><i class="material-icons">file_download</i> <?php _e( 'Download book', 'tst' ) ?></a></p>
        			<?php endif ?>
        		</div>
			</div>
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