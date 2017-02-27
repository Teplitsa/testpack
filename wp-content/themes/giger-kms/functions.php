<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('TST_VERSION', '1.0');
define('TST_DOC_URL', 'https://kms.te-st.ru/site-help/');


if ( ! isset( $content_width ) ) {
	$content_width = 770; /* pixels */
}


function tst_setup() {

	// Inits
	load_theme_textdomain( 'tst', get_template_directory() . '/lang' );
	//add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );


	// Menus
	$menus = array(
		'primary'   => 'Главное',
		'sitemap'   => 'Карта сайта',
		"footer_1"	=> 'Футер - 1',
		"footer_2"	=> 'Футер - 2',
		"footer_3"	=> 'Футер - 3'
	);

	register_nav_menus($menus);

	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'init', 'tst_setup', 30 );




/**
 * Register widget area.
 */
function tst_widgets_init() {

	$config = array(
		'footer_1' => array(
						'name' => 'Подвал - 1 колонка',
						'description' => 'Динамическая область в подвале: 1 колонка'
					),
		'footer_2' => array(
						'name' => 'Подвал - 2 колонка',
						'description' => 'Динамическая область в подвале: 2 колонка'
					),
		'footer_3' => array(
						'name' => 'Подвал - 3 колонка',
						'description' => 'Динамическая область в подвале: 3 колонка'
					),
	);

	foreach($config as $id => $sb) {

		$before = '<div id="%1$s" class="widget %2$s">';

		//if(false !== strpos($id, 'footer')){
		//	$before = '<div id="%1$s" class="widget-bottom %2$s">';
		//}

		register_sidebar(array(
			'name' => $sb['name'],
			'id' => $id.'-sidebar',
			'description' => $sb['description'],
			'before_widget' => $before,
			'after_widget' => '</div>',
			'before_title' => '<div class="widget__title">',
			'after_title' => '</div>',
		));
	}
}
//add_action( 'init', 'tst_widgets_init', 25 );


/**
 * Includes
 */

require get_template_directory().'/inc/caching.php';
require get_template_directory().'/inc/class-event.php';
require get_template_directory().'/inc/class-about.php';
require get_template_directory().'/inc/class-cssjs.php';
require get_template_directory().'/inc/class-mediamnt.php';
require get_template_directory().'/inc/class-section.php';
require get_template_directory().'/inc/class-import.php';
require get_template_directory().'/inc/class-part-colors.php';

require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/pagebuilder.php'; //temp

require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/request.php';
require get_template_directory().'/inc/markers-map.php';
require get_template_directory().'/inc/navs.php';

//require get_template_directory().'/inc/donations.php';
require get_template_directory().'/inc/forms.php';

require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/social.php';
require get_template_directory().'/inc/widgets.php';

require get_template_directory().'/inc/templates-thumbnails.php';
require get_template_directory().'/inc/templates-micro.php';
require get_template_directory().'/inc/templates-single.php';
require get_template_directory().'/inc/templates-tags.php';

if(is_admin()){
	require get_template_directory() . '/admin/general.php';
	require get_template_directory() . '/admin/post-hooks.php';
}
