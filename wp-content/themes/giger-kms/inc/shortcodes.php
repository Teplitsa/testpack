<?php
/**
 * Shortcodes
 **/

/** sitemap **/
add_shortcode('tst_sitemap', 'tst_sitemap_screen');
function tst_sitemap_screen($atts){

	$out =  wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));

	return $out;
}



/** == OLD == **/
/** logo vvc **/
add_shortcode('tst_logo_vvc', 'tst_logo_vvc_screen');
function tst_logo_vvc_screen($atts){

	$out =  "<div class='vvc-logo'>".tst_svg_icon('pic-vvc', false)."</div>";

	return $out;
}




/** Youtube video caption **/
add_shortcode('yt_caption', 'tst_yt_caption_screen');
function tst_yt_caption_screen($atts, $content = null){

	return '<div class="yt-caption">'.apply_filters('tst_the_content', $content).'</div>';
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
