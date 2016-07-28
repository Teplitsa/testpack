<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('FRL_VERSION', '1.0');
define('TST_DOC_URL', 'https://kms.te-st.ru/site-help/');
 
 
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}


function rdc_setup() {

	// Inits
	load_theme_textdomain( 'rdc', get_template_directory() . '/lang' );
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
	//add_image_size('embed', 735, 430, true ); // fixed size for embedding
	//add_image_size('cover', 400, 567, true ); // long thumbnail for pages

	// Menus
	$menus = array(
		'primary'   => 'Главное',		
		'social'    => 'Социальные кнопки',
		'sitemap'   => 'Карта сайта'
	);
		
	register_nav_menus($menus);
	
	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'init', 'rdc_setup', 30 );


/** Custom image size for medialib **/
add_filter('image_size_names_choose', 'tst_medialib_custom_image_sizes');
function tst_medialib_custom_image_sizes($sizes) {
	
	$addsizes = array(
		"medium-thumbnail" => 'Горизонтальный',
		"square" => 'Квадратный'
	);
		
	return array_merge($sizes, $addsizes);
}

/**
 * Register widget area.
 */
function rdc_widgets_init() {
		
	$config = array(		
		'right_single' => array(
						'name' => 'Правая колонка - Записи',
						'description' => 'Боковая колонка справа на страницах новостей'
					),
		'right_event' => array(
						'name' => 'Правая колонка - Анонсы',
						'description' => 'Боковая колонка справа на страницах анонсов'
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
	
	if ( function_exists('p2p_register_connection_type') ) {
        p2p_register_connection_type ( array (
            'name' => 'children-projects',
            'from' => 'leyka_campaign',
            'to' => 'leyka_campaign',
            'cardinality' => 'many-to-many',
            'admin_dropdown' => 'any',
//             'title' => array (
//                 'from' => __ ( 'Child', 'rdc' ),
//                 'to' => __ ( 'Project', 'rdc' ) 
//             ),
//             'from_labels' => array (
//                 'singular_name' => __ ( 'Project', 'rdc' ),
//                 'search_items' => __ ( 'Search project', 'rdc' ),
//                 'not_found' => __ ( 'No projects found.', 'rdc' ),
//                 'create' => __ ( 'Choose project', 'rdc' ) 
//             ),
//             'to_labels' => array (
//                 'singular_name' => __ ( 'Children', 'rdc' ),
//                 'search_items' => __ ( 'Search children', 'rdc' ),
//                 'not_found' => __ ( 'No children found.', 'rdc' ),
//                 'create' => __ ( 'Choose children', 'rdc' ) 
//             ),
            'title' => array (
                'from' => 'Дети',
                'to' => 'Проект или программа'
            ),
            'from_labels' => array (
                    'singular_name' => 'Проект или программа',
                    'search_items' => 'Искать',
                    'not_found' => 'Ничего не найдено.',
                    'create' => 'Выбрать',
                ),
                'to_labels' => array (
                    'singular_name' => 'Дети',
                    'search_items' => 'Искать детей',
                    'not_found' => 'Дети не найдены.',
                    'create' => 'Выбрать детей',
                ),
                'admin_column' => true 
            ) );
	}
}
add_action( 'init', 'rdc_widgets_init', 25 );

function get_leyka_compaign_type($compaign) {
    $terms = wp_get_post_terms( $compaign->ID, 'campaign_cat' );
    
    $type = '';
    if(!is_wp_error($terms)) {
        foreach($terms as $term) {
            if(in_array($term->slug, array('children', 'you-helped', 'need-help', 'rosemary'))) {
                $type = 'child';
            }
            elseif(in_array($term->slug, array('projects'))) {
                $type = 'project';
            }
            elseif(in_array($term->slug, array('programms'))) {
                $type = 'program';
            }
        
        }
    }
    
    return $type;
}

function restrict_p2p_box_display( $show, $ctype, $post ) {
    if ( 'children-projects' == $ctype->name ) {
        $show = false;
        if ( 'to' == $ctype->get_direction() ) {
            $compaign_type = get_leyka_compaign_type($post);
            if($compaign_type == 'child') {
                $show = true;
            }
        }
    }
    
    return $show;
}
add_filter( 'p2p_admin_box_show', 'restrict_p2p_box_display', 10, 3 );

function order_pages_by_title( $args, $ctype, $post_id ) {

    if ( 'children-projects' == $ctype->name ) {
        $term_list = wp_get_post_terms($post_id, 'wpsc_product_category', array("fields" => "ids"));
        $args['p2p:per_page'] = '20';
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'campaign_cat',
                'field'    => 'slug',
                'terms'    => array('projects', 'programms'),
            )
        );
    }

    return $args;
}
add_filter( 'p2p_connectable_args', 'order_pages_by_title', 10, 3 );

/**
 * Includes
 */

require get_template_directory().'/inc/class-cssjs.php';
//require get_template_directory().'/inc/class-event.php';

require get_template_directory().'/inc/aq_resizer.php';
require get_template_directory().'/inc/author.php';
require get_template_directory().'/inc/cards.php';
require get_template_directory().'/inc/donations.php';
//require get_template_directory().'/inc/events.php';
require get_template_directory().'/inc/extras.php';
require get_template_directory().'/inc/forms.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/related.php';
require get_template_directory().'/inc/request.php';
require get_template_directory().'/inc/shortcodes.php';
require get_template_directory().'/inc/social.php';
require get_template_directory().'/inc/template-tags.php';
require get_template_directory().'/inc/widgets.php';

require get_template_directory().'/inc/import-campaigns.php';
require get_template_directory().'/inc/import-posts.php';
require get_template_directory().'/inc/import-donations.php';



if(is_admin()){
	require get_template_directory() . '/inc/admin.php';
	
}

