<?php

add_action('init', 'tst_custom_content', 20);
function tst_custom_content(){
	
	if(defined('TST_HAS_AUTHORS') && TST_HAS_AUTHORS) {
		register_taxonomy('auctor', array('post',), array(
			'labels' => array(
				'name'                       => 'Авторы',
				'singular_name'              => 'Автор',
				'menu_name'                  => 'Авторы',
				'all_items'                  => 'Все авторы',
				'edit_item'                  => 'Редактировать автора',
				'view_item'                  => 'Просмотреть',
				'update_item'                => 'Обновить автора',
				'add_new_item'               => 'Добавить нового автора',
				'new_item_name'              => 'Название нового автора',
				'parent_item'                => 'Родительский автор',
				'parent_item_colon'          => 'Родительский автор:',            
				'search_items'               => 'Искать авторов',
				'popular_items'              => 'Часто используемые',
				'separate_items_with_commas' => 'Разделять запятыми',
				'add_or_remove_items'        => 'Добавить или удалить авторов',
				'choose_from_most_used'      => 'Выбрать из часто используемых',
				'not_found'                  => 'Не найдено'
			),
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array('slug' => 'auctor', 'with_front' => false),
			//'update_count_callback' => '',        
		));
	}
	
	register_taxonomy('org_cat', array('org',), array(
		'labels' => array(
			'name'                       => 'Категории организаций',
			'singular_name'              => 'Категория',
			'menu_name'                  => 'Категории',
			'all_items'                  => 'Все категории',
			'edit_item'                  => 'Редактировать категорию',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить категорию',
			'add_new_item'               => 'Добавить новую категорию',
			'new_item_name'              => 'Название новой категории',
			'parent_item'                => 'Родительская категория',
			'parent_item_colon'          => 'Родительская категория:',            
			'search_items'               => 'Искать категории',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить категории',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'public' => false,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'orgs', 'with_front' => false),
		//'update_count_callback' => '',        
	));
	
	
	register_taxonomy('person_cat', array('person',), array(
		'labels' => array(
			'name'                       => 'Категории персон',
			'singular_name'              => 'Категория',
			'menu_name'                  => 'Категории',
			'all_items'                  => 'Все категории',
			'edit_item'                  => 'Редактировать категорию',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить категорию',
			'add_new_item'               => 'Добавить новую категорию',
			'new_item_name'              => 'Название новой категории',
			'parent_item'                => 'Родительская категория',
			'parent_item_colon'          => 'Родительская категория:',            
			'search_items'               => 'Искать категории',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить категории',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'public' => false,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'people', 'with_front' => false),
		//'update_count_callback' => '',        
	));
	
	
	
	/** Post types **/
	/*register_post_type('project', array(
        'labels' => array(
            'name'               => 'Программы',
            'singular_name'      => 'Программа',
            'menu_name'          => 'Программы',
            'name_admin_bar'     => 'Добавить программу',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить программу',
            'new_item'           => 'Новая программа',
            'edit_item'          => 'Редактировать программу',
            'view_item'          => 'Просмотр программ',
            'all_items'          => 'Все программы',
            'search_items'       => 'Искать программы',
            'parent_item_colon'  => 'Родительская программа:',
            'not_found'          => 'Программы не найдены',
            'not_found_in_trash' => 'В Корзине программы не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => 'projects',
        'rewrite'             => array('slug' => 'project', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));
	
	register_post_type('event', array(
        'labels' => array(
            'name'               => 'События',
            'singular_name'      => 'Событие',
            'menu_name'          => 'События',
            'name_admin_bar'     => 'Добавить событие',
            'add_new'            => 'Добавить новое',
            'add_new_item'       => 'Добавить событие',
            'new_item'           => 'Новое событие',
            'edit_item'          => 'Редактировать событие',
            'view_item'          => 'Просмотр события',
            'all_items'          => 'Все события',
            'search_items'       => 'Искать события',
            'parent_item_colon'  => 'Родительское событие:',
            'not_found'          => 'События не найдены',
            'not_found_in_trash' => 'В Корзине события не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'event', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'page-attributes'),
        'taxonomies'          => array(),
    ));*/
	
	register_post_type('org', array(
        'labels' => array(
            'name'               => 'Организации',
            'singular_name'      => 'Организация',
            'menu_name'          => 'Партнеры',
            'name_admin_bar'     => 'Добавить организацию',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить организацию',
            'new_item'           => 'Новая организация',
            'edit_item'          => 'Редактировать организацию',
            'view_item'          => 'Просмотр организации',
            'all_items'          => 'Все организации',
            'search_items'       => 'Искать организации',
            'parent_item_colon'  => 'Родительская организация:',
            'not_found'          => 'Организации не найдены',
            'not_found_in_trash' => 'В Корзине организации не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'org', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 10,
		'menu_icon'           => 'dashicons-networking',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array('org_cat'),
    ));
	
	register_post_type('person', array(
        'labels' => array(
            'name'               => 'Профили людей',
            'singular_name'      => 'Профиль',
            'menu_name'          => 'Люди',
            'name_admin_bar'     => 'Добавить профиль',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить профиль',
            'new_item'           => 'Новый профиль',
            'edit_item'          => 'Редактировать профиль',
            'view_item'          => 'Просмотр профиля',
            'all_items'          => 'Все профили',
            'search_items'       => 'Искать профили',
            'parent_item_colon'  => 'Родительский профиль:',
            'not_found'          => 'Профили не найдены',
            'not_found_in_trash' => 'В Корзине профили не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'profile', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 10,
		'menu_icon'           => 'dashicons-groups',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array('person_cat'),
    ));
	
	//pages
	add_post_type_support('page', 'excerpt');
	add_post_type_support('page', 'thumbnail');
		
	//remove post tags
	unregister_taxonomy_for_object_type('post_tag', 'post');
	
	
	register_post_type('quote', array(
        'labels' => array(
            'name'               => 'Цитаты',
            'singular_name'      => 'Цитата',
            'menu_name'          => 'Цитаты',
            'name_admin_bar'     => 'Добавить цитату',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить цитату',
            'new_item'           => 'Новая цитата',
            'edit_item'          => 'Редактировать цитату',
            'view_item'          => 'Просмотр цитаты',
            'all_items'          => 'Все цитаты',
            'search_items'       => 'Искать цитаты',
            'parent_item_colon'  => 'Родительская цитата:',
            'not_found'          => 'Цитаты не найдены',
            'not_found_in_trash' => 'В Корзине цитаты не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'quotation', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));

	
	register_taxonomy('campaign_cat', array('leyka_campaign',), array(
		'labels' => array(
			'name'                       => 'Категории кампаний',
			'singular_name'              => 'Категория',
			'menu_name'                  => 'Категории',
			'all_items'                  => 'Все категории',
			'edit_item'                  => 'Редактировать категорию',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить категорию',
			'add_new_item'               => 'Добавить новую категорию',
			'new_item_name'              => 'Название новой категории',
			'parent_item'                => 'Родительская категория',
			'parent_item_colon'          => 'Родительская категория:',            
			'search_items'               => 'Искать категории',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить категории',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'work', 'with_front' => false),
		//'update_count_callback' => '',        
	));
	
	//Markers
	register_post_type('marker', array(
        'labels' => array(
			'name'               => 'Маркеры',
			'singular_name'      => 'Маркер',
			'menu_name'          => 'Маркеры',
			'name_admin_bar'     => 'Добавить маркер',
			'add_new'            => 'Добавить новый',
			'add_new_item'       => 'Добавить маркер',
			'new_item'           => 'Новый маркер',
			'edit_item'          => 'Редактировать маркер',
			'view_item'          => 'Просмотр маркеров',
			'all_items'          => 'Все маркеры',
			'search_items'       => 'Искать маркер',
			'parent_item_colon'  => 'Родительский маркер:',
			'not_found'          => 'Маркеры не найдены',
			'not_found_in_trash' => 'В Корзине маркеры не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'marker', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 20,
		'menu_icon'           => 'dashicons-location',
        'supports'            => array('title', 'editor', 'thumbnail'),
        'taxonomies'          => array('marker_cat'),
    ));
	
	register_taxonomy('marker_cat', array('marker',), array(
		'labels' => array(
			'name'                       => 'Группы маркеров',
			'singular_name'              => 'Группа',
			'menu_name'                  => 'Группы',
			'all_items'                  => 'Все группы',
			'edit_item'                  => 'Редактировать группу',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить группу',
			'add_new_item'               => 'Добавить новую группу',
			'new_item_name'              => 'Название новой группы',
			'parent_item'                => 'Родительская группа',
			'parent_item_colon'          => 'Родительская группа:',            
			'search_items'               => 'Искать группы',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить группы',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'public' => false,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'layer', 'with_front' => false),
		//'update_count_callback' => '',        
	));
	
	
}






/** Metaboxes **/
add_action( 'cmb2_admin_init', 'tst_custom_metaboxes' );
function tst_custom_metaboxes() {
	
	
	/** Post **/
    $format_cmb = new_cmb2_box( array(
        'id'            => 'post_format_metabox',
        'title'         => 'Настройки формата',
        'object_types'  => array( 'post'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',		
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	
	
	//format
	$format_cmb->add_field( array(
		'name'             => 'Формат',
		'desc'             => 'Укажите формат публикации',
		'id'               => 'post_format',
		'type'             => 'select',
		'show_option_none' => false,
		'default'          => 'standard',
		'options'          => array(
			'standard' => 'Стандартный',			
			'introimg' => 'Заставка',
			'introvid' => 'Видео-заставка',
			'nointro'  => 'Без заставки',
		),
	));
	
	//$format_cmb->add_field( array(
	//	'name'    => 'Изображение заставки',
	//	'desc'    => 'Загрузить изображение для заставки',
	//	'id'      => 'megahead_image',
	//	'type'    => 'file',
	//	'options' => array(
	//		'url' => false, // Hide the text input for the url
	//		'add_upload_file_text' => 'Добавить изображение' 
	//	)
	//));
	
	$format_cmb->add_field( array(
		'name'    => 'Видео',		
		'default' => '',
		'id'      => 'post_video',
		'desc'    => 'Ссылка на видео (YouTube, Vimeo) или код для вставки',
		'type'    => 'textarea_small',
	));
	
	$format_cmb->add_field( array(
		'name'    => 'Источник - название',		
		'default' => '',
		'id'      => 'post_source_name',
		'desc'    => 'Название источника новости',
		'type'    => 'text',
	));
	
	$format_cmb->add_field( array(
		'name'    => 'Источник - ссылка',		
		'default' => '',
		'id'      => 'post_source_url',
		'desc'    => 'Ссылка на источник новости',
		'type'    => 'text_url',
	));
	
	

	/* People tax */
	$person_cat_term = new_cmb2_box( array(
		'id'               => 'person_cat_data',
		'title'            => 'Опции шаблона',
		'object_types'     => array( 'term' ), 
		'taxonomies'       => array( 'person_cat' )		
	));

	$person_cat_term->add_field( array(
		'name'    => 'Изображение заставки',		
		'id'      => 'featured_action_image',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Добавить изображение' // Change upload button text. Default: "Add or Upload File"
		),
	) );

	$person_cat_term->add_field( array(
		'name'     => 'Заголовок',
		'desc'     => 'Текст заголовка на заставке',
		'id'       => 'featured_action_title',
		'type'     => 'text'		
	));
	
	$person_cat_term->add_field( array(
		'name'     => 'Подзаголовок / описание',
		'desc'     => 'Текст подзаголовка на заставке',
		'id'       => 'featured_action_subtitle',
		'type'     => 'textarea_small'		
	));
	
	$person_cat_term->add_field( array(
		'name'     => 'Ссылка',
		'desc'     => 'Адрес, куда ссылается кнопка на заставке',
		'id'       => 'featured_action_link',
		'type'     => 'text_url'		
	));
	
	$person_cat_term->add_field( array(
		'name'     => 'Текст ссылки',
		'desc'     => 'Текст на кнопке',
		'id'       => 'featured_action_link_text',
		'type'     => 'text'		
	));
	
	
	//markers
	$marker_cmb = new_cmb2_box( array(
        'id'            => 'marker_settings_metabox',
        'title'         => 'Настройки маркера',
        'object_types'  => array( 'marker', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',		
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	
	$marker_cmb->add_field(array(
		'name'    => 'Адрес',
		'id'      => 'marker_address',
		'type'    => 'text',
		'default' => ''
	));
	
	$marker_cmb->add_field( array(
		'name' => 'Маркер',
		'desc' => 'Укажите позициюна карте',
		'id'   => 'marker_location',
		'type' => 'pw_map',
		'split_values' => true, // Save latitude and longitude as two separate fields
	));
	
	$marker_cmb->add_field( array(
		'name'        => 'Связанная кампания',
		'id'          => 'marker_related_campaign',
		'type'        => 'post_search_text', // This field type
		// post type also as array
		'post_type'   => 'leyka_campaign',
		// Default is 'checkbox', used in the modal view to select the post type
		'select_type' => 'radio',
		// Will replace any selection with selection from modal. Default is 'add'
		'select_behavior' => 'replace',
	));
	
	// marker groups 
	$markern_cat_term = new_cmb2_box( array(
		'id'               => 'marker_cat_data',
		'title'            => 'Настройки маркеров ',
		'object_types'     => array( 'term' ), 
		'taxonomies'       => array( 'marker_cat' )		
	));

	$markern_cat_term->add_field( array(
		'name'             => 'Цвет маркера',		
		'id'               => 'layer_marker_colors',
		'type'             => 'select',
		'show_option_none' => false,
		'default'          => 'navi',
		'options'          => array(
			'navi' 	=> 'Синий',
			'red'   => 'Красный',
			'blue'  => 'Голубой',
		)
	));

	$markern_cat_term->add_field(array(
		'name'    => 'Класс иконки',
		'desc' 	  => 'Справочник по классам иконок WP - <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Найдите нужную и скопируйте класс</a>',
		'id'      => 'layer_marker_icon',
		'type'    => 'text',
		'default' => ''
	));
}

