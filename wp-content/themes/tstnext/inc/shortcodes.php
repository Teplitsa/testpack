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


/** Partners gallery **/
add_shortcode('tst_partners_gallery', 'tst_partners_gallery_screen');
function tst_partners_gallery_screen($atts){
	
	extract(shortcode_atts(array(				
		'css'  => ''
	), $atts));
		
	$size = 'full'; // logo size
	
	$partners = (function_exists('get_field')) ? get_field('partners_to_show') : false;
	if(!$partners)
		return '';
	
?>
	<ul class="partners-logo-gallery frame <?php echo $css;?>">
    <?php
		foreach($partners as $item):
        
            $url = $item->post_excerpt ? esc_url($item->post_excerpt) : '';
            $txt = esc_attr($item->post_title);					
			//$cat = tst_get_partner_type($item);
        ?>
		<li class="bit mf-4 md-3 lg-2">
			<div class="logo">
				<div class='logo-frame'>			
				<?php if(!empty($url)): ?>
					<a href="<?php echo $url;?>" target="_blank" title="<?php echo $txt;?>" class="logo-link">
				<?php else: ?>
					<span class="logo-link" title="<?php echo $txt;?>">
				<?php endif;?>

				<?php echo get_the_post_thumbnail($item->ID, $size);?>

				<?php if($url): ?>
					</a>
				<?php else: ?>
					</span>
				<?php endif;?>
				</div>
				
			</div>
			
		</li>
        <?php endforeach; ?>        
    </ul>
<?php	
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

