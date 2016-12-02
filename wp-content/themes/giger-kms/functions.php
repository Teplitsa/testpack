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

	// Thumbnails
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(640, 395, true ); // regular thumbnails
	add_image_size('square', 450, 450, true ); // square thumbnail
	add_image_size('medium-thumbnail', 790, 488, true ); // poster in widget
	add_image_size('small-thumbnail', 320, 198, true ); // fixed size for embedding
	//add_image_size('cover', 400, 567, true ); // long thumbnail for pages

	// Menus
	$menus = array(
		'primary'   => 'Главное',
		'social'    => 'Социальные кнопки',
		'sitemap'   => 'Карта сайта',
		'news'   	=> 'Рубрики новостей',
	);

	register_nav_menus($menus);

	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'init', 'tst_setup', 30 );


/** Custom image size for medialib **/
add_filter('image_size_names_choose', 'tst_medialib_custom_image_sizes');
function tst_medialib_custom_image_sizes($sizes) {

	$addsizes = array(
		"small-thumbnail" 	=> 'Горизонтальная миниатюра',
		"post-thumbnail" 	=> 'Горизонтальный средний',
		"square" 			=> 'Квадратный'
	);

	return array_merge($sizes, $addsizes);
}

/**
 * Register widget area.
 */
function tst_widgets_init() {

	$config = array(
		'right_single' => array(
						'name' => 'Правая колонка - Записи',
						'description' => 'Боковая колонка справа на страницах новостей'
					),
		'footer' => array(
						'name' => 'Подвал - 4 виджета',
						'description' => 'Динамическая область в подвале: 4 виджета'
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
add_action( 'init', 'tst_widgets_init', 25 );

function tst_formidable_frm_scroll_offset() {
    $offset = (int)get_theme_mod('auto_scroll_offset');

    if(!$offset) {
        $offset = 70;
    }

    if(is_user_logged_in()) {
        $offset += 32;
    }
    return $offset;
}
add_filter('frm_scroll_offset', 'tst_formidable_frm_scroll_offset');

/**
 * Includes
 */

require get_template_directory().'/inc/class-cssjs.php';

require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/request.php';

//require get_template_directory().'/inc/cards.php';
//require get_template_directory().'/inc/donations.php';
//require get_template_directory().'/inc/forms.php';
//require get_template_directory().'/inc/shortcodes.php';

require get_template_directory().'/inc/social.php';
require get_template_directory().'/inc/template-tags.php';
//require get_template_directory().'/inc/widgets.php';


if(is_admin()){
	require get_template_directory() . '/inc/admin.php';

}

/** Cron **/
//add_action( 'wp', 'tst_cron_job' );
function tst_cron_job() {

	if (!wp_next_scheduled( 'tst_daily_events')) {
		wp_schedule_event( strtotime('today midnight'), 'daily', 'tst_daily_events' );
	}
}