<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
 */




/* Custom conditions */
function is_about(){
	global $post;
		
	if(is_page_branch(2))
		return true;
	
	if(is_post_type_archive('org'))
		return true;
	
	if(is_post_type_archive('org'))
		return true;
	
	return false;
}

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


function is_posts() {
	
	if(is_home() || is_category())
		return true;	
	
	if(is_tax('auctor'))
		return true;
	
	if(is_singular('post'))
		return true;
	
	return false;
}


function is_projects() {	
		
	if(is_page('programms'))
		return true;
		
	if(is_singular('programm'))
		return true;
		
	return false;
}


/** Menu filter sceleton **/
//add_filter('wp_nav_menu_objects', 'kds_custom_menu_items', 2, 2);
function kds_custom_menu_items($items, $args){			
	
	if(empty($items))
		return;	
	
	//var_dump($args);
	if($args->theme_location =='primary'){
		
		foreach($items as $index => $menu_item){
			if(in_array('current-menu-item', $menu_item->classes))
				$items[$index]->classes[] = 'active';
		}
	}
	
	return $items;
}
 
/** HTML with meta information for the current post-date/time and author **/
function kds_posted_on(WP_Post $cpost) {
	
	$meta = array();
	$sep = '';
	
	if('post' == $cpost->post_type){		
		
		$meta[] = "<span class='date'>".get_the_date('d.m.Y', $cpost)."</span>";
		
		$cat = get_the_term_list($cpost->ID, 'category', '<span class="category">', ', ', '</span>');
		$meta[] = (!is_singular()) ? strip_tags($cat, '<span>') : $cat;
		$meta = array_filter($meta);
		
		$sep = kds_get_sep('&middot;');		
	}
	
		
	return implode($sep, $meta);		
}


/** Logo **/
function kds_site_logo($size = 'regular') {

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

function kds_svg_icon($id, $echo = true) {
	
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
function kds_get_sep($mark = '//') {
	
	return "<span class='sep'>".$mark."</span>";
}

/** == Titles == **/
/** CPT archive title **/
function kds_get_post_type_archive_title($post_type) {
	
	$pt_obj = get_post_type_object( $post_type );	
	$name = $pt_obj->labels->menu_name;
	
	
	return $name;
}

function kds_section_title() {
	
	$title = '';
	$css = '';
	
	if(is_category() || is_tag()){
		$title = single_term_title('', false);
		$css = 'archive';
	}
	elseif(is_home()){
		$p = get_post(get_option('page_for_posts'));
		$title = get_the_title($p);
		$css = 'archive';
	}
	elseif(is_search()){
		$title = __('Search results', 'kds');
		$css = 'archive search';
	}
	elseif(is_404()){
		$title = __('404: Page not found', 'kds');
		$css = 'archive e404';
	}
	
	echo "<h1 class='section-title {$css}'>{$title}</h1>";	
}


/** == NAVs == **/
function kds_paging_nav(WP_Query $query = null) {

	if( !$query ) {

		global $wp_query;
		$query = $wp_query;
	}

	if($query->max_num_pages < 2) { // Don't print empty markup if there's only one page
		return;
	}

	$p = kds_paginate_links($query, false);
	if($p) {
?>
	<nav class="navigation paging-navigation" role="navigation"><?php echo $p; ?></nav>
<?php
	}
}


function kds_paginate_links(WP_Query $query = null, $echo = true) {

	if( !$query ) {

		global $wp_query;
		$query = $wp_query;
	}
	
	$current = ($query->query_vars['paged'] > 1) ? $query->query_vars['paged'] : 1; 

	$parts = parse_url(get_pagenum_link(1));

	$pagination = array(
        'base' => trailingslashit(esc_url($parts['host'].$parts['path'])).'%_%',
        'format' => 'page/%#%/',
        'total' => $query->max_num_pages,
        'current' => $current,
		'prev_next' => true,
        'prev_text' => '&lt;',
        'next_text' => '&gt;',
        'end_size' => 4,
        'mid_size' => 4,
        'show_all' => false,
        'type' => 'plain', //list
		'add_args' => array()
    );

    if( !empty($query->query_vars['s']) ) {
        $pagination['add_args'] = array('s' => str_replace(' ', '+', get_search_query()));
	}

	foreach(array('s') as $param) { // Params to remove

		if($param == 's') {
			continue;
		}

		if(isset($_GET[$param]) && !empty($_GET[$param])) {
			$pagination['add_args'] = array_merge($pagination['add_args'], array($param => esc_attr(trim($_GET[$param]))));
		}
	}
		
		    
    if($echo) {

		echo paginate_links($pagination);
		return '';
	} else {
		return paginate_links($pagination);
	}
}


/** next/previous post when applicabl */
function kds_post_nav() {

	$previous = is_attachment() ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);

	if( !get_adjacent_post(false, '', false) && !$previous) { // Don't print empty markup if there's nowhere to navigate
		return;
	}?>

	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e('Post navigation', 'kds'); ?></h1>
		<div class="nav-links">
			<?php previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">&larr;</span>');
			next_post_link('<div class="nav-next">%link</div>', '<span class="meta-nav">&rarr;</span>');?>
		</div>
	</nav>
	<?php
}


/** Breadcrumbs  **/
function kds_breadcrumbs(WP_Post $cpost){
			
	$links = array();
	if(is_singular('post')) {
		$links[] = "<a href='".home_url()."' class='crumb-link'>".__('Homepage', 'kds')."</a>";
				
		$p = get_post(get_option('page_for_posts'));
		if($p){
			$links[] = "<a href='".get_permalink($p)."' class='crumb-link'>".get_the_title($p)."</a>";
		}
				
	}
	elseif(is_singular('programm')) {
		
		$links[] = "<a href='".home_url()."' class='crumb-link'>".__('Homepage', 'kds')."</a>";
				
		$p = get_page_by_path('programms');
		if($p){
			$links[] = "<a href='".get_permalink($p)."' class='crumb-link'>".get_the_title($p)."</a>";
		}

	}
	
	
	$sep = kds_get_sep('&gt;');
	
	return "<div class='crumbs'>".implode($sep, $links)."</div>";	
}


/** == Newsletter == */
function kds_get_newsletter_form(){
	
	$form_id = get_theme_mod('newsletter_form_id', 0);
	if($form_id && class_exists('FrmFormsController'))

	return FrmFormsController::get_form_shortcode(array('id' => $form_id, 'title' => false, 'description' => false));
}



/** == Posts elements == **/
function kds_post_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('kds_the_title', kds_get_post_excerpt($cpost, 25, true));
?>
<article class="tpl-post">
	<div class="frame">
		<div class="bit md-4 sm-6">
			<a href="<?php echo $pl; ?>" class="thumbnail-link"><?php echo kds_post_thumbnail($cpost->ID, 'post-thumbnail');?></a>
		</div>
		<div class="bit md-8 sm-6"><a href="<?php echo $pl; ?>">
			<div class="entry-meta"><?php echo kds_posted_on($cpost);?></div>
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</a></div>
	</div>
</article>
<?php
}

function kds_featured_post_card(WP_Post $cpost){
	
	$thumbnail = kds_post_thumbnail_src($cpost->ID, 'full');
	$pl = get_permalink($cpost);
	$ex = apply_filters('kds_the_title', kds_get_post_excerpt($cpost, 40, true));
?>
<article class="tpl-featured">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	<a href="<?php echo $pl; ?>">
	<div class="container for-message">
		<div class="featured-content">
			<div class="entry-meta"><?php echo kds_posted_on($cpost);?></div>
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</div>
	</div>	
</a></article>
<?php
}

function kds_programm_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('kds_the_title', kds_get_post_excerpt($cpost, 25, true));
?>
<article class="tpl-programm"><a href="<?php echo $pl; ?>" class="entry-link">	
	<?php echo kds_post_thumbnail($cpost->ID, 'post-thumbnail');?>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-summary"><?php echo $ex;?></div>
</a></article>
<?php
}

/** Excerpt  **/
function kds_get_post_excerpt($cpost, $l = 30, $force_l = false){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);	
	
	return $e;
}


/** Deafult thumbnail for posts **/
function kds_get_default_post_thumbnail($type = 'default_thumbnail', $size){
		
	$default_thumb_id = attachment_url_to_postid(get_theme_mod($type));
	$img = '';
	if($default_thumb_id){
		$img = wp_get_attachment_image($default_thumb_id, $size);	
	}
	
	return $img;
}

function kds_post_thumbnail($post_id, $size = 'post-thumbnail'){
	
	$thumb = get_the_post_thumbnail($post_id, $size);
	
	if(!$thumb){
		$thumb = kds_get_default_post_thumbnail('default_thumbnail', $size);
	}
			
	return $thumb;
}

function kds_post_thumbnail_src($post_id, $size = 'post-thumbnail'){
	
	$src = get_the_post_thumbnail_url($post_id, $size);
	if(!$src){
		$default_thumb_id = attachment_url_to_postid(get_theme_mod('default_thumbnail'));
		if($default_thumb_id){
			$src = get_the_post_thumbnail_url($default_thumb_id, $size);
		}
	}
	
	return $src;
}

/** More section **/
function kds_more_section($posts, $title = '', $type = 'news'){
		
	$all_link = '';
	
	if($type == 'programms'){
		$all_link = "<a href='".home_url('programms')."'>".__('All programms', 'kds')."</a>";
		$title = (empty($title)) ? __('Our programms', 'kds') : $title;
	}
	else {
		$all_link = "<a href='".home_url('news')."'>".__('All news', 'kds')."</a>";
		$title = (empty($title)) ? __('Latest news', 'kds') : $title;
	}

?>
	<div class="related-section">
		<h3 class="ms-title"><?php echo $title;?> <?php echo $all_link;?></h3>
		<div class="frame">
		<?php foreach($posts as $p) { ?>
			<div class="bit sm-6 md-3"><?php kds_related_post_card($p);?></div>
		<?php } ?>
		</div>
	</div>
<?php
}

function kds_related_post_card(WP_Post $cpost) {
	
	$pl = get_permalink($cpost);	
?>
<article class="tpl-related-post <?php echo $cpost->post_type;?>">
	<a href="<?php echo $pl; ?>" class="thumbnail-link">
		<?php echo kds_post_thumbnail($cpost->ID, 'post-thumbnail');?>
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	</a>
</article>
<?php
}


/** Related project on single page **/
function kds_related_project(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('kds_the_title', kds_get_post_excerpt($cpost, 25, true));
?>
<div class="related-widget widget">
	<h3 class="widget-title"><?php _e('Related project', 'kds');?></h3>
	<a href="<?php echo $pl;?>" class="entry-link">
		<div class="rw-preview">
			<?php echo kds_post_thumbnail($cpost->ID, 'post-thumbnail');?>
		</div>
		<div class="rw-content">
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</div>
	</a>
	<div class="help-cta">
		<?php echo kds_get_help_now_cta();?>
	</div>
</div>
<?php	
}

function kds_get_help_now_cta($cpost = null, $label = ''){
	
	$label = (empty($label)) ? __('Help now', 'kds') : $label;
	$cta = '';
	
	if(!$cpost){
		
		$help_id = get_theme_mod('help_campaign_id');
		if(!$help_id)
			return '';
		
		$cta = "<a href='".get_permalink($help_id)."' class='help-button'>{$label}</a>";
	}
	else {
		$url = get_post_meta($cpost->ID, 'cta_link', true);
		$txt = get_post_meta($cpost->ID, 'cta_text', true);
		
		if(empty($url))
			return '';
		
		if(empty($txt))
			$txt = $label;
		
		$css = (false !== strpos($url, '#')) ? 'help-button local-scroll' : 'help-button'; 
		$cta = "<a href='{$url}' class='{$css}'>{$txt}</a>";
	}
	
	return $cta;
}


/** == People fuctions == **/
function kds_people_gallery($type = 'all'){
	
	$args = array(
		'post_type'=> 'person',
		'posts_per_page' => -1
	);
	
	if($type != 'all'){
		$args['tax_query'] = array(
			array(
				'taxonomy'=> 'person_cat',
				'field'   => 'slug',
				'terms'   => $type  
			)
		);
	}
	
	$query = new WP_Query($args);
	if(!$query->have_posts())
		return '';
	
?>
	<div class="people-gallery eqh-container frame">
	<?php foreach($query->posts as $p){ ?>
		<div class="bit md-6 eqh-el"><?php kds_person_card($p);?></div>
	<?php }	?>
	</div>
<?php
}

function kds_person_card(WP_Post $cpost){
		
?>
<article class="tpl-person">
	
	<div class="avatar"><?php echo kds_post_thumbnail($cpost->ID, 'thumbnail');?></div>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-meta"><?php echo apply_filters('kds_the_title', $cpost->post_excerpt);?></div>
	<div class="entry-summary"><?php echo apply_filters('kds_the_content', $cpost->post_content);?></div>		
	
</article>
<?php
}

/** == Orgs functions == **/
function kds_orgs_gallery($type = 'all') {
	
$args = array(
		'post_type'=> 'org',
		'posts_per_page' => -1
	);
	
	if($type != 'all'){
		$args['tax_query'] = array(
			array(
				'taxonomy'=> 'org_cat',
				'field'   => 'slug',
				'terms'   => $type  
			)
		);
	}
	
	$query = new WP_Query($args);
	if(!$query->have_posts())
		return '';
	
?>
	<div class="orgs-gallery  frame">
	<?php foreach($query->posts as $p){ ?>
		<div class="bit mf-6 sm-4 md-3 "><?php kds_org_card($p);?></div>
	<?php }	?>
	</div>
<?php	
}

function kds_org_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="tpl-org logo">
	<a href="<?php echo $pl;?>" class="logo-link logo-frame" target="_blank" title="<?php echo esc_attr($cpost->post_title);?>">
		<span><?php echo get_the_post_thumbnail($cpost->ID, 'full'); ?></span>
	</a>
</article>
<?php
}