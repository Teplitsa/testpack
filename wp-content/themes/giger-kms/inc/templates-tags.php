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
		case 'small':
			$file = 'pic-logo-small';
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

/* heading css */
function tst_get_heading_style() {
	
	$i = rand(1, 5);
	$url = get_template_directory_uri()."/assets/img/bg-ex-".$i.".png";
	
	return "style='background-image: url(".$url.")'";
}