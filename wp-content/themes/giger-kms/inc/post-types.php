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

	register_taxonomy('person', array('landing', 'page'), array(
		'labels' => array(
			'name'                       => 'Сотрудники',
			'singular_name'              => 'Сотрудник',
			'menu_name'                  => 'Сотрудники',
			'all_items'                  => 'Все сотрудники',
			'edit_item'                  => 'Редактировать сотрудника',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить сотрудника',
			'add_new_item'               => 'Добавить нового сотрудника',
			'new_item_name'              => 'Имя нового сотрудника',
			'parent_item'                => 'Родительский сотрудник',
			'parent_item_colon'          => 'Родительский сотрудник:',
			'search_items'               => 'Искать сотрудника',
			'popular_items'              => 'Часто используемые',
			'separate_items_with_commas' => 'Разделять запятыми',
			'add_or_remove_items'        => 'Добавить или удалить сотрудников',
			'choose_from_most_used'      => 'Выбрать из часто используемых',
			'not_found'                  => 'Не найдено'
		),
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => false,
		'publicly_queryable'=> false,
		'query_var'         => true,
		'rewrite'           => false,
		//'update_count_callback' => '',
	));

	register_taxonomy('document_cat', array('document'), array(
		'labels' => array(
			'name'                       => 'Категории документов',
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
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'section', 'with_front' => false),
		//'update_count_callback' => '',
	));


	/** Post types **/
	register_post_type('landing', array(
        'labels' => array(
            'name'               => 'Лэндинги',
            'singular_name'      => 'Лэндинг',
            'menu_name'          => 'Лэндинги',
            'name_admin_bar'     => 'Добавить лэндинг',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить лэндинг',
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
		'menu_icon'           => 'dashicons-schedule',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'page-attributes', 'revisions',),
        'taxonomies'          => array('section', 'person'),
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
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'revisions', 'thumbnail'),
        'taxonomies'          => array(),
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

	register_post_type('document', array(
        'labels' => array(
            'name'               => 'Документ',
            'singular_name'      => 'Документы',
            'menu_name'          => 'Документы',
            'name_admin_bar'     => 'Добавить документ',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить документ',
            'new_item'           => 'Новый документ',
            'edit_item'          => 'Редактировать документ',
            'view_item'          => 'Просмотр документов',
            'all_items'          => 'Все документы',
            'search_items'       => 'Искать документы',
            'parent_item_colon'  => 'Родительский документ:',
            'not_found'          => 'Документы не найдены',
            'not_found_in_trash' => 'В Корзине документы не найдены'
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
        'rewrite'             => false,
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'editor', 'revisions'),
        'taxonomies'          => array(),
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



}






/** Metaboxes **/
add_action( 'cmb2_admin_init', 'tst_custom_metaboxes' );
function tst_custom_metaboxes() {

	/** Projects fields **/
	$project_cmb = new_cmb2_box( array(
        'id'            => 'project_settings_metabox',
        'title'         => 'Настройки проекта',
        'object_types'  => array('project'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

	$project_cmb->add_field( array(
		'name' => 'Страница',
		'id'   => 'project_has_single',
		'desc' => 'Не имеет собственной страницы',
		'type' => 'checkbox',
		'default' => 0
	));


	/** Documents fields **/
	$doc_cmb = new_cmb2_box( array(
        'id'            => 'document_settings_metabox',
        'title'         => 'Настройки документа',
        'object_types'  => array('document'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

	$doc_cmb->add_field( array(
		'name'    => 'Файл для просмотра / скачивания',
		'desc'    => 'Используйте PDF файлы - просмотр других форматов не работает!',
		'id'      => 'document_file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => true, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Добавить файл' // Change upload button text. Default: "Add or Upload File"
		),
	));


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
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

}



/** Posts 2 Posts **/
add_action( 'p2p_init', 'tst_p2p_connection_types' );

function tst_p2p_connection_types() {

	p2p_register_connection_type(array(
        'name' 	=> 'landing_post',
        'from' 	=> 'landing',
        'to' 	=> array('post'),
		'admin_column' => 'to',
		'to_labels' => array(
			//'column_title' => 'НКО',
		),
		'admin_box' => array(
			'show' => 'to',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));

	p2p_register_connection_type(array(
        'name' 	=> 'landing_landing',
        'from' 	=> 'landing',
        'to' 	=> 'landing',
		//'admin_column' => 'to',
		'to_labels' => array(
			//'column_title' => 'НКО',
		),
		'from_labels' => array(
			//'column_title' => 'НКО',
		),
		'admin_box' => array(
			'show' => 'to',
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
			//'column_title' => 'НКО',
		),
		'admin_box' => array(
			'show' => 'to',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));

	p2p_register_connection_type(array(
        'name' 	=> 'landing_document',
        'from' 	=> 'landing',
        'to' 	=> array('document'),
		'admin_column' => 'to',
		'to_labels' => array(
			//'column_title' => 'НКО',
		),
		'admin_box' => array(
			'show' => 'to',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));
}
