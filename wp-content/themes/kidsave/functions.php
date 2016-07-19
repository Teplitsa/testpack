<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('FRL_VERSION', '1.0');
//define('FRL_NL_FORM', 2);
 
 
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}


function kds_setup() {

	// Inits
	load_theme_textdomain( 'kds', get_template_directory() . '/lang' );
	//add_theme_support( 'automatic-feed-links' );	
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// Thumbnails
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(640, 400, true ); // regular thumbnails	
	//add_image_size('mini', 222, 167, true ); // logo thumbnail 
	//add_image_size('poster', 220, 295, true ); // poster in widget	
	//add_image_size('embed', 735, 430, true ); // fixed size for embedding
	//add_image_size('cover', 400, 567, true ); // long thumbnail for pages

	// Menus
	$menus = array(
		'primary'   => 'Главное',		
		'social'    => 'Социальные кнопки',
		'secondary' => 'В подвале',
		'about'     => 'О нас'
	);
		
	register_nav_menus($menus);
	
	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'init', 'kds_setup', 30 );


/** Custom image size for medialib **/
//add_filter('image_size_names_choose', 'kds_medialib_custom_image_sizes');
function kds_medialib_custom_image_sizes($sizes) {
	
	$addsizes = apply_filters('kds_medialib_custom_image_sizes', array(
		//"thumbnail-landscape" => __("Landscape mini", 'kds'),
		"embed" => 'Фиксированный'
	));
		
	return array_merge($sizes, $addsizes);
}

/**
 * Register widget area.
 */
function kds_widgets_init() {
		
	$config = array(
		'right_top' => array(
						'name' => 'Правая колонка - Верх',
						'description' => 'Общая боковая колонка справа (верхняя часть, на мобильных - перед контентом)'
					),
		'right_bottom' => array(
						'name' => 'Правая колонка - Низ',
						'description' => 'Общая боковая колонка справа (верхняя часть, на мобильных - после контента)'
					),
	);
		
	foreach($config as $id => $sb) {
		
		$before = '<div id="%1$s" class="widget %2$s">';
		
		if(false !== strpos($id, 'footer')){
			$before = '<div id="%1$s" class="widget-bottom %2$s">';
		}		
		
		register_sidebar(array(
			'name' => $sb['name'],
			'id' => $id.'-sidebar',
			'description' => $sb['description'],
			'before_widget' => $before,
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}
}
add_action( 'init', 'kds_widgets_init', 25 );


/**
 * Includes
 */

require get_template_directory().'/inc/class-cssjs.php';

require get_template_directory().'/inc/aq_resizer.php';
require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/forms.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/request.php';
require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/social.php';
require get_template_directory().'/inc/template-tags.php';
require get_template_directory().'/inc/widgets.php';



if(is_admin()){
	require get_template_directory() . '/inc/admin.php';
	
}