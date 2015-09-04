<?php
/**
 * Shortcodes
 **/

add_shortcode('tst_sitemap', 'tst_sitemap_screen');
function tst_sitemap_screen($atts){
		
	$out =  wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));
	
	return $out;
}


/** == Defaults == **/

/**
 * Clear
 **/
add_shortcode('clear', 'tst_clear_screen');
function tst_clear_screen($atts){

	return '<div class="clear"></div>';
}


/**
 * Partners gallery
 **/

add_shortcode('partners_gallery', 'tst_partners_gallery_screen');
function tst_partners_gallery_screen($atts){

	extract(shortcode_atts(array(
		'type' => '',
		'num'  => -1,
		'css'  => ''
	), $atts));

	$size = 'full'; // logo size

	$args = array(
		'post_type' => 'partner',
		'posts_per_page' => $num,
		'orderby' => array('menu_order' => 'DESC')
	);
	
	if(!empty($type)){

		$type = array_map('trim', explode('_', $type));
		
		$args['tax_query'][] = array(
			'taxonomy' => ($type[0] == 'category') ? 'partner_cat' : 'period',
			'field' => 'id',
			'terms' => intval($type[1])
		);
	}
	
	$query = new WP_Query($args);
		
	ob_start();
?>
	<ul class="cf partners logo-gallery frame <?php echo $css;?>">
    <?php
		foreach($query->posts as $item):
        
            $url = $item->post_excerpt ? esc_url($item->post_excerpt) : '';
            $txt = esc_attr($item->post_title);					
			$cat = tst_get_partner_type($item);
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
				<?php if($cat) {?>
					<span class="partner-cat"><?php echo $cat;?></span>
				<?php }?>
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

    ob_start();?>

    <section class="cards-gallery" <?php echo $css;?>>
    <div class="mdl-grid">
        <?php foreach($posts as $item) {

            $url = get_permalink($item->ID);
            $name = esc_attr($item->post_title);
            $pic = get_the_post_thumbnail($item->ID, $pic_size ? $pic_size : 'full');
            $text = $item->post_excerpt ? esc_attr($item->post_excerpt) : esc_html($item->post_content);
        ?>
            <div class="mdl-cell mdl-cell--4-col mdl-cell--3-col-desktop">
                <div class="mdl-card mdl-shadow--2dp tpl-partner">
                    <div class="mdl-card__media">
                        <?php if($url && $pic) {?>
                            <a class="logo-link" title="<?php echo esc_attr($name);?>" href="<?php echo $url;?>"><?php echo $pic;?></a>
                        <?php } else if($pic) {?>
                            <span class="logo-link" title="<?php echo esc_attr($name);?>"><?php echo $pic;?></span>
                        <?php }?>
                    </div>

                    <div class="mdl-card__title">
                        <h4 class="mdl-card__title-text">
                            <?php echo apply_filters(
                                'tst_the_title',
                                $url ? "<a class='name-link' href='$url' title='$name'>$name</a>" : $name
                            );?>
                        </h4>
                    </div>

                    <div class="mdl-card__supporting-text mdl-card--expand"><?php echo apply_filters('tst_the_title', wp_trim_words($text, 20));?></div>

                    <div class="mdl-card__actions mdl-card--border">
                        <?php if($url) {?>
                            <a class="mdl-button mdl-js-button mdl-button--primary" href="<?php echo $url;?>"><?php echo $link_text;?></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;
}