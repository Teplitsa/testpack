<?php
/**
 * Shortcodes
 **/

add_filter('widget_text', 'do_shortcode');

/** Featured page in intro block **/
add_shortcode('featured_action', 'rdc_featured_action_screen');
function rdc_featured_action_screen($atts){
	
	extract(shortcode_atts(array(				
		'id'  => 0,
		'cta' => ''
	), $atts));
		
	$cpost = get_post($id);
	if(!$cpost)
		return '';
	
	$out = '';
	
	ob_start();
	rdc_featured_action_card($cpost, $cta);
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}


add_shortcode('page_section', 'rdc_page_section_screen');
function rdc_page_section_screen($atts, $content){
	
	extract(shortcode_atts(array(				
		'type'  => 'genral',
		'pic'   => 0,
		'title' => ''
	), $atts));
	
	$src = '';
	$out = '';
	if($pic > 0)
		$src = wp_get_attachment_url($pic);
		
	
	if(!empty($src)){
		$out  = "<div class='page-section picture' style='background-image: url({$src});'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= "<div class='frame'><div class='bit md-6'>{$content}</div>";
		$out .= "</div></div>";
	}
	elseif($type == '2col'){
		$out = "<div class='page-section 2col'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= "<div class='frame'>";
		$out .= do_shortcode($content);
		$out .= "</div></div>";
	}
	else {
		$out = "<div class='page-section {$type}'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= do_shortcode($content);
		$out .= "</div>";
	}
	
	return $out;
}

add_shortcode('col', 'rdc_col_screen');
function rdc_col_screen($atts, $content){
	
	return "<div class='bit md-6'>".do_shortcode($content)."</div>";
}

add_shortcode('newsletter_form', 'rdc_newsletter_form_screen');
function rdc_newsletter_form_screen($atts){
	
	return "<div class='newsletter-form'>".rdc_get_newsletter_form()."</div>";
}








/** Clear **/
add_shortcode('clear', 'rdc_clear_screen');
function rdc_clear_screen($atts){

	return '<div class="clear"></div>';
}


/** Number **/
add_shortcode('b_num', 'rdc_b_num_screen');
function rdc_b_num_screen($atts){
	
	extract(shortcode_atts(array(				
		'n'  => 1		
	), $atts));
	
	return '<div class="b-num"><span>'.intval($n).'</span></div>';
}

add_shortcode('yt_caption', 'rdc_yt_caption_screen');
function rdc_yt_caption_screen($atts, $content = null){	
	
	return '<div class="yt-caption">'.apply_filters('rdc_the_content', $content).'</div>';
}





/** Buttons **/
add_shortcode('rdc_btn', 'rdc_btn_screen');
function rdc_btn_screen($atts){
	
	extract(shortcode_atts(array(				
		'url'  => '',
		'txt'  => ''
	), $atts));
	
	if(empty($url))
		return '';
	
	$url = esc_url($url);
	$txt = apply_filters('rdc_the_title', $txt);
	
	ob_start();
?>
<span class="rdc-btn"><a href="<?php echo $url;?>" class="rdc-button"><?php echo $txt;?></a></span>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}




/** Partners gallery **/
add_shortcode('rdc_partners_gallery', 'rdc_partners_gallery_screen');
function rdc_partners_gallery_screen($atts){
	
	extract(shortcode_atts(array(				
		'css'  => ''
	), $atts));
		
	$size = 'full'; // logo size
	
	$partners = new WP_Query(array(
		'post_type' => 'org',
		'posts_per_page' => -1,
		'orderby' => 'rand'
	));
		
	if(!$partners->have_posts())
		return '';
	
	ob_start();
	
	foreach($partners->posts as $p){
		rdc_org_card($p);
	}
	
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}



/** Toggle **/
if(!shortcode_exists( 'su_spoiler' ))
	add_shortcode('su_spoiler', 'rdc_su_spoiler_screen');

function rdc_su_spoiler_screen($atts, $content = null){
	
	extract(shortcode_atts(array(
        'title' => 'Подробнее',
        'open'  => 'no',
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$title = apply_filters('rdc_the_title', $title);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	if($open == 'yes')
		$class .= ' toggled';
	
	ob_start();
?>
<div class="su-spoiler<?php echo $class;?>">
	<div class="su-spoiler-title"><span class="su-spoiler-icon"></span><?php echo $title;?></div>
	<div class="su-spoiler-content"><?php echo apply_filters('rdc_the_content', $content);?></div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Quote **/
add_shortcode('rdc_quote', 'rdc_quote_screen');
function rdc_quote_screen($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'name' => '',        
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$name = apply_filters('rdc_the_title', $name);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	ob_start();
?>
<div class="rdc-quote <?php echo $class;?>">	
	<div class="rdc-quote-content"><?php echo apply_filters('rdc_the_content', $content);?></div>
	<?php if(!empty($name)) { ?>
		<div class="rdc-quote-cite"><?php echo $name;?></div>
	<?php } ?>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/* Cards from posts */
add_shortcode('rdc_cards_from_posts', 'rdc_cards_from_posts');
function rdc_cards_from_posts($atts) {

    extract(shortcode_atts(array(
        'ids' => '',
        'css'  => '',
        'pic_size' => 'full',
        'link_text' => 'Веб-сайт',
    ), $atts));

    /** @var $ids */
    /** @var $css */
    /** @var $pic_size */
    /** @var $link_text */

    $posts = get_posts(array(
        'post__in' => array_map('trim', explode(',', $ids)),
        'post_type' => 'any',
        'orderby' => array('menu_order' => 'DESC')
    ));

    ob_start();
?>

    <section class="embed-cards-gallery" <?php echo $css;?>>
    <div class="mdl-grid">
    <?php
		foreach($posts as $item) {
			$callback = "rdc_".get_post_type($item)."_card";
			if(is_callable($callback)) {
				call_user_func($callback, $item);
			}
			else {
				rdc_post_card($item);
			}	
		}
	?>
    </div>
    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;
}