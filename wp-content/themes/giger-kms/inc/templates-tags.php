<?php
/**
 * General template tags
 **/


/** Custom conditions **/
function is_page_branch($pageID){
	global $post;

	if(empty($pageID))
		return false;

	if(!is_page() || is_front_page())
		return false;

	if(is_page($pageID))
		return true;

	if($post->post_parent == 0)
		return false;

	$parents = get_post_ancestors($post);

	if(is_string($pageID)){
		$test_id = get_page_by_path($pageID)->ID;
	}
	else {
		$test_id = (int)$pageID;
	}

	if(in_array($test_id, $parents))
		return true;

	return false;
}


function is_tax_branch($slug, $tax) {
	//global $post;

	$test = get_term_by('slug', $slug, $tax);
	if(empty($test))
		return false;

	if(is_tax($tax)){
		$qobj = get_queried_object();
		if($qobj->term_id == $test->term_id || $qobj->parent == $test->term_id)
			return true;
	}

	//if(is_singular() && is_object_in_term($post->ID, $tax, $test->term_id))
	//	return true;

	return false;
}



/** Logo & icons **/
function tst_site_logo($size = 'regular') {

	switch($size) {
		case 'regular':
			$file = 'pic-logo';
			break;
		case 'white':
			$file = 'pic-logo-w';
			break;
		default:
			$file = 'icon-logo';
			break;
	}

	$file = esc_attr($file);
?>
<svg class="logo <?php echo $file;?>">
	<use xlink:href="#<?php echo $file;?>" />
</svg>
<?php
}

function tst_svg_icon($id, $echo = true) {

	ob_start();
?>
<svg class="svg-icon <?php echo $id;?>">
	<use xlink:href="#<?php echo $id;?>" />
</svg>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	if($echo)
		echo $out;
	return $out;
}


/** Separator **/
function tst_get_sep($mark = '//') {

	return "<span class='sep'>".$mark."</span>";
}


/** Breadcrumbs **/
function tst_tag_breadcrubms() {

	$tag = get_queried_object();
	if(!$tag || $tag->taxonomy != 'post_tag')
		return '';

	$list = array();

	$list[] = "<a href='".home_url('/')."'>".__('Home', 'tst')."</a>";
	$list[] = "<a href='".home_url('news')."'>".__('News', 'tst')."</a>";

	$sep = tst_get_sep('&gt;');

	return "<div class='crumbs'>".implode($sep, $list)."</div>";
}

function tst_about_breadcrubms() {

	$page = get_queried_object();
	$about = get_page_by_path('about-us');

	if(!$page || !$about)
		return;

	$list = array();

	$list[] = "<a href='".home_url('/')."'>".__('Home', 'tst')."</a>";
	$list[] = "<a href='".get_permalink($about)."'>".apply_filters('tst_the_title', $about->post_title)."</a>";

	$sep = tst_get_sep('&gt;');

	return "<div class='crumbs'>".implode($sep, $list)."</div>";
}

/* heading css */
function tst_get_heading_style() {

	$i = rand(1, 5);
	$url = get_template_directory_uri()."/assets/img/bg-ex-".$i.".png";

	return "style='background-image: url(".$url.")'";
}




/* == Loop markups == **/
function tst_news_loop_page($posts) {

	if(empty($posts))
		return;

	$step_1 = array_slice($posts, 0, 9);
	$step_2 = array_slice($posts, 9);

	if(!empty($step_1)) {
		tst_news_loop_pattern($step_1);
	}

	if(!empty($step_2)) {
		tst_news_loop_pattern($step_2);
	}
}

function tst_news_loop_pattern($posts) {

	if(empty($posts))
		return;

	$count = count($posts);

	$row_1_data = array();
	$row_2_data = array();
	$row_3_data = array();
	$row_4_data = array();


	switch($count) {

		case 1:
			$row_4_data = $posts;
			break;

		case 2:
			$row_2_data = $posts;
			break;

		case 3:
			$row_1_data = $posts;
			break;

		case 4:
			$row_1_data = array_slice($posts, 0, 3);
			$row_4_data = array_slice($posts, 3);
			break;

		case 5:
			$row_1_data = array_slice($posts, 0, 3);
			$row_2_data = array_slice($posts, 3);
			break;

		case 6:
			$row_1_data = array_slice($posts, 0, 3);
			$row_3_data = array_slice($posts, 3);
			break;

		case 7:
			$row_1_data = array_slice($posts, 0, 3);
			$row_3_data = array_slice($posts, 3, 3);
			$row_4_data = array_slice($posts, 6);
			break;

		case 8:
			$row_1_data = array_slice($posts, 0, 3);
			$row_2_data = array_slice($posts, 3, 2);
			$row_3_data = array_slice($posts, 5);
			break;

		case 9:
			$row_1_data = array_slice($posts, 0, 3);
			$row_2_data = array_slice($posts, 3, 2);
			$row_3_data = array_slice($posts, 5, 3);
			$row_4_data = array_slice($posts, 8);
			break;
	}


	if(!empty($row_1_data)) {
?>
	<div class="tripleblock-picture loop-pattern-row-1">
		<div class="flex-grid--stacked scheme-color-1-ground scheme-color-2-leaf">
			<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
				<?php tst_card_linked($row_1_data[0], array('size' => 'block-2col')) ;?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_news_card($row_1_data[1], 'colored'); ?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--item">
				<?php tst_card_linked($row_1_data[2], array('size' => 'block-1col')); ?>
			</div>
		</div>
	</div>
<?php
	}

	if(!empty($row_2_data)) {
?>
	<div class="tripleblock-2cards loop-pattern-row-2">
		<div class="flex-grid--stacked row-reverse scheme-color-1-ground">
			<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
				<?php tst_card_linked($row_2_data[0], array('size' => 'block-2col')) ;?>
			</div>

			<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
				<div class="flex-column-centered"><?php tst_card_text($row_2_data[1]); ?></div>
			</div>
		</div>
	</div>
<?php
	}

	if(!empty($row_3_data)) {
?>
	<div class="tripleblock-2cardstext loop-pattern-row-3">
		<div class="flex-grid--stacked row-reverse scheme-color-1-ground scheme-color-2-loam">
			<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
				<div class="flex-column-centered"><?php tst_card_text($row_3_data[0]); ?></div>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_news_card($row_3_data[1], 'colored'); ?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_news_card($row_3_data[2], 'colored'); ?>
			</div>
		</div>
	</div>
<?php
	}

	if(!empty($row_4_data)) {
?>
	<div class="loop-pattern-row-4">
		<div class="flex-grid--stacked">
			<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
				<div class="flex-column-centered"><?php tst_card_text($row_4_data[0]); ?></div>
			</div>

			<?php tst_loop_injected_block();?>
		</div>
	</div>
<?php
	}
}


function tst_loop_injected_block(){

	$type = array('readmore', 'donate', 'volunteer', 'corporate');
	$key = array_rand($type);
	$type = $type[$key];

	if($type != 'readmore') {

		switch($type) {

			case 'donate':
				$support = get_page_by_path('donate', 'OBJECT', 'leyka_campaign');
				break;

			case 'volunteer':
				$support = get_page_by_path('volunteer');
				break;

			case 'corporate':
				$support = get_page_by_path('corporate');
				break;
		}
?>
	<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
		<?php tst_linked_help_card($support, 0, array('size' => 'block-2col')) ;?>
	</div>
<?php
	}
	else {
?>
	<div class="flex-cell--stacked sm-12 lg-6 card card--inject">
		add the read more block
	</div>
<?php
	}
}