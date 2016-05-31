<?php

add_action('init', 'krbl_custom_content', 20);
function krbl_custom_content(){
	
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
	register_post_type('project', array(
        'labels' => array(
            'name'               => 'Проекты',
            'singular_name'      => 'Проект',
            'menu_name'          => 'Проекты',
            'name_admin_bar'     => 'Добавить проект',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить проект',
            'new_item'           => 'Новый проект',
            'edit_item'          => 'Редактировать проект',
            'view_item'          => 'Просмотр проекта',
            'all_items'          => 'Все проекты',
            'search_items'       => 'Искать проект',
            'parent_item_colon'  => 'Родительский проект:',
            'not_found'          => 'Проекты не найдены',
            'not_found_in_trash' => 'В Корзине проекты не найдены'
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
    ));
	
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
        'publicly_queryable'  => true,
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
        'publicly_queryable'  => true,
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
}

/** Metaboxes **/
add_action( 'cmb2_admin_init', 'krbl_custom_metaboxes' );
function krbl_custom_metaboxes() {
	
	
	/** Post **/
    $format_cmb = new_cmb2_box( array(
        'id'            => 'post_format_metabox',
        'title'         => 'Настройки формата',
        'object_types'  => array( 'post', 'project', 'event'), // Post type
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
			'introvid' => 'Видео-заставка'
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
	
	
	
	/** Events **/
    $event_cmb = new_cmb2_box( array(
        'id'            => 'event_settings_metabox',
        'title'         => 'Настройки мероприятия',
        'object_types'  => array( 'event', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',		
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	

	$event_cmb->add_field( array(
		'name' => 'Дата начала',
		'id'   => 'event_date_start',
		'type' => 'text_date_timestamp',		
		'date_format' => 'd.m.Y',
	));
	
	$event_cmb->add_field( array(
		'name' => 'Время начала',
		'id' => 'event_time_start',
		'type' => 'text_time',
		'time_format' => 'H.i',
	));
	
	$event_cmb->add_field( array(
		'name' => 'Дата окончания',
		'id'   => 'event_date_end',
		'type' => 'text_date_timestamp',		
		'date_format' => 'd.m.Y',
	));
	
	$event_cmb->add_field( array(
		'name' => 'Время окончания',
		'id' => 'event_time_end',
		'type' => 'text_time',
		'time_format' => 'H.i',
	));
	
	$event_cmb->add_field( array(
		'name'    => 'Город',		
		'default' => '',
		'id'      => 'event_city',
		'type'    => 'text',
		'default' => 'Великий Новгород'
	));
	
	$event_cmb->add_field( array(
		'name'    => 'Адрес',		
		'default' => '',
		'id'      => 'event_address',
		'type'    => 'text',
	));
	
	$event_cmb->add_field( array(
		'name'    => 'Местро проведения',		
		'default' => '',
		'id'      => 'event_location',
		'type'    => 'text',
	));
	
	$event_cmb->add_field( array(
		'name'    => 'Участники (спикеры)',		
		'default' => '',
		'id'      => 'event_participants',
		'type'    => 'text'		
	));
	
	
	/** Page **/
//	$page_cmb = new_cmb2_box( array(
//        'id'            => 'page_data_metabox',
//        'title'         => 'Настройки страницы',
//        'object_types'  => array( 'page'), // Post type
//        'context'       => 'normal',
//        'priority'      => 'high',
//        'show_names'    => true, // Show field names on the left
//		//'show_on'       => array( 'key' => 'page-template', 'value' => 'default' ),	
//        //'cmb_styles'    => false, // false to disable the CMB stylesheet
//        // 'closed'     => true, // Keep the metabox closed by default
//    ));
//	
//	$page_cmb->add_field( array(
//		'name' => 'Дополнительный блок',
//		'id'   => 'page_side',
//		'type' => 'textarea'
//		
//	) );
//	
//	$page_cmb->add_field( array(
//		'name' => 'Ссылка для кнопки CTA',
//		'id'   => 'cta_link',
//		'type' => 'text_url',
//		// 'protocols' => array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' ), // Array of allowed protocols
//	) );
//	
//	$page_cmb->add_field( array(
//		'name' => 'Текст для кнопки CTA',
//		'id'   => 'cta_text',
//		'type' => 'text',
//	) );
	
	
	/** Homepage **/
	$homepage_cmb = new_cmb2_box( array(
        'id'            => 'homepage_data_metabox',
        'title'         => 'Настройки главной',
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		'show_on'       => array( 'key' => 'page-template', 'value' => 'page-homepage.php' ),	
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	
	$homepage_cmb->add_field( array(
		'name'        => 'Стартовый блок',
		'id'          => 'home_featured_item',
		'type'        => 'post_search_text', // This field type
		// post type also as array
		'post_type'   => array('post', 'page', 'leyka_campaign'),
		// Default is 'checkbox', used in the modal view to select the post type
		'select_type' => 'radio',
		// Will replace any selection with selection from modal. Default is 'add'
		'select_behavior' => 'replace'		
	));
	
	$homepage_cmb->add_field( array(
		'name' => 'Текст кнопки в стартовом блоке',
		'id'   => 'home_featured_text',
		'type' => 'text'		
	) );
	//
	//$homepage_cmb->add_field( array(
	//	'name' => 'Ссылка блока "Стать наставником"',
	//	'id'   => 'mentor_link',
	//	'type' => 'text_url',
	//));
	//
	//$homepage_cmb->add_field( array(
	//	'name' => 'Текст блока "Пожертвование"',
	//	'id'   => 'donation_text',
	//	'type' => 'textarea_small'		
	//) );
	//
	//$homepage_cmb->add_field( array(
	//	'name' => 'Ссылка блока "Пожертвование"',
	//	'id'   => 'donation_link',
	//	'type' => 'text_url',
	//));
	
	/* People tax */
	$person_cat_term = new_cmb2_box( array(
		'id'               => 'person_cat_data',
		'title'            => 'Опции шаблона',
		'object_types'     => array( 'term' ), 
		'taxonomies'       => array( 'person_cat' )		
	));
	
	$person_cat_term->add_field( array(
		'name'     => 'ID объекта заставки',
		'desc'     => 'Заставка страницы категории представлена этим объектом',
		'id'       => 'featured_action_id',
		'type'     => 'text'		
	));
	
	$person_cat_term->add_field( array(
		'name'     => 'Напись на кнопке',
		'desc'     => 'Заставка страницы категории содержит кнопку с этой надписью',
		'id'       => 'featured_action_сta',
		'type'     => 'text'		
	));
}