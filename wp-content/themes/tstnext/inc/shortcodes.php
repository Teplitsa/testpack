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
		
	
	$out = '<div class="clear"></div>';		

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
			
				<?php if(!empty($url)): ?>
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


