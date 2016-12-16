<?php

add_action('init', 'tst_custom_content', 20);
function tst_custom_content(){

	register_taxonomy('section', array('landing', 'page'), array(
		'labels' => array(
			'name'                       => 'Разделы',
			'singular_name'              => 'Раздел',
			'menu_name'                  => 'Разделы',
			'all_items'                  => 'Все разделы',
			'edit_item'                  => 'Редактировать раздел',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить раздел',
			'add_new_item'               => 'Добавить новый раздел',
			'new_item_name'              => 'Название нового раздела',
			'parent_item'                => 'Родительский раздел',
			'parent_item_colon'          => 'Родительский раздел:',
			'search_items'               => 'Искать раздел',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить разделы',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'section', 'with_front' => false),
		//'update_count_callback' => '',
	));


	register_taxonomy('project_cat', array('project'), array(
		'labels' => array(
			'name'                       => 'Категории проектов',
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
			'search_items'               => 'Искать категорию',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить категорию',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => false,
		'publicly_queryable'=> false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'rewrite'           => false,
		//'update_count_callback' => '',
	));

	register_taxonomy('archive_page_cat', array('archive_page'), array(
		'labels' => array(
			'name'                       => 'Категории архива',
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
			'search_items'               => 'Искать категорию',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить категорию',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => true,
		'publicly_queryable'=> false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'rewrite'           => false,
		//'update_count_callback' => '',
	));

	register_taxonomy('attachment_tag', array('attachment'), array(
		'labels' => array(
			'name'                       => 'Метки документов',
			'singular_name'              => 'Метка документов',
			'menu_name'                  => 'Метки документов',
			'all_items'                  => 'Все метки документов',
			'edit_item'                  => 'Редактировать метку',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить метку',
			'add_new_item'               => 'Добавить новую метку',
			'new_item_name'              => 'Название новой метки',
			'parent_item'                => 'Родительская метка',
			'parent_item_colon'          => 'Родительская метка:',
			'search_items'               => 'Искать метку',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить метку',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => false,
		'publicly_queryable'=> false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'rewrite'           => false,
		//'update_count_callback' => '',
	));

    /** Post types **/
	register_post_type('landing', array(
        'labels' => array(
            'name'               => 'Лендинги',
            'singular_name'      => 'Лендинг',
            'menu_name'          => 'Лендинги',
            'name_admin_bar'     => 'Добавить лендинг',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить лендинг',
            'new_item'           => 'Новый лендинг',
            'edit_item'          => 'Редактировать лендинг',
            'view_item'          => 'Просмотр лендингов',
            'all_items'          => 'Все лендинги',
            'search_items'       => 'Искать лендинги',
            'parent_item_colon'  => 'Родительские лендинги:',
            'not_found'          => 'Лендинг не найдены',
            'not_found_in_trash' => 'В Корзине лендинги не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'item', 'with_front' => false),
        'hierarchical'        => true,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-layout',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'page-attributes', 'revisions',),
        'taxonomies'          => array('section', 'post_tag'),
    ));


	register_post_type('project', array(
        'labels' => array(
            'name'               => 'Проект',
            'singular_name'      => 'Проекты',
            'menu_name'          => 'Проекты',
            'name_admin_bar'     => 'Добавить проект',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить проект',
            'new_item'           => 'Новый проект',
            'edit_item'          => 'Редактировать проект',
            'view_item'          => 'Просмотр проектов',
            'all_items'          => 'Все проекты',
            'search_items'       => 'Искать проекты',
            'parent_item_colon'  => 'Родительский проект:',
            'not_found'          => 'Проекты не найдены',
            'not_found_in_trash' => 'В Корзине проекты не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'project', 'with_front' => false),
        'hierarchical'        => true,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'excerpt', 'editor', 'revisions', 'page-attributes', 'thumbnail'),
        'taxonomies'          => array('project_cat'),
    ));

	register_post_type('event', array(
        'labels' => array(
            'name'               => 'Анонсы',
            'singular_name'      => 'Анонс',
            'menu_name'          => 'Анонсы',
            'name_admin_bar'     => 'Добавить анонс',
            'add_new'            => 'Добавить анонс',
            'add_new_item'       => 'Добавить анонс',
            'new_item'           => 'Новый анонс',
            'edit_item'          => 'Редактировать анонс',
            'view_item'          => 'Просмотр анонсов',
            'all_items'          => 'Все анонсы',
            'search_items'       => 'Искать анонсы',
            'parent_item_colon'  => 'Родительские анонсы:',
            'not_found'          => 'Анонсы не найдены',
            'not_found_in_trash' => 'В Корзине анонсы не найдены'
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
        'rewrite'             => array('slug' => 'event/%year%/%monthnum%/%day%', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
        'taxonomies'          => array(),
    ));

	register_post_type('archive_page', array(
        'labels' => array(
            'name'               => 'Архивные страницы',
            'singular_name'      => 'Архивная страница',
            'menu_name'          => 'Архив',
            'name_admin_bar'     => 'Добавить страницу',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить страницу',
            'new_item'           => 'Новая страница',
            'edit_item'          => 'Редактировать страницу',
            'view_item'          => 'Просмотр страниц',
            'all_items'          => 'Все страницы',
            'search_items'       => 'Искать страницы',
            'parent_item_colon'  => 'Родительская страница:',
            'not_found'          => 'Страницы не найдены',
            'not_found_in_trash' => 'В Корзине страницы не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'archives', 'with_front' => false),
        'hierarchical'        => true,
        'menu_position'       => 25,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'editor', 'revisions'),
        'taxonomies'          => array('archive_page_cat'),
    ));

	register_post_type('person', array(
        'labels' => array(
            'name'               => 'Люди',
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
        'show_in_admin_bar'   => false,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'profile', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 10,
		'menu_icon'           => 'dashicons-businessman',
        'supports'            => array('title', 'excerpt', 'editor', 'revisions', 'thumbnail'),
        'taxonomies'          => array(),
    ));

	register_post_type('import', array(
        'labels' => array(
            'name'               => 'Импортные записи',
            'singular_name'      => 'Импортная запись',
            'menu_name'          => 'Импорт',
            'name_admin_bar'     => 'Добавить',
            'add_new'            => 'Добавить',
            'add_new_item'       => 'Добавить',
            'new_item'           => 'Новая',
            'edit_item'          => 'Редактировать',
            'view_item'          => 'Просмотр',
            'all_items'          => 'Все',
            'search_items'       => 'Искать',
            'parent_item_colon'  => 'Родительская:',
            'not_found'          => 'Записи не найдены',
            'not_found_in_trash' => 'В Корзине записи не найдены'
       ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'import', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 27,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'editor', 'revisions'),
    ));

	/*register_post_type('org', array(
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
    ));*/



	//pages
	add_post_type_support('page', 'excerpt');
	add_post_type_support('page', 'thumbnail');

	//remove post tags
	unregister_taxonomy_for_object_type('category', 'post');

    tst_setup_terms();
}

function tst_setup_terms() {
    
    if( !get_term_by( 'slug', 'bereginya', 'attachment_tag' ) ) {
        wp_insert_term( 'Берегиня', 'attachment_tag', $args = array( 'slug' => 'bereginya' ) );
    }
    
    if( !get_term_by( 'slug', 'report', 'attachment_tag' ) ) {
        wp_insert_term( 'Отчет', 'attachment_tag', $args = array( 'slug' => 'report' ) );
    }
    
    if( !get_term_by( 'slug', 'publication', 'attachment_tag' ) ) {
        wp_insert_term( 'Публикация', 'attachment_tag', $args = array( 'slug' => 'publication' ) );
    }
    
}






/** Metaboxes **/
add_action( 'cmb2_admin_init', 'tst_custom_metaboxes' );
function tst_custom_metaboxes() {

	/** Projects fields **/
	/*$project_cmb = new_cmb2_box( array(
        'id'            => 'project_settings_metabox',
        'title'         => 'Настройки проекта',
        'object_types'  => array('project'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

	$project_cmb->add_field( array(
		'name' => 'Страница',
		'id'   => 'project_has_single',
		'desc' => 'Не имеет собственной страницы',
		'type' => 'checkbox',
		'default' => 0
	));*/

	/** Events fields  **/
    $event_cmb = new_cmb2_box( array(
        'id'            => 'event_settings_metabox',
        'title'         => 'Настройки анонса',
        'object_types'  => array('event'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

	$event_cmb->add_field( array(
		'name'    => 'Название мероприятия',
		'default' => '',
		'id'      => 'event_name',
		'type'    => 'text'
	));

	$event_cmb->add_field( array(
		'name' => 'Дата начала',
		'id'   => 'event_date_start',
		'type' => 'text_date_timestamp',
		'date_format' => 'd.m.Y',
		'column' => true
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
		'name'    => 'Контакты организаторов',
		'default' => '',
		'id'      => 'event_contact',
		'type'    => 'text'
	));

	$event_cmb->add_field(array(
		'name' 		=> 'Адрес',
		'desc'	  	=> 'Укажите адрес: город, улицу и номер дома, для мероприятий без города - адрес полностью',
		'default' 	=> '',
		'id'   		=> 'event_address',
		'type'		=> 'text'
	));

	$event_cmb->add_field( array(
		'name'    => 'Местро проведения',
		'desc'	  => 'Укажите название, например, Парк им. Горького или офис компании "Добро"',
		'default' => '',
		'id'      => 'event_location',
		'type'    => 'text',
	));

	$event_cmb->add_field(array(
		'name'			=> 'Маркер',
		'desc'			=> 'Укажите позициюна карте',
		'id'			=> 'event_marker',
		'type' 			=> 'pw_map',
		'split_values'	=> true
	));


	/** Landing fields  **/
	$landing_cmb = new_cmb2_box( array(
        'id'            => 'landing_settings_metabox',
        'title'         => 'Настройки лэндинга',
        'object_types'  => array('landing'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));


}



/** Posts 2 Posts **/
add_action( 'p2p_init', 'tst_p2p_connection_types' );

function tst_p2p_connection_types() {

	p2p_register_connection_type(array(
        'name' 	=> 'landing_landing',
        'from' 	=> 'landing',
        'to' 	=> 'landing',
		'reciprocal' 	=> true,
		'admin_column' 	=> 'any',
		'to_labels' => array(
			'column_title' => 'Связи',
		),
		'from_labels' => array(
			'column_title' => 'Связи',
		),
		'admin_box' => array(
			'show' => 'any',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));

	p2p_register_connection_type(array(
        'name' 	=> 'landing_project',
        'from' 	=> 'landing',
        'to' 	=> array('project'),
		'admin_column' => 'to',
		'to_labels' => array(
			'column_title' => 'Лендинги',
		),
		'admin_box' => array(
			'show' => 'to',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));

	p2p_register_connection_type(array(
        'name' 	=> 'landing_person',
        'from' 	=> 'landing',
        'to' 	=> array('person'),
		'admin_column' => 'to',
		'to_labels' => array(
			'column_title' => 'Лендинги',
		),
		'admin_box' => array(
			'show' => 'to',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));

	p2p_register_connection_type(array(
        'name' 	=> 'connected_attachments',
        'from' 	=> array('landing', 'page', 'project'),
        'to' 	=> 'attachment',
		'admin_column' => 'any',
		'from_labels' => array(
			'column_title' => 'Файлы',
		),
		'admin_box' => array(
			'show' => 'from',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));
    
	p2p_register_connection_type(array(
        'name' 	=> 'import_attachments',
        'from' 	=> array('import'),
        'to' 	=> 'attachment',
		'admin_column' => 'any',
		'from_labels' => array(
			'column_title' => 'Файлы',
		),
		'admin_box' => array(
			'show' => 'from',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));
}
