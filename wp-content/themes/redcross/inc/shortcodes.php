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
		'title' => '',
		'css'   => ''
	), $atts));
	
	$src = '';
	$out = '';
	if($pic > 0)
		$src = wp_get_attachment_url($pic);
		
	
	if(!empty($src)){
		$out  = "<div class='page-section picture {$css}' style='background-image: url({$src});'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= "<div class='rdc-col-row'><div class='rdc-col'>".apply_filters('rdc_the_content', $content)."</div>";
		$out .= "</div></div>";
	}
	elseif($type == '2col'){
		$out = "<div class='page-section 2col {$css}'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= "<div class='rdc-col-row'>";
		$out .= do_shortcode($content);
		$out .= "</div></div>";
	}
	else {
		$out = "<div class='page-section {$type} {$css}'>";
		if(!empty($title))
			$out .= "<h2 class='page-section-title'>{$title}</h2>";
		$out .= apply_filters('rdc_the_content', $content);
		$out .= "</div>";
	}
	
	return $out;
}

add_shortcode('col', 'rdc_col_screen');
function rdc_col_screen($atts, $content){
	
	return "<div class='rdc-col'>".apply_filters('rdc_the_content', $content)."</div>";
}

add_shortcode('newsletter_form', 'rdc_newsletter_form_screen');
function rdc_newsletter_form_screen($atts){
	
	return "<div class='newsletter-form'>".rdc_get_newsletter_form()."</div>";
}


/** 3 col block **/
add_shortcode('3col_section', 'rdc_3col_section_screen');
function rdc_3col_section_screen($atts, $content){
	
	extract(shortcode_atts(array(				
		'css'   => '',		
	), $atts));
	
	$out  = "<div class='col3-section {$css}'>".do_shortcode($content)."</div>";
	
	return $out;
}

add_shortcode('3col', 'rdc_3col_screen');
function rdc_3col_screen($atts, $content){
	
	extract(shortcode_atts(array(				
		'title'     => '',
		'link_url'  => '',
		'link_txt'  => '',
	), $atts));
	
	$target = (false !== strpos($link_url, home_url())) ? '' : ' target="_blank"'; //test this
	
	$out = "<div class='col3'><div class='col3-content'>";
	$out .= "<h4 class='col3-title'>{$title}</h4>";
	$out .= "<div class='col3-text'>".apply_filters('rdc_the_content', $content)."</div>";
	$out .= "<div class='col3-link'><a href='{$link_url}'{$target}>{$link_txt}</a></div></div></div>";
	
	return $out;
}

/** People gallery **/
add_shortcode('rdc_people_gallery', 'rdc_people_gallery_screen');
function rdc_people_gallery_screen($atts){
	
	extract(shortcode_atts(array(				
		'type'  => '',
		'num' => -1
	), $atts));
	
	$qargs = array(
		'post_type' => 'person',
		'posts_per_page' => $num		
	);
	
	if(!empty($type)){
		$qargs['tax_query'] = array(
			array(
				'taxonomy' => 'person_cat',
				'field' => 'slug',
				'terms' => $type
			)
		);
	}
	
	$query = new WP_Query($qargs);
	if(!$query->have_posts())
		return '';
	
	ob_start();
?>
<div class="cards-loop sm-cols-2 md-cols-3 lg-cols-4 exlg-cols-5 people-cards-shortcode">
<?php
	foreach($query->posts as $p){
		rdc_person_card($p, false);
	}
?>
</div>
<?php	
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Map **/
add_shortcode('pw_map', 'rdc_pw_map_screen');
function rdc_pw_map_screen($atts){
	
	extract(shortcode_atts(
		array(
			'address'           => false,
			'width'             => '100%',
			'height'            => '360px',
			'enablescrollwheel' => 'false',
			'zoom'              => 16,
			'disablecontrols'   => 'false',
			'v_shift' => 0,
			'h_shift' => 0
		),
		$atts
	));
		
	$coord = rdc_map_get_coordinates($address);	
	
	if( !is_array( $coord ) )
		return '';
	
	$map_id = uniqid( 'pw_map_' );
	$zoomControl = ($disablecontrols == 'false') ? 'true' : 'false';
	
	$center = array();
	$center['lat'] = (float)$coord['lat'] + (float)$v_shift;
	$center['lng'] = (float)$coord['lng'] + (float)$h_shift;
	
	ob_start();
?>
<div class="pw_map-wrap">
<div class="pw_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr($height); ?>; width: <?php echo esc_attr( $width); ?>"></div>
</div>
<script type="text/javascript">
	if (typeof mapFunc == "undefined") {
		var mapFunc = new Array();
	}	
	
	mapFunc.push(function (){
		
		var map = L.map('<?php echo $map_id ; ?>', {
			zoomControl: <?php echo $zoomControl;?>,
			scrollWheelZoom: <?php echo $enablescrollwheel;?>,
			center: [<?php echo $center['lat'];?>, <?php echo $center['lng'];?>],
			zoom: <?php echo $zoom;?>
		});

		//https://b.tile.openstreetmap.org/16/39617/20480.png
		//http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: 'Карта &copy; <a href="http://osm.org/copyright">Участники OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
			maxZoom: 24,
			minZoom: 3			
		}).addTo(map);
		
		var pin = L.divIcon({
			className: 'mymap-icon',
			iconSize: [36, 36],
			html: '<svg class="icon-marker"><use xlink:href="#icon-marker" /></svg>'
		});
		
		L.marker([<?php echo $coord['lat'];?>, <?php echo $coord['lng'];?>], { icon: pin }).addTo(map);
	});
		
</script>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

add_action('wp_footer', function(){
	
	$base = get_template_directory_uri().'/assets/img/';	
?>
<script type="text/javascript">
	L.Icon.Default.imagePath = '<?php echo $base; ?>';
	
	if (typeof mapFunc != "undefined") {
		for (var i = 0; i < mapFunc.length; i++) {
			mapFunc[i]();
		}
	}	
</script>
<?php
}, 100);

function rdc_map_get_coordinates( $address, $force_refresh = false ) {

    $address_hash = md5( $address );
    $coordinates = get_transient( $address_hash );

    if ($force_refresh || $coordinates === false) {

    	$args       = array( 'q' => urlencode( $address ), 'format' => 'json', 'limit' => 1 );
    	$url        = add_query_arg( $args, 'http://nominatim.openstreetmap.org/search/' );
     	$response 	= wp_remote_get( $url );
			
		
     	if( is_wp_error( $response ) )
     		return;

     	$data = wp_remote_retrieve_body( $response );

     	if( is_wp_error( $data ) )
     		return;
		
		
		if ( $response['response']['code'] == 200 ) {

			$data = json_decode( $data );

			if (isset($data[0]->lat)) {			  	

			  	$cache_value['lat'] = $data[0]->lat;
			  	$cache_value['lng'] = $data[0]->lon;
			  	$cache_value['address_name'] = (string) $data[0]->display_name;

			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			
			} else {
				return 'Неизвесная ошибка. Убедитесь что шорткод указан корректно';
			}

		} else {
		 	return 'Геокодер недоступен';
		}

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
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