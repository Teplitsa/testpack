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


	$url = get_template_directory_uri().'/assets/img/';
?>
<img src="<?php echo $url;?>logo.svg" onerror="this.src='<?php echo $url;?>logo.png'">
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

