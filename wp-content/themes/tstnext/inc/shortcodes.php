<?php
/**
 * Shortcodes
 **/

add_filter('widget_text', 'do_shortcode');
 
add_shortcode('tst_sitemap', 'tst_sitemap_screen');
function tst_sitemap_screen($atts){
		
	$out =  wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));
	
	return $out;
}


/** Clear **/
add_shortcode('clear', 'tst_clear_screen');
function tst_clear_screen($atts){

	return '<div class="clear"></div>';
}

/** lead **/
add_shortcode('lead', 'tst_lead_screen');
function tst_lead_screen($atts, $content){
	
	if(empty($content))
		return '';
	
	return '<div class="entry-summary">'.apply_filters('the_content', $content).'</div>';
}

add_shortcode('max_content_col', 'tst_max_content_col_screen');
function tst_max_content_col_screen($atts, $content){
	
	if(empty($content))
		return '';
	
	return '<div class="max-content-col">'.apply_filters('the_content', $content).'</div>';
}


/** lead **/
add_shortcode('fab', 'tst_fab_screen');
function tst_fab_screen($atts){
	
	extract(shortcode_atts(array(				
		'url'  => ''
	), $atts));
	
	if(empty($url))
		return '';
	
	ob_start();
?>
<div class="fab"><a href="<?php echo $url;?>" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored"><svg class="mi"><use xlink:href="#pic-favorite_border" /></svg></a></div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

add_shortcode('tst_btn', 'tst_btn_screen');
function tst_btn_screen($atts){
	
	extract(shortcode_atts(array(				
		'url'  => '',
		'txt'  => ''
	), $atts));
	
	if(empty($url))
		return '';
	
	$url = esc_url($url);
	$txt = apply_filters('frl_the_title', $txt);
	
	ob_start();
?>
<span class="tst-btn"><a href="<?php echo $url;?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"><?php echo $txt;?></a></span>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}


/** Partners gallery **/
add_shortcode('tst_partners_gallery', 'tst_partners_gallery_screen');
function tst_partners_gallery_screen($atts){
	
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
		tst_org_card($p);
	}
	
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

add_shortcode('tst_cards_from_posts', 'tst_cards_from_posts');
function tst_cards_from_posts($atts) {

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
			$callback = "tst_".get_post_type($item)."_card";
			if(is_callable($callback)) {
				call_user_func($callback, $item);
			}
			else {
				tst_post_card($item);
			}	
		}
	?>
    </div>
    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

/** Urgent help **/
add_shortcode('tst_children_to_help', 'tst_children_to_help_screen');
function tst_children_to_help_screen($atts) {
	
	 extract(shortcode_atts(array(
        'num' => 6        
    ), $atts));
	
	$query = new WP_Query(array(
		'post_type' => 'children',	
		'posts_per_page' => $num, 
		'orderby' => 'rand',
		'tax_query' => array(
			array(
				'taxonomy' => 'children_status',
				'field' => 'slug',
				'terms' => 'need-help'
			)
		)
	));
	
	if(!$query->have_posts())
		return '';
	
	ob_start();
?>
<div class="embed-children">
<div class="mdl-grid">
<?php foreach($query->get_posts() as $chp) {
		tst_children_card($chp);
}?>
</div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}


/** Toggle **/
if(!shortcode_exists( 'su_spoiler' ))
	add_shortcode('su_spoiler', 'tst_su_spoiler_screen');

function tst_su_spoiler_screen($atts, $content = null){
	
	extract(shortcode_atts(array(
        'title' => 'Подробнее',
        'open'  => 'no',
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$title = apply_filters('tst_the_title', $title);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	if($open == 'yes')
		$class .= ' toggled';
	
	ob_start();
?>
<div class="su-spoiler<?php echo $class;?>">
	<div class="su-spoiler-title"><span class="su-spoiler-icon"></span><?php echo $title;?></div>
	<div class="su-spoiler-content"><?php echo apply_filters('tst_the_content', $content);?></div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Quote **/
add_shortcode('tst_quote', 'tst_quote_screen');
function tst_quote_screen($atts, $content = null) {
	
	extract(shortcode_atts(array(
        'name' => '',        
		'class' => ''
    ), $atts));
	
	if(empty($content))
		return '';
	
	$name = apply_filters('tst_the_title', $name);
	$class = (!empty($class)) ? ' '.esc_attr($class) : '';
	ob_start();
?>
<div class="tst-quote <?php echo $class;?>">	
	<div class="tst-quote-content"><?php echo apply_filters('tst_the_content', $content);?></div>
	<?php if(!empty($name)) { ?>
		<div class="tst-quote-cite"><?php echo $name;?></div>
	<?php } ?>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}
