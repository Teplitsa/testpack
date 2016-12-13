<?php
/** Thumbnails */

add_action( 'init', 'tst_thumbnails_setup', 35);
function tst_thumbnails_setup() {

	// Thumbnails
	add_theme_support('post-thumbnails');

	//special cases
	//add_image_size('nl_thumb', 150, 94, true ); //thumbnail for newsletter
	//add_image_size('vcard', 320, 320, true );
	//add_image_size('cover', 220, 312, true );
	//add_image_size('feature', 788, 520, true );

	//thumbnail in cards
	add_image_size('thumbnail-small-fixed', 320, 198, true );
	add_image_size('thumbnail-small-flex', 320, 999, false );
	add_image_size('thumbnail-medium-fixed', 500, 247, true );
	add_image_size('thumbnail-medium-flex', 500, 999, false );
	add_image_size('thumbnail-large-fixed', 640, 395, true );
	//add_image_size('thumbnail-medium-flex', 640, 999, true ); == large default size
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


/** == Responsive system == **/

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


/** == Video and gallery helpders == **/

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


/** Find youtube video URL and return HTML **/
function tst_get_youtube_html(WP_Post $cpost, $mode = 'player'){
	global $wp_embed;

	$url = '';

	if(preg_match('/youtube\.com/', $cpost->post_content) || preg_match('/youtu\.be/', $cpost->post_content)) {
		preg_match_all('/(http:|https:)?\/\/(www\.)?(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/', $cpost->post_content, $urls_match_1);
		preg_match_all('/(http:|https:)?\/\/(www\.)?(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?["\']/', $cpost->post_content, $urls_match_2);

		if(!isset($urls_match_2[0][0]) && isset($urls_match_1[0][0])) {
			$url = $urls_match_1[0][0];
			$url = str_replace('[/embed]', '', $url);
		}
		elseif(isset($urls_match_2[0][0])) {
			$url = trim(str_replace(array("'", '"'), '', $urls_match_2[0][0]));
		}

	}

	if(empty($url))
		return '';

	$player = $wp_embed->autoembed($wp_embed->run_shortcode($url));

	if($mode == 'player'){
		return $player;

	}
	elseif($mode == 'preload_url') {
		//build url for preloader
		preg_match('/(src=["\'](.*?)["\'])/', $player, $match);

		if(isset($match[2])) {
			$src = parse_url($match[2]);

			if(isset($src['path']))
				$url = trailingslashit('//www.youtube.com'.$src['path']);

		}

		return $url;
	}
}

/* save youtube thumbnails into medialib **/
function tst_localize_youtube_thumbnail(WP_Post $cpost) {

	$raw_url = $video_id = "";
	$img_id = false;


	$url = tst_get_youtube_html($cpost, 'preload_url');
	$url = explode('/embed/', untrailingslashit($url));

	if(isset($url[1])){
		$video_id = $url[1];
		if(false !== strpos($video_id, 'videoseries') || false !== strpos($video_id, 'list'))
			$video_id = '';

		if(!empty($video_id))
			$raw_url = "https://img.youtube.com/vi/{$video_id}/0.jpg";
	}

	if(empty($raw_url))
		return false;

	//upload file
	$file = wp_remote_get($raw_url, array('timeout' => 50, 'sslverify' => false));

	if(!is_wp_error($file) && isset($file['body'])){
		if(isset($file['headers']['content-type']) && false !== strpos($file['headers']['content-type'], 'image')) {

			$filename = 'youtube-thumb-'.$video_id.'.jpg';
			$upload_file = wp_upload_bits($filename, null, $file['body']);

			if (!$upload_file['error']) {
				$wp_filetype = wp_check_filetype($filename, null );

				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_parent' => 0,
					'post_title' => 'Видео заставка для записи '.$cpost->ID,
					'post_content' => '',
					'post_status' => 'inherit',
					'post_parent' => (int)$cpost->ID
				);

				$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

				if (!is_wp_error($attachment_id)) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id,  $attachment_data );
				}

				$img_id = (int)$attachment_id;
			}
		}
	}

	if(!$img_id)
		return false;

	//regenerate thumbnails
	$media = TST_Media::get_instance();
	$media->regenerate_thumbnails($img_id);

	//store as meta for post
	update_post_meta($cpost->ID, '_youtube_thumbnail_id', $img_id);

	return $img_id;
}

function tst_get_post_youtube_thumbnail_picture($cpost, $thumb_args = array()) {

	$defaults = array(
		'placement_type'	=> 'medium-small-small-small-small',
		'aspect_ratio' 		=> 'video', //square, video, cover
		'crop' 				=> 'fixed' //flex
	);

	$args = wp_parse_args($args, $defaults);
	extract($args);

	//test for cache
	$thumbs = get_post_meta($cpost->ID, 'post_video_thumbnail_markup_lazy', true);
	$thumb_key = "{$placement_type}_{$aspect_ratio}_{$crop}";
	if(isset($thumbs[$thumb_key]))
		return $thumbs[$thumb_key];

	//agrs
	$css = "{$aspect_ratio} {$crop}";
	$thumbnail_id = get_post_meta($cpost->ID, '_youtube_thumbnail_id', true);

	if(!$thumbnail_id) { //may be we need to localize it
		$thumbnail_id = tst_localize_youtube_thumbnail($cpost);
	}

	if(!$thumbnail_id) { //fallbak to thumbnail
		do_action('tst_before_get_post_thumbnail', $cpost->ID, 'feature');

		$thumbnail_id = get_post_thumbnail_id($cpost);
	}

	ob_start();
?>
	<div class="tst-thumbnail tst-thumbnail--videopost <?php echo esc_attr($css);?>">
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

	$thumbs[$thumb_key] = $out;
	update_post_meta($cpost->ID, 'post_video_thumbnail_markup_lazy', $thumbs);

	return $out;
}


function tst_get_youtube_thumbnail_url(WP_Post $cpost){

	$img = "";

	$url = tst_get_youtube_html($cpost, 'preload_url');
	$url = explode('/embed/', untrailingslashit($url));

	if(isset($url[1])){
		$img = $url[1];
		if(false !== strpos($img, 'videoseries') || false !== strpos($img, 'list'))
			$img = '';
	}

	if(!empty($img))
		$img = "https://img.youtube.com/vi/{$img}/0.jpg";

	//may be test for existence

	return $img;
}
