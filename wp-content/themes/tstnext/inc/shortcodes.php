<?php
/**
 * Shortcodes
 **/

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

