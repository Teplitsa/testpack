<?php
/**
 * Shortcodes
 **/

add_filter('widget_text', 'do_shortcode');

/** Clear **/
add_shortcode('clear', 'kds_clear_screen');
function kds_clear_screen($atts){

	return '<div class="clear"></div>';
}


/** Number **/
add_shortcode('b_num', 'kds_b_num_screen');
function kds_b_num_screen($atts){
	
	extract(shortcode_atts(array(				
		'n'  => 1		
	), $atts));
	
	return '<div class="b-num"><span>'.intval($n).'</span></div>';
}

add_shortcode('yt_caption', 'kds_yt_caption_screen');
function kds_yt_caption_screen($atts, $content = null){	
	
	return '<div class="yt-caption">'.apply_filters('kds_the_content', $content).'</div>';
}


/** Buttons **/
add_shortcode('kds_btn', 'kds_btn_screen');
function kds_btn_screen($atts){
	
	extract(shortcode_atts(array(				
		'url'  => '',
		'txt'  => ''
	), $atts));
	
	if(empty($url))
		return '';
	
	$url = esc_url($url);
	$txt = apply_filters('kds_the_title', $txt);
	
	ob_start();
?>
<span class="tst-btn"><a href="<?php echo $url;?>" class="tst-button"><?php echo $txt;?></a></span>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}




/** Partners gallery **/
add_shortcode('kds_partners_gallery', 'kds_partners_gallery_screen');
function kds_partners_gallery_screen($atts){
	
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
		kds_org_card($p);
	}
	
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}



/** Toggle **/
if(!shortcode_exists( 'su_spoiler' ))
	add_shortcode('su_spoiler', 'kds_su_spoiler_screen');

function kds_su_spoiler_screen($atts, $content = null){
	
	extract(shortcode_atts(array(
        'title' => 'Подробнее',
        'open'  => 'no',
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$title = apply_filters('kds_the_title', $title);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	if($open == 'yes')
		$class .= ' toggled';
	
	ob_start();
?>
<div class="su-spoiler<?php echo $class;?>">
	<div class="su-spoiler-title"><span class="su-spoiler-icon"></span><?php echo $title;?></div>
	<div class="su-spoiler-content"><?php echo apply_filters('kds_the_content', $content);?></div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Quote **/
add_shortcode('kds_quote', 'kds_quote_screen');
function kds_quote_screen($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'name' => '',        
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$name = apply_filters('kds_the_title', $name);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	ob_start();
?>
<div class="tst-quote <?php echo $class;?>">	
	<div class="tst-quote-content"><?php echo apply_filters('kds_the_content', $content);?></div>
	<?php if(!empty($name)) { ?>
		<div class="tst-quote-cite"><?php echo $name;?></div>
	<?php } ?>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}
