<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
 */

/* CPT Filters */
add_action('init', 'tst_custom_query_vars');
function tst_custom_query_vars(){
	global $wp;
	
	//additional data in query
	$wp->add_query_var('fetch_thumbnails');
	$wp->add_query_var('query_thumbnails');
}

add_action('parse_query', 'tst_request_corrected');
function tst_request_corrected($query) {
	
	if(is_admin())
		return;
	
	if(!$query->is_main_query())
		return;
	
	
} 

//add_filter('the_posts', 'tst_fetch_posts_data', 2,2);
function tst_fetch_posts_data($posts, $query) {
	
	if(is_admin())
		return $posts;
	
	if(!$query->get('fetch_thumbnails') || empty($posts))
		return $posts;
	
	
	$thumb_ids = array();
	foreach($posts as $i => $p){		
		$id = (int)get_post_meta($p->ID, '_thumbnail_id', true );
		if($id){
			$posts[$i]->_thumbnail_id = $id;
			$thumb_ids[] = $id;
		}		
	}
	
	
	if(!empty($thumb_ids)) {	
		$thumbs_obj = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'post__in' => $thumb_ids));
				
		foreach($thumbs_obj as $th) {
			$thumbs[$th->ID] = $th;
		}
		
		//var_dump($thumbs);
		$query->set('query_thumbnails', $thumbs);
	}
	
	return $posts;
} 


function tst_get_post_id_from_posts($posts){
		
	$ids = array();
	if(!empty($posts)){ foreach($posts as $p) {
		$ids[] = $p->ID;
	}}
	
	return $ids;
}

/* Custom conditions */
function is_about(){
	global $post;
		
	if(is_page_branch(2))
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
	global $post;
	
	$test = get_term_by('slug', $slug, $tax);
	if(empty($test))
		return false;
	
	if(is_tax($tax)){
		$qobj = get_queried_object();
		if($qobj->term_id == $test->term_id || $qobj->parent == $test->term_id)
			return true;
	}
	
	if(is_singular() && is_object_in_term($post->ID, $tax, $test->term_id))
		return true;
	
	return false;
}


function is_posts() {
	
	if(is_home() || is_category() || is_tax('auctor'))
		return true;	
		
	if(is_singular('post'))
		return true;
	
	return false;
}

function is_events() {	
		
	if(is_post_type_archive('event') || is_page('calendar'))
		return true;
		
	if(is_singular('event'))
		return true;
	
	return false;
}

function is_activity() {	
		
	if(is_page_branch(868))
		return true;
		
	return false;
}


/** Menu filter sceleton **/
add_filter('wp_nav_menu_objects', 'tst_custom_menu_items', 2, 2);
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
function tst_posted_on($cpost) {
			
	if(is_int($cpost))
		$cpost = get_post($cpost);
			
	$meta = array();
	$sep = '';
	
	if('post' == $cpost->post_type){
		$label = __('in the category', 'tst');
		$meta[] = "<time class='date'>".esc_html(get_the_date('d.m.Y', $cpost))."</time>";
		$meta[] = get_the_term_list($cpost->ID, 'category', '<span class="category">'.$label.' ', ', ', '</span>');
		$sep = ' ';
	}
	if('project' == $cpost->post_type){
		
		$meta[] = "<time class='date'>".esc_html(get_the_date('d.m.Y', $cpost))."</time>";		
	}
		
	return implode($sep, $meta);		
}

/** Logo **/
function tst_site_logo($size = 'regular') {

	switch($size) {
		case 'context':
			$file = 'pic-logo-accent';
			break;
		case 'small':
			$file = 'pic-logo-small';
			break;
		default:
			$file = 'pic-logo';
			break;	
	}
	
	$file = esc_attr($file);	
?>
<svg class="logo <?php echo $file;?>">
	<use xlink:href="#<?php echo $file;?>" />
</svg>
<?php
}


/** Separator **/
function tst_get_sep($mark = '//') {
	
	return "<span class='sep'>".$mark."</span>";
}

/** CPT archive title **/
function tst_get_post_type_archive_title($post_type) {
	
	$pt_obj = get_post_type_object( $post_type );	
	$name = $pt_obj->labels->menu_name;
	
	
	return $name;
}

// loader panel
function tst_loader_panel(){

	return '<div class="loader-panel"><div class="spinner"></div></div>';

}


function tst_material_icon($icon){
	
	$icon = esc_attr($icon);
	return "<i class='material-icons'>{$icon}</i>";
}


/** Header image **/
function tst_header_image_url(){
	
	$img = '';
	if(is_tax()){
		$qo = get_queried_object();
		$img = (function_exists('get_field')) ? get_field('header_img', $qo->taxonomy.'_'.$qo->term_id) : 0;
		$img = wp_get_attachment_url($img);
	}
	elseif(is_single() || is_page()){
		$qo = get_queried_object();
		$img = get_post_meta($qo->ID,'header_img', true);		
		$img = wp_get_attachment_url($img);
	}
	
	if(empty($img)){ // fallback
		$img = get_template_directory_uri().'/assets/images/header-default.jpg';
	}
	
	return $img;
}




/** == NAVs == **/

/* Nav in loops */
function tst_paging_nav($query = null) {
	global $wp_query;
	
	if(!$query)
		$query = $wp_query;
	
	
	// Don't print empty markup if there's only one page.
	if ($query->max_num_pages < 2 ) {
		return;
	}
		
	$p = tst_load_more_link($query, false);	
	if(!empty($p)) {
		$p = "<div class='paging-navigation'>$p</div>";
	}

	return $p;
}

add_filter('next_posts_link_attributes', 'tst_load_more_link_css');
function tst_load_more_link_css($attr){
	
	$attr = " class='mdl-button mdl-js-button mdl-js-ripple-effect'";
	
	return $attr;
}

function tst_load_more_link($query = null, $echo = true) {
	global $wp_query;
	
	if(!$query)
		$query = $wp_query;
	
	$label = __('More entries', 'tst');
	if(is_search()){
		$label = __('More results', 'tst');
	}
	elseif(isset($query->posts[0])){
		switch($query->posts[0]->post_type){
			case 'post':
				$label = __('More news', 'tst');
				break;
			case 'project':
				$label = __('More projects', 'tst');
				break;
			case 'org':
				$label = __('More orgs', 'tst');
				break;	
		}
	}
	
	$l = get_next_posts_link($label, $query->max_num_pages);
	if(empty($l) && $query->get('paged') > 1){ //last page
		$link = get_pagenum_link(0);
		$l = "<a href='{$link}' ".tst_load_more_link_css('').">".__('To the beginning', 'tst')."</a>";
	}
	
	if($echo){
		echo $l;
	}
	else {
		return $l;
	}	
}


/** Breadcrumbs  **/
function tst_breadcrumbs(){
	global $post;
		
	$links = array();
	if(is_front_page()){
		$links[] = "<span class='crumb-name'>".get_bloginfo('name')."</span>";
	}
	elseif(is_singular('post')) {
		
		$cat = get_the_terms($post->ID, 'category');
		if(!empty($cat)){
			$links[] = "<a href='".get_term_link($cat[0])."' class='crumb-link'>".apply_filters('tst_the_title', $cat[0]->name)."</a>";
		}			
	}
	elseif(is_singular('event')) {
		
		$p = get_page_by_path('calendar');
		$links[] = "<a href='".get_permalink($p)."' class='crumb-link'>".get_the_title($p)."</a>";		
	}
	elseif(is_page() || is_singular('leyka_campaign')){
		//@to-do - if treee ?
		$links[] = "<span class='crumb-name'>".get_the_title($post)."</span>";
	}
	elseif(is_home()){
		$p = get_post(get_option('page_for_posts'));
		if($p)
			$links[] = "<span class='crumb-name'>".get_the_title($p)."</span>";
	}
	elseif(is_category()){	
			
		$links[] = "<span class='crumb-name'>".single_cat_title('', false)."</span>";
	}
	elseif(is_post_type_archive('project')) {
		$links[] = "<span class='crumb-name'>".tst_get_post_type_archive_title('project')."</span>";
		
	}
	elseif(is_post_type_archive('org')) {
		$links[] = "<span class='crumb-name'>".tst_get_post_type_archive_title('org')."</span>";
		
	}
	elseif(is_singular('project')) {		
		$links[] = "<a href='".get_post_type_archive_link('project')."' class='crumb-link'>".tst_get_post_type_archive_title('project')."</a>";
		
	}
	
	$sep = '';
	
	return "<div class='crumbs'>".implode($sep, $links)."</div>";	
}


/** Next link **/
function tst_next_link($cpost){	
	
	if($cpost->post_type == 'event'){
		
		//get next event
		$news_query = new WP_Query(array(
			'post_type' => 'event',			
			'meta_key' => 'event_date',
			'orderby' => 'meta_value',
			'order' => 'ASC', 
			'posts_per_page' => 1,
			'meta_query' => array(
				array(
					'key'     => 'event_date',
					'value'   => get_post_meta($cpost->ID, 'event_date', true),
					'compare' => '>', // '>' to get a chronologically next event
				),
			),
		));
		
		if(!$news_query->have_posts()){
			$news_query = new WP_Query(array(
				'post_type' => 'event',			
				'meta_key' => 'event_date',
				'orderby' => 'meta_value',
				'order' => 'ASC', 
				'posts_per_page' => 1				
			));
		}
		$next = '';
		
		if(isset($news_query->posts[0]) && $news_query->posts[0]->ID != $cpost->ID){
			$label = 'След.';
			$next = "<a href='".get_permalink($news_query->posts[0])."' rel='next' class='mdl-button mdl-js-button mdl-button--primary'>".$label." &raquo;</a>";
		}
	}
	else {
		$label = 'След.';
		$next =  get_next_post_link('%link', $label.' &raquo;', true);
		if(empty($next)) {
			$next = tst_next_fallback_link($cpost);
		}
	}
		
	return $next;				
}

function tst_next_fallback_link($cpost){	
			
	$args = array(
		'post_type' => $cpost->post_type,
		'posts_per_page' => 1,
		'orderby' => 'date',
		'order' => 'ASC'
	);
		
	$query = new WP_Query($args);
	$link = '';
	
	if(isset($query->posts[0]) && $query->posts[0]->ID != $cpost->ID){
		$label = __('Next post', 'tst');
		$link = "<a href='".get_permalink($query->posts[0])."' rel='next' class='mdl-button mdl-js-button mdl-button--primary'>{$label}&nbsp;&raquo;</a>";
	}
	
	return $link;
}



/** == Cards in loop == **/
function tst_get_the_post_thumbnail($post, $size = 'thumbnail', $alt = null){
	
	if(!$alt)
		$alt = __('Thumbnail', 'tst');
	
	$id = (int)get_post_meta($post->ID, '_thumbnail_id', true );
	$img =  wp_get_attachment_image_src($id, $size, false);
	
	if(!$img)
		return '';
	
	return "<img src='".$img[0]."' alt='{$alt}'>";
}



/** Post card content **/
function tst_post_card($cpost, $tax = 'auctor'){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$pl = get_permalink($cpost);
	$e = tst_get_post_excerpt($cpost, 30, true);
	$date = "<time>".get_the_date('d.m.Y.', $cpost->ID)."</time> - ";
	
	$name = $desc = $avatar = '';
	
	if($tax == 'auctor'){
		$author = tst_get_post_author($cpost);
		$name = ($author) ? $author->name : '';
		$desc = ($author) ? wp_trim_words($author->description, 20) : '';
		$avatar = ($author) ? tst_get_author_avatar($author->term_id) : '';
	}
	elseif($tax) { //category
		$cat = get_the_terms($cpost->ID, $tax);
		$name = (isset($cat[0])) ? $cat[0]->name : '';
		$desc = (isset($cat[0])) ? wp_trim_words($cat[0]->description, 20) : '';
		$avatar = (isset($cat[0])) ? tst_get_tax_avatar($cat[0]) : '';
	}

?>
<article <?php post_class('mdl-cell mdl-cell--4-col masonry-item'); ?>>
<div class="tpl-card-blank mdl-card mdl-shadow--2dp">
	
	<?php if(has_post_thumbnail($cpost->ID)){ ?>
		<div class="mdl-card__media"><a href="<?php echo $pl;?>">
			<?php echo tst_get_the_post_thumbnail($cpost, 'post-thumbnail'); ?>
		</a></div>			
	<?php } ?>
	
	<?php if(!empty($name)) { ?>
		<div class="entry-author mdl-card__supporting-text">		
			<div class="pictured-card-item">
				<div class="author-avatar round-image pci-img"><?php echo $avatar;?></div>
					
				<div class="author-content card-footer-content pci-content">
					<h5 class="author-name mdl-typography--body-1"><?php echo apply_filters('tst_the_title', $name);?></h5>
					<?php if(!empty($desc)) { ?>
						<p class="author-role mdl-typography--caption"><?php echo apply_filters('tst_the_title', $desc);?></p>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo $pl;?>"><?php echo get_the_title($cpost->ID);?></a></h4>
	</div>
	
	<?php echo tst_card_summary($date.$e); ?>
	<div class="mdl-card--expand"></div>
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo $pl;?>" class="mdl-button mdl-js-button"><?php _e('Details', 'tst');?></a>
	</div>
</div>
</article>
<?php
}

/** Post card content **/
function tst_project_card($cpost){
		
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = tst_get_post_excerpt($cpost, 30, true);	
	$pl = get_permalink($cpost);
	
	
?>
<article <?php post_class('mdl-cell mdl-cell--4-col masonry-item'); ?>>
<div class="tpl-card-color mdl-card mdl-shadow--2dp">
	
	<?php if(has_post_thumbnail($cpost->ID)){ ?>
	<div class="mdl-card__media">
		<a href="<?php echo $pl;?>"><?php echo tst_get_the_post_thumbnail($cpost, 'post-thumbnail'); ?></a>
	</div>			
	<?php } ?>
		
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo $pl;?>"><?php echo get_the_title($cpost->ID);?></a></h4>
	</div>
	
	<?php echo tst_card_summary($e); ?>
	
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo $pl;?>" class="mdl-button mdl-js-button"><?php _e('Details', 'tst');?></a>
	</div>
</div>	
</article>
<?php
}


/** Partner card content **/
function tst_org_card($cpost, $ext_link = true){
		
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	if($ext_link) {
		$label = __('Website', 'tst');
		$url = $cpost->post_excerpt ? esc_url($cpost->post_excerpt) : '';
		$target = " target='_blank'";
	}
	else {
		$label = __('Details', 'tst');
		$url = get_permalink($cpost);
		$target = "";
	}
	
	$logo = tst_get_the_post_thumbnail($cpost, 'full');
	$text = apply_filters('tst_the_content', $cpost->post_content);	
	
?>
<article <?php post_class('mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone'); ?>>
<div class="tpl-card-mix mdl-card mdl-shadow--2dp">
	
	<div class="mdl-card__media">
		<a class="logo-link" href="<?php echo $url;?>"<?php echo $target;?>><?php echo $logo; ?></a>
	</div>
			
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo $url;?>"><?php echo get_the_title($cpost->ID);?></a></h4>
	</div>
			
	<?php echo tst_card_summary($text); ?>
			
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo $url;?>"<?php echo $target;?> class="mdl-button mdl-js-button"><?php echo $label;?></a>
	</div>
</div>	
</article>
<?php
}

/** Page card content **/
function tst_page_card($cpost){
		
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = tst_get_post_excerpt($cpost, 30, true);	
	$pl = get_permalink($cpost);
		
	$img = get_post_meta($cpost->ID, 'header_img', true);
	$img = wp_get_attachment_image($img, 'post-thumbnail', false, array('alt' => __('Thumbnail', 'tst')));
?>
<article <?php post_class('mdl-cell mdl-cell--4-col masonry-item'); ?>>
<div class="tpl-card-color mdl-card mdl-shadow--2dp">
	
	<?php if($img){ ?>
	<div class="mdl-card__media">
		<a href="<?php echo $pl;?>"><?php echo $img; ?></a>
	</div>			
	<?php } ?>
		
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo $pl;?>"><?php echo get_the_title($cpost->ID);?></a></h4>
	</div>
	
	<?php echo tst_card_summary($e); ?>
	
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo $pl;?>" class="mdl-button mdl-js-button"><?php _e('Details', 'tst');?></a>
	</div>
</div>	
</article>
<?php
}


/** Partner card content **/
function tst_general_card($block, $link_label = null, $ext_link = true){
	
	$name = (isset($block['name'])) ? apply_filters('tst_the_title', $block['name']) : '';
    $url =  (isset($block['url'])) ? esc_url($block['url']) : '';
	$pic_id = (isset($block['pic'])) ? intval($block['pic']) : 0;
    $logo = wp_get_attachment_image($pic_id, 'thumbnail-embed', false, array('alt' => $name));
    $text = (isset($block['descr'])) ? apply_filters('tst_the_content', $block['descr']) : ''; 
	$label = ($link_label) ? $link_label : __('Website', 'tst');
	$target = ($ext_link) ? " target='_blank'" : '';
		
?>
<article <?php post_class('mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone'); ?>>
<div class="tpl-card-mix mdl-card mdl-shadow--2dp">
	
	<div class="mdl-card__media">
		<a class="logo-link" href="<?php echo $url;?>"<?php echo $target;?>><?php echo $logo; ?></a>
	</div>
			
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo $url;?>"><?php echo $name;?></a></h4>
	</div>
			
	<?php echo tst_card_summary($text); ?>
	
	<?php if(!empty($url)) { ?>
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo $url;?>"<?php echo $target;?> class="mdl-button mdl-js-button"><?php echo $label;?></a>
	</div>
	<?php } ?>
</div>	
</article>
<?php
}


/** == Social buttons == **/
function tst_social_share_no_js() {
	
	$title = (class_exists('WPSEO_Frontend')) ? WPSEO_Frontend::get_instance()->title( '' ) : '';
	$link = tst_current_url();
	$text = $title.' '.$link;

	$data = array(
		'vkontakte' => array(
			'label' => 'Поделиться во Вконтакте',
			'url' => 'https://vk.com/share.php?url='.$link.'&title='.$title,
			'txt' => 'Вконтакте',
			'icon' => 'icon-vk',
			'show_mobile' => false
		),
		'facebook' => array(
			'label' => 'Поделиться на Фейсбуке',
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$link,
			'txt' => 'Facebook',
			'icon' => 'icon-facebook',
			'show_mobile' => true
		),		
		'twitter' => array(
			'label' => 'Поделиться ссылкой в Твиттере',
			'url' => 'https://twitter.com/intent/tweet?url='.$link.'&text='.$title,
			'txt' => 'Twitter',
			'icon' => 'icon-twitter',
			'show_mobile' => false		
		),
		'odnoklassniki' => array(
			'label' => 'Поделиться ссылкой в Одноклассниках',
			'url' => 'http://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
			'txt' => 'Одноклассники',
			'icon' => 'icon-ok',
			'show_mobile' => false
			
		),
	);
	
?>
<div class="social-likes-wrapper">
<div class="social-likes social-likes_visible social-likes_ready">

<?php
foreach($data as $key => $obj){		
	if((tst_is_mobile_user_agent() && $obj['show_mobile']) || !tst_is_mobile_user_agent()){
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
		<a href="<?php echo $obj['url'];?>" class="social-likes__button social-likes__button_<?php echo $key;?>" target="_blank" onClick="window.open('<?php echo $obj['url'];?>','<?php echo $obj['label'];?>','top=320,left=325,width=650,height=430,status=no,scrollbars=no,menubar=no,tollbars=no');return false;">
			<svg class="sh-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span>
		</a>
	</div>
<?php 
	}
	
} //foreach

	$text = $title.' '.$link;
	
	$mobile = array(
		'twitter' => array(
			'label' => 'Поделиться ссылкой в Твиттере',
			'url' => 'twitter://post?message='.$text,
			'txt' => 'Twitter',
			'icon' => 'icon-twitter',
			'show_desktop' => false		
		),
		'whatsapp' => array(
			'label' => 'Поделиться ссылкой в WhatsApp',
			'url' => 'whatsapp://send?text='.$text,
			'txt' => 'WhatsApp',
			'icon' => 'icon-whatsup',
			'show_desktop' => false
		),
		'telegram' => array(
			'label' => 'Поделиться ссылкой в Telegram',
			'url' => 'tg://msg?text='.$text,
			'txt' => 'Telegram',
			'icon' => 'icon-telegram',
			'show_desktop' => false
		),
		'viber' => array(
			'label' => 'Поделиться ссылкой в Viber',
			'url' => 'viber://forward?text='.$text,
			'txt' => 'Viber',
			'icon' => 'icon-viber',
			'show_desktop' => false
		),
	);
		
	foreach($mobile as $key => $obj) {
		
		if((!tst_is_mobile_user_agent() && $obj['show_desktop']) || tst_is_mobile_user_agent()) {
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
	<a href="<?php echo $obj['url'];?>" target="_blank" class="social-likes__button social-likes__button_<?php echo $key;?>"><svg class="sh-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span></a>
	</div>	
<?php } } //endforeach ?>

</div>
</div>
<?php
}

function tst_is_mobile_user_agent(){
	//may be need some more sophisticated testing
	$test = false;
	
	if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
		$test = true;
	}
	
	return $test;
}



/** == Summary helpers == **/

/** Excerpt  **/
function tst_get_post_excerpt($cpost, $l = 30, $force_l = false){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);	
	
	return $e;
}

function tst_card_summary($summary_content){
			
	$text = apply_filters('tst_the_content', $summary_content);
	$text = '<div class="card-summary mdl-card__supporting-text mdl-card--expand">'.$text.'</div>';
		
	return $text;
}



/** == Authors == **/

/** Default author avatar **/
function tst_get_default_author_avatar(){
	
	$theme_dir_url = get_template_directory_uri();
	$src = get_template_directory_uri().'/assets/images/author-default.jpg';
	$alt = 'Аватар';
	
	return "<img src='{$src}' alt='{$alt}'>";
}

/** Author **/
function tst_get_post_author($cpost) {
			
	$author = get_the_terms($cpost->ID, 'auctor');
	if(!empty($author) && !is_wp_error($author))
		$author = $author[0];
	
	return $author;
}


function tst_get_author_avatar($author_term_id) {

	$avatar = get_field('auctor_photo', 'auctor_'.$author_term_id);

    return $avatar ? wp_get_attachment_image($avatar, 'avatar') : tst_get_default_author_avatar();
}

function tst_get_tax_avatar($term) {

	$avatar = get_field('header_img', $term->taxonomy.'_'.$term->term_id);

    return $avatar ? wp_get_attachment_image($avatar, 'avatar') : tst_get_default_author_avatar();
}


/** == Compact Items for posts in widgets and related section == **/

// deafult thumbnail for posts
function tst_get_default_post_thumbnail($size){
		
	$default_thumb_id = attachment_url_to_postid(get_theme_mod('default_thumbnail'));
	$img = '';
	if($default_thumb_id){
		$img = wp_get_attachment_image($default_thumb_id, $size);	
	}
	
	return $img;
}

/** Compact post item **/
function tst_compact_project_item($cpost){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = tst_get_post_excerpt($cpost, 30, true);	
?>
	<div class="tpl-related-project"><a href="<?php echo get_permalink($cpost);?>">
	
	<div class="mdl-grid mdl-grid--no-spacing">
		<div class="mdl-cell mdl-cell--9-col mdl-cell--5-col-tablet mdl-cell--4-col-phone">
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
				
			<!-- summary -->
			<p class="entry-summary">
				<?php echo apply_filters('tstpsk_the_tite', $e); ?>
			</p>
		</div>
		
		<div class="mdl-cell mdl-cell--3-col mdl-cell--3-col-tablet mdl-cell--hide-phone">
		<?php
			$thumb = get_the_post_thumbnail($cpost->ID, 'thumbnail-landscape', array('alt' => __('Thumbnail', 'tst')) ) ;
			if(empty($thumb)){
				$thumb = tst_get_default_post_thumbnail('thumbnail-landscape');
			}
			echo $thumb;
		?>
		</div>
	</div>	
	
	</a></div>
<?php
}

/** Compact post item **/
function tst_compact_post_item($cpost, $show_thumb = true, $tax = 'category'){
			
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	if($tax == 'auctor'){
		$author = tst_get_post_author($cpost);
		$name = ($author) ? $author->name : '';
		$avatar = ($show_thumb && $author) ? tst_get_author_avatar($author->term_id) : '';
	}
	else { //category
		$cat = get_the_terms($cpost->ID, $tax);
		$name = (isset($cat[0])) ? $cat[0]->name : '';
		$avatar = ($show_thumb && isset($cat[0])) ? tst_get_tax_avatar($cat[0]) : '';
	}
	
?>
	<div class="tpl-related-post"><a href="<?php echo get_permalink($cpost);?>">
	
	<div class="mdl-grid mdl-grid--no-spacing">
		<div class="mdl-cell mdl-cell--9-col mdl-cell--5-col-tablet mdl-cell--4-col-phone">
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			
		<?php if($show_thumb) { ?>	
			<div class="entry-author pictured-card-item">
				<div class="author-avatar round-image pci-img"><?php echo $avatar;?></div>
					
				<div class="author-content card-footer-content pci-content">
					<h5 class="author-name mdl-typography--body-1"><?php echo apply_filters('tst_the_title', $name);?></h5>
					<p class="post-date mdl-typography--caption"><time><?php echo get_the_date('d.m.Y.', $cpost);?></time></p>
				</div>
				
			</div>	
		<?php } else { ?>
			<div class="entry-author plain-card-item">
				<h5 class="author-name mdl-typography--body-1"><?php echo apply_filters('tst_the_title', $name);?></h5>
				<p class="post-date mdl-typography--caption"><time><?php echo get_the_date('d.m.Y.', $cpost);?></time></p>				
			</div>	
		<?php } ?>
		</div>
		
		<div class="mdl-cell mdl-cell--3-col mdl-cell--3-col-tablet mdl-cell--hide-phone">
		<?php
			$thumb = tst_get_the_post_thumbnail($cpost, 'thumbnail-landscape') ;
			if(empty($thumb)){
				$thumb = tst_get_default_post_thumbnail('thumbnail-landscape');
			}
			
			echo $thumb;
		?>
		</div>
	</div>	
	
	</a></div>
<?php
}



/** Post card content **/
function tst_post_card_content($cpost = null, $tax = 'auctor'){
			
	if(!$cpost)
		$cpost = $post;
	
	$name = $desc = $avatar = '';
	
	if($tax == 'auctor'){
		$author = tst_get_post_author($cpost);
		$name = ($author) ? $author->name : '';
		$desc = ($author) ? $author->description : '';
		$avatar = ($show_thumb && $author) ? tst_get_author_avatar($author->term_id) : '';
	}
	elseif($tax) { //category
		$cat = get_the_terms($cpost->ID, $tax);
		$name = (isset($cat[0])) ? $cat[0]->name : '';
		$desc = (isset($cat[0])) ? $cat[0]->description : '';
		$avatar = ($show_thumb && isset($cat[0])) ? tst_get_tax_avatar($cat[0]) : '';
	}
?>
	<?php if(has_post_thumbnail($cpost->ID)){ ?>
	<div class="mdl-card__media">
		<?php echo tst_get_post_thumbnail($cpost, 'embed'); ?>		
	</div>			
	<?php } ?>
	
	<?php if(!empty($name)) { ?>
		<div class="entry-author mdl-card__supporting-text">
			<div class="pictured-card-item">
				<div class="author-avatar round-image pci-img"><?php echo $avatar;?></div>
					
				<div class="author-content card-footer-content pci-content">
					<h5 class="author-name mdl-typography--body-1"><?php echo apply_filters('tst_the_title', $name);?></h5>
				<?php if(!empty($desc)) { ?>	
					<p class="author-role mdl-typography--caption"><?php echo apply_filters('tst_the_title', $desc);?></p>
				<?php  } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<div class="mdl-card__title">
		<h4 class="mdl-card__title-text"><a href="<?php echo get_permalink($cpost);?>"><?php echo get_the_title($cpost->ID);?></a></h4>
	</div>
	
	<?php echo tst_card_summary($cpost); ?>
	<div class="mdl-card--expand"></div>
	<div class="mdl-card__actions mdl-card--border">
		<a href="<?php echo get_permalink($cpost);?>" class="mdl-button mdl-js-button">Подробнее</a>
	</div>
<?php
}
