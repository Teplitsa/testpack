<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
 */


function tst_has_authors(){
		
	if(defined('TST_HAS_AUTHORS') && TST_HAS_AUTHORS && function_exists('get_term_meta'))
		return true;
	
	return false;
}


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
//add_filter('wp_nav_menu_objects', 'tst_custom_menu_items', 2, 2);
function tst_custom_menu_items($items, $args){			
	
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
function tst_posted_on(WP_Post $cpost) {
	
	$meta = array();
	$sep = '';
	$cat = '';
	
	if('post' == $cpost->post_type){		
		
		$meta[] = "<span class='date'>".get_the_date('d.m.Y', $cpost)."</span>";
		$cat = tst_get_post_source_link($cpost->ID);
		
		if(!empty($cat)) {
			$cat = "источник: ".$cat;
		}
		else {
			$cat = get_the_term_list($cpost->ID, 'category', '<span class="category">', ', ', '</span>');
		}
		
		$meta[] = $cat;
		$meta = array_filter($meta);
		
		$sep = tst_get_sep(',');		
	}	
	elseif('person' == $cpost->post_type) {
		
		$cat = get_the_term_list($cpost->ID, 'person_cat', '<span class="category">', ', ', '</span>');
		if(!empty($cat)) {
			$meta[] = $cat;
		}
	}
	elseif('page' == $cpost->post_type && is_search()) {
		
		$meta[] = "<span class='category'>".__('Page', 'tst')."</span>";
		
	}
		
	return implode($sep, $meta);		
}

function tst_posted_on_single(WP_Post $cpost) {
	
	$meta = array();
	$sep = '';
	$cat = '';
	
	if('post' == $cpost->post_type){		
		
		$meta[] = "<span class='date'>".get_the_date('d.m.Y', $cpost)."</span>";
		$cat = get_the_term_list($cpost->ID, 'category', '<span class="category">', ', ', '</span>');
				
		$meta[] = $cat;
		$meta = array_filter($meta);
		
		$sep = tst_get_sep('&middot;');		
	}
	
	return implode($sep, $meta);		
}

function tst_get_post_source_link($post_id) {
	
	$source_link = get_post_meta($post_id, 'post_source_url', true);
	$source_name = get_post_meta($post_id, 'post_source_name', true);
	
	if(empty($source_name) || empty($source_link))
		return '';
	
	$source_link = esc_url($source_link);
	$source_name = apply_filters('tst_the_title', $source_name);
	$target = (false === strpos($source_link, home_url())) ? " target='_blank'" : '';
	
	return "<a href='{$source_link}'{$target} class='post-source-link'>{$source_name}</a>";
}


/** Logo **/
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

/** == Titles == **/
/** CPT archive title **/
function tst_get_post_type_archive_title($post_type) {
	
	$pt_obj = get_post_type_object( $post_type );	
	$name = $pt_obj->labels->menu_name;
	
	
	return $name;
}

function tst_section_title() {
	
	$title = '';
	$css = '';
	
	if(is_category()){
		
		$p = get_post(get_option('page_for_posts'));
		$title = get_the_title($p);
		$title .= tst_get_sep('&mdash;');
		$title .= single_term_title('', false);
		$css = 'archive';
	}
	elseif(is_tag() || is_tax()){
		$title = single_term_title('', false);
		$css = 'archive';
	}
	elseif(is_home()){
		$p = get_post(get_option('page_for_posts'));
		$title = get_the_title($p);
		$css = 'archive';
	}
	elseif(is_post_type_archive('leyka_donation')){		
		$title = __('Donations history', 'tst');
		$css = 'archive';
	}
	elseif(is_search()){
		$title = __('Search results', 'tst');
		$css = 'archive search';
	}
	elseif(is_404()){
		$title = __('404: Page not found', 'tst');
		$css = 'archive e404';
	}
	
	echo "<h1 class='section-title {$css}'>{$title}</h1>";	
}


/** == NAVs == **/
function tst_paging_nav(WP_Query $query = null) {

	if( !$query ) {

		global $wp_query;
		$query = $wp_query;
	}

	if($query->max_num_pages < 2) { // Don't print empty markup if there's only one page
		return;
	}

	$p = tst_paginate_links($query, false);
	if($p) {
?>
	<nav class="paging-navigation" role="navigation"><?php echo $p; ?></nav>
<?php
	}
}


function tst_paginate_links(WP_Query $query = null, $echo = true) {

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
function tst_post_nav() {

	$previous = is_attachment() ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);

	if( !get_adjacent_post(false, '', false) && !$previous) { // Don't print empty markup if there's nowhere to navigate
		return;
	}?>

	<nav class="navigation post-navigation" role="navigation">		
		<div class="nav-links">
			<?php previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">&larr; Пред.</span>');
			next_post_link('<div class="nav-next">%link</div>', '<span class="meta-nav">След. &rarr;</span>');?>
		</div>
	</nav>
	<?php
}


/** Breadcrumbs  **/
function tst_breadcrumbs(WP_Post $cpost){
			
	$links = array();
	if(is_singular('post')) {
						
		$p = get_post(get_option('page_for_posts'));
		if($p){
			$links[] = "<a href='".get_permalink($p)."' class='crumb-link'>".get_the_title($p)."</a>";
		}
				
	}
	
	
	$sep = tst_get_sep('&middot;');
	
	return "<div class='crumbs'>".implode($sep, $links)."</div>";	
}


/** post format **/
function tst_get_post_format($cpost){
	
	$format = get_post_meta($cpost->ID, 'post_format', true);
	if(empty($format))
		$format = 'standard';
		
	return $format;
}



/** More section **/
function tst_more_section($posts, $title = '', $type = 'news', $css= ''){
	
	if(empty($posts))
		return;
	
	$all_link = '';
	$container_type = 'container';
	
	if($type == 'projects'){
		$all_link = "<a href='".home_url('activity')."'>".__('More projects', 'tst')."&nbsp;&rarr;</a>";
		$title = (empty($title)) ? __('Our projects', 'tst') : $title;
	}	
	else {
		$all_link = "<a href='".home_url('news')."'>Все новости&nbsp;&rarr;</a>";
		$title = (empty($title)) ? 'Новости по теме' : $title;
		$container_type = 'container-narrow';
	}

	$css .= ' related-card-holder';
?>
<section class="<?php echo esc_attr($css);?>"><div class="<?php echo $container_type;?>">
<h3 class="related-title"><?php echo $title; ?></h3>

<div class="related-cards-loop">
	<?php
		foreach($posts as $p){			
			tst_related_post_card($p);
		}		
	?>
</div>

<div class="related-all-link"><?php echo $all_link;?></div>
</div></section>
<?php
}





/** == People fuctions == **/
function tst_people_gallery($type = 'all'){
	
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
		<div class="bit md-6 eqh-el"><?php tst_person_card($p);?></div>
	<?php }	?>
	</div>
<?php
}



/** == Orgs functions == **/
function tst_orgs_gallery($type = 'all') {
	
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
		<div class="bit mf-6 sm-4 md-3 "><?php tst_org_card($p);?></div>
	<?php }	?>
	</div>
<?php	
}


/** == Children profile related logic == **/
add_action('save_post_leyka_campaign', 'tst_donations_actions', 2, 3);
function tst_donations_actions($post_ID, $post, $update){
	
	if(!class_exists('Leyka_Campaign'))
		return;
	
	$camp = new Leyka_Campaign($post);
	
	if($camp->is_closed && has_term('need-help', 'campaign_cat', $post)) {
		$category = get_term_by('slug', 'you-helped', 'campaign_cat');
		if($category)
			wp_set_post_terms($post->ID, $category->term_id, $category->taxonomy);
	}
	
	if(!$camp->is_closed && has_term('you-helped', 'campaign_cat', $post)) {
		$category = get_term_by('slug', 'need-help', 'campaign_cat');
		if($category)
			wp_set_post_terms($post->ID, $category->term_id, $category->taxonomy);
	}	
}

function tst_is_children_campaign($post_id){
	
	if(has_term(array('you-helped', 'need-help', 'rosemary', 'children'), 'campaign_cat', $post_id))
		return true;
	
	return false;
}

function tst_connected_project_meta($cpost){
		
	//WP_Query doesn't ork for any reason
	$connected = p2p_get_connections('children-projects', array('direction' => 'any', 'to' => $cpost->ID));
	if(!empty($connected) && isset($connected[0]->p2p_from)){
		$ccpost = new Leyka_Campaign((int)$connected[0]->p2p_from);
		$label = ($ccpost->is_closed) ? 'Сбор средств осуществлялся в рамках программы/проекта' : 'Сбор средств осуществляется в рамках программы/проекта';
	?>
		<div class="child-project"><span class="label"><?php echo $label;?>:</span> <a href="<?php echo get_permalink($ccpost->ID);?>"><?php echo apply_filters('tst_the_title', $ccpost->title);?></a></div>
	<?php
	}
}


/** Related project on single page **/
function tst_related_project(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('tst_the_title', tst_get_post_excerpt($cpost, 25, true));
?>
<div class="related-widget widget">
	<h3 class="widget-title"><?php _e('Related project', 'kds');?></h3>
	<a href="<?php echo $pl;?>" class="entry-link">
		<div class="rw-preview">
			<?php echo tst_post_thumbnail($cpost->ID, 'post-thumbnail');?>
		</div>
		<div class="rw-content">
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</div>
	</a>
	<div class="help-cta">
		<?php echo tst_get_help_now_cta();?>
	</div>
</div>
<?php	
}

function tst_get_help_now_cta($cpost = null, $label = ''){
	
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