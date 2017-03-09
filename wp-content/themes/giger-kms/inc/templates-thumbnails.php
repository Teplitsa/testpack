<?php
/** Thumbnails */

add_action( 'init', 'tst_thumbnails_setup', 35);
function tst_thumbnails_setup() {

	// Thumbnails
	add_theme_support('post-thumbnails');

	//sizes for landing blocks
	//add_image_size('block-full', 1192, 482, true );
	//add_image_size('block-2col', 596, 482, true );
	//add_image_size('block-3col', 894, 482, true );
	//add_image_size('block-small', 298, 298, true );
	add_image_size('block-single', 894, 596, true );


	//new sizes
	add_image_size('mobile-common', 400, 250, true ); //aspect 1.618
	add_image_size('small-long', 700, 376, true ); //aspect 1.855
	add_image_size('small-square', 298, 298, true ); //aspect 1
	add_image_size('3col', 894, 482, true ); //aspect 1.855 - limit for 3 col pictures
	add_image_size('2col', 596, 482, true ); // aspect 1.236 - limit for 2 col pictures
	add_image_size('4col', 1192, 482, true ); // aspect 2.473 - limit for 4col pictures
}

/** Custom image size for medialib **/
//add_filter('image_size_names_choose', 'tst_medialib_custom_image_sizes');
function tst_medialib_custom_image_sizes($sizes) {

	$sizes = array(
		'thumbnail' 				=> 'Мин. квадратная',
		"thumbnail-small-fixed"		=> 'Мин. маленькая',
		'thumbnail-medium-fixed'	=> 'Мин. горизонтальная',
		'medium'					=> 'Средний',
		'feature'					=> 'Фиксированный',
		'full'						=> 'Полный'
	);

	return $sizes;
}

/* Config for picture places */
function tst_get_image_place_config($place_id) {

	$config = array(
		'block-1col' => array(
			'mobile' 	=> array('size' => 'mobile-common', 'css' => 'aspect-62'),
			'small' 	=> array('size' => 'small-square', 'css' => 'aspect-100'),
			'medium' 	=> array('size' => 'small-square', 'css' => 'aspect-100'),
			'large' 	=> array('size' => 'small-square', 'css' => 'aspect-100'),
			'base'	 	=> array('size' => 'small-square', 'css' => ''),
		),
		'block-2col' => array(
			'mobile' 	=> array('size' => 'mobile-common', 'css' => 'aspect-62'),
			'small' 	=> array('size' => 'small-long', 'css' => 'aspect-54'),
			'medium' 	=> array('size' => '3col', 'css' => 'aspect-54'),
			'large' 	=> array('size' => '2col', 'css' => 'cover-482'),
			'base'	 	=> array('size' => '2col', 'css' => ''),
		),
		'block-3col' => array(
			'mobile' 	=> array('size' => 'mobile-common', 'css' => 'aspect-62'),
			'small' 	=> array('size' => 'small-long', 'css' => 'aspect-54'),
			'medium' 	=> array('size' => '3col', 'css' => 'aspect-54'),
			'large' 	=> array('size' => '3col', 'css' => 'cover-482'),
			'base'	 	=> array('size' => 'small-long', 'css' => ''),
		),
		'block-4col' => array(
			'mobile' 	=> array('size' => 'mobile-common', 'css' => 'aspect-62'),
			'small' 	=> array('size' => 'small-long', 'css' => 'aspect-54'),
			'medium' 	=> array('size' => '3col', 'css' => 'aspect-54'),
			'large' 	=> array('size' => '4col', 'css' => 'aspect-40'),
			'base'	 	=> array('size' => 'small-long', 'css' => ''),
		)
	);

	if(isset($config[$place_id])){
		return $config[$place_id];
	}

	return false;
}





/** == Responsive system NEWS == **/
function tst_get_picture_markup($img_id, $place_id) {

	$config = tst_get_image_place_config($place_id);
	if(!$config)
		return;

	$sources = array();
	$classes = array($place_id);
	$base_src = '';

	foreach($config as $key => $obj) {

		if($key == 'base') {
			$base_src = ($img_id) ? wp_get_attachment_image_src($img_id, $obj['size']) : false;
			if($base_src)
				$base_src = $base_src[0];

			$classes[] = $obj['css'];
		}
		else {
			$mq = '';
			switch($key) {
				case 'mobile':
					$mq = '(max-width: 480px)'; //mobile
					break;

				case 'small':
					$mq = '(min-width: 481px) and (max-width: 767px)'; //small
					break;

				case 'medium':
					$mq = '(min-width: 768px) and (max-width: 1023px)'; // medium
					break;

				case 'large':
					$mq = '(min-width: 1024px)'; //large
					break;
			}

			$img_src = ($img_id) ? wp_get_attachment_image_src($img_id, $obj['size']) : false;

			if($img_src && !empty($mq)){
				$sources[$mq] = $img_src[0].' '.$img_src[1].'w';
			}

			$classes[] = $key.'-'.$obj['css'];
		}
	}

	ob_start();
?>
<div class="tst-picture <?php echo implode(' ', $classes);?>"><div class="tst-picture__frame">
<?php if(!empty($sources)) { ?>
	<picture class="tst-picture__image">
		<!-- sources -->
		<?php if(!empty($sources)) { foreach($sources as $media_query => $src) { ?>
			<source data-srcset="<?php echo esc_attr($src);?>" media="<?php echo esc_attr($media_query);?>"></source>
		<?php }} ?>

		<!-- fallback -->
		<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
			 data-src="<?php echo $base_src;?>"
			 class="lazyload">
			<noscript><img src="<?php echo $base_src;?>"></noscript>
	</picture>
<?php } else { ?>
	<div class="tst-picture__noimage"></div>
<?php } ?>
</div></div>
<?php
	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}


function tst_get_the_post_thumbnail($cpost, $size) {

	$thumbnail_id = get_post_thumbnail_id($cpost);

	if($thumbnail_id)
		do_action('tst_before_display_attachment', $thumbnail_id, 'small-square');

	return tst_get_picture_markup($thumbnail_id, $size);
}


/** == Responsive system OLD == **/

function tst_get_post_thumbnail_picture(WP_Post $cpost, $args = array()) {

	$defaults = array(
		'placement_type'	=> 'medium-medium-medium-medium-medium',
		'aspect_ratio' 		=> 'standard', //square, cover
		'crop' 				=> 'fixed' //flex
	);

	$args = wp_parse_args($args, $defaults);
	extract($args);

	do_action('tst_before_get_post_thumbnail', $cpost->ID, 'feature');

	//test for cache
	//$thumbs = get_post_meta($cpost->ID, 'post_thumbnail_markup_lazy', true);
	//$thumb_key = "{$placement_type}_{$aspect_ratio}_{$crop}";
	//if(isset($thumbs[$thumb_key]))
	//	return $thumbs[$thumb_key];


	$css = "{$aspect_ratio} {$crop}";
	$thumbnail_id = get_post_thumbnail_id($cpost);

	ob_start();
?>
	<div class="tst-thumbnail <?php echo esc_attr($css);?>">
		<div class="tst-thumbnail__frame">
		<?php
			if($thumbnail_id) {
				$sources = tst_get_picture_sources($thumbnail_id, $placement_type, $crop);
				$base = wp_get_attachment_image_src($thumbnail_id, $sources['base_size']);
		?>
			<picture class="tst-thumbnail__picture">
				<!-- sources -->
				<?php if(!empty($sources)) { foreach($sources as $media_query => $src) { ?>
					<source data-srcset="<?php echo esc_attr($src);?>" media="<?php echo esc_attr($media_query);?>"></source>
				<?php }} ?>
				<!-- fallback -->
				<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
					 data-src="<?php echo $base[0];?>"
					 class="lazyload">
					<noscript><img src="<?php echo $base[0];?>"></noscript>
			</picture>

		<?php } else { ?>
			<div class="tst-thumbnail__nopicture"></div>

		<?php } ?>
		</div>
	</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();

	//$thumbs[$thumb_key] = $out;
	//update_post_meta($cpost->ID, 'post_thumbnail_markup_lazy', $thumbs);

	return $out;
}

function tst_get_picture_sources($attachment_id, $placement_type = '', $crop = 'fixed') {

	$sources = array();

	$sizes = array_map('trim', explode('-', $placement_type));
	if(count($sizes) != 5)
		return $sources; //illegal type

	foreach($sizes as $i => $size_type) {

		$size_name = ($size_type != 'square_mini') ? "thumbnail-{$size_type}-{$crop}" : 'thumbnail';
		if($size_name == 'thumbnail-medium-flex')
			$size_name = 'large';

		if($size_type == 'feature')
			$size_name = 'feature';

		$img_src = wp_get_attachment_image_src($attachment_id, $size_name);

		//mq
		switch($i) {
			case 0:
				$mq = '(max-width: 480px)'; //mobile
				break;

			case 1:
				$mq = '(min-width: 481px) and (max-width: 779px)'; //small
				break;

			case 2:
				$mq = '(min-width: 780px) and (max-width: 1023px)'; // medium
				break;

			case 3:
				$mq = '(min-width: 1024px) and (max-width: 1199px)'; //large
				break;

			case 4:
				$mq = '(min-width: 1200px)'; //exlarge
				break;
		}


		if($img_src){
			$sources[$mq] = $img_src[0].' '.$img_src[1].'w';
		}
	}

	//base_size
	$test = $sizes[2];
	switch($test) {
		case 'square_mini':
			$sources['base_size'] = 'thumbnail';
			break;

		case 'mini':
		case 'small':
			$sources['base_size'] = 'thumbnail-small-'.$crop;
			break;

		default:
			$sources['base_size'] = 'thumbnail-medium-'.$crop;
			break;
	}

	return $sources;
}


/** == Helpers == **/
function tst_get_post_thumbnail_cation(WP_Post $cpost) {

	$thumb = get_post(get_post_thumbnail_id($cpost->ID));
	if(!$thumb)
		return '';


	return tst_get_image_caption($thumb);
}

function tst_get_image_caption(WP_Post $attachment){

	$cap = (!empty($attachment->post_excerpt)) ? $attachment->post_excerpt : '';
	$cap .= (!empty($cap) && !empty($attachment->post_content)) ? '. ' : '';
	$cap .= (!empty($attachment->post_content)) ? $attachment->post_content : '';

	if(empty($cap))
		$cap = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

	if(mb_strlen($cap) > 140) {
		$cap = wp_trim_words($cap, 12);
	}

	return $cap;
}






/** == Gallery helpders == **/

/** find gallery shortcode in content and return array of images from it **/
function tst_get_gallery_images(WP_Post $cpost){

	$ids = array();

	//backward compat
	$gallery = get_post_meta($cpost->ID, 'format_gallery', true);
	if(!empty($gallery))
		$cpost->post_content .= ' '.$gallery;


	if(preg_match_all( '/' . get_shortcode_regex() . '/s', $cpost->post_content, $matches, PREG_SET_ORDER )) {
		foreach ( $matches as $shortcode ) {
			if ( 'gallery' === $shortcode[2] ) {
				$data = shortcode_parse_atts( $shortcode[3]);
				if(!isset($data['ids']))
					continue;

				$data = array_map('intval', explode(',', $data['ids']));
				$ids = array_merge($data, $ids);
			}
		}
	}

	return $ids;
}

