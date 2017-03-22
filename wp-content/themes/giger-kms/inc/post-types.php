<?php

add_action('init', 'tst_custom_content', 20);
function tst_custom_content(){

	register_taxonomy('author_news', array('post'), array(
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
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'people', 'with_front' => false),
		//'update_count_callback' => '',
	));

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
		'publicly_queryable'=> true,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'rewrite'           => array('slug'=> 'media', 'with_front' => false),
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
            'new_item'           => 'Новый лэндинг',
            'edit_item'          => 'Редактировать лэндинг',
            'view_item'          => 'Просмотр лэндингов',
            'all_items'          => 'Все лэндинги',
            'search_items'       => 'Искать лэндинги',
            'parent_item_colon'  => 'Родительские лэндинги:',
            'not_found'          => 'Лэндинг не найдены',
            'not_found_in_trash' => 'В Корзине лэндинги не найдены'
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
        'supports'            => array('title', 'excerpt', 'thumbnail', 'page-attributes', 'revisions',),
        'taxonomies'          => array('section', 'post_tag'),
    ));

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
            'view_item'          => 'Просмотр проектов',
            'all_items'          => 'Все проекты',
            'search_items'       => 'Искать проекты',
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
        'hierarchical'        => true,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'excerpt', 'editor', 'revisions', 'page-attributes', 'thumbnail'),
        'taxonomies'          => array('post_tag'),
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
        'has_archive'         => 'events',
        'rewrite'             => array('slug' => 'event', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
        'taxonomies'          => array(),
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
        'supports'            => array('title', 'excerpt', 'editor', 'revisions', 'thumbnail', 'page-attributes'),
        'taxonomies'          => array(),
    ));

	register_post_type('import', array(
        'labels' => array(
            'name'               => 'Архивные записи',
            'singular_name'      => 'Архивная запись',
            'menu_name'          => 'Архив',
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
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => 'dront-archive',
        'rewrite'             => array('slug' => 'archive', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 27,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'editor', 'revisions'),
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
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'marker', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-location',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt',),
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
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'layer', 'with_front' => false),
        //'update_count_callback' => '',
    ));

	//pages
	add_post_type_support('page', 'excerpt');
	add_post_type_support('page', 'thumbnail');

	//attachment
	add_post_type_support( 'attachment', 'thumbnail' );

	//remove post tags
	unregister_taxonomy_for_object_type('category', 'post');
    register_taxonomy_for_object_type( 'attachment_tag', 'attachment' );
	register_taxonomy_for_object_type( 'section', 'leyka_campaign' );

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

    //markers
    $marker_cmb = new_cmb2_box( array(
        'id'            => 'marker_settings_metabox',
        'title'         => 'Настройки маркера',
        'object_types'  => array('marker',), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        //'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

    $marker_cmb->add_field( array(
        'name' => 'Приложенные фото',
        'desc' => '',
        'id'   => 'marker_photos',
        'type' => 'file_list',
         'preview_size' => array(100, 100),
        // Optional, override default text strings
//        'text' => array(
//            'add_upload_files_text' => 'Replacement', // default: "Add or Upload Files"
//            'remove_image_text' => 'Replacement', // default: "Remove Image"
//            'file_text' => 'Replacement', // default: "File:"
//            'file_download_text' => 'Replacement', // default: "Download"
//            'remove_text' => 'Replacement', // default: "Remove"
//        ),
    ) );

    $marker_cmb->add_field(array(
        'name'    => 'Адрес',
        'id'      => 'marker_address',
        'type'    => 'text',
        'default' => '',
		'column' => true
    ));

    $marker_cmb->add_field( array(
        'name' => 'Маркер',
        'desc' => 'Укажите позицию на карте',
        'id'   => 'marker_location',
        'type' => 'pw_map',
        'split_values' => true, // Save latitude and longitude as two separate fields
    ));

    $marker_cmb->add_field(array(
        'name'    => 'ФИО отправителя',
        'id'      => 'marker_contact_fullname',
        'type'    => 'text',
        'default' => '',
		'column' => true
    ));

    $marker_cmb->add_field(array(
        'name'    => 'Контакты отправителя',
        'id'      => 'marker_contacts',
        'type'    => 'text',
        'default' => ''
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
            'black'         => 'Черный',
            'red'           => 'Красный',
            'orange' 	    => 'Оранжевый',
            'dark-pink'     => 'Темно-розовый',
            'sand'          => 'Желтый',
            'green'         => 'Зеленый',
            'grey-green'    => 'Серо-зеленый',
        )
    ));
    $markern_cat_term->add_field(array(
        'name'    => 'Класс иконки',
        'desc' 	  => 'Справочник по классам иконок WP - <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Найдите нужную и скопируйте класс</a>',
        'id'      => 'layer_marker_icon',
        'type'    => 'text',
        'default' => ''
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
		'type'    => 'text',
		'column' => true
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
		'column' => true
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
		'desc'	  	=> 'Укажите адрес: город, улицу и номер дома. Для мероприятий без города укажите адрес полностью',
		'default' 	=> '',
		'id'   		=> 'event_address',
		'type'		=> 'text',
		'column' => true
	));

	$event_cmb->add_field( array(
		'name'    => 'Место проведения',
		'desc'	  => 'Укажите название (например, Парк им. Горького или офис компании "Добро")',
		'default' => '',
		'id'      => 'event_location',
		'type'    => 'text',
		'column' => true
	));

	$event_cmb->add_field(array(
		'name'			=> 'Маркер',
		'desc'			=> 'Укажите позицию на карте',
		'id'			=> 'event_marker',
		'type' 			=> 'pw_map',
		'split_values'	=> true
	));

	$event_cmb->add_field(array(
		'name' 		=> 'Теглайн',
		'desc'	  	=> 'Теглайн - для отображения в текстовый карточек на лендинге (если применимо)',
		'default' 	=> '',
		'id'   		=> 'subtitle_meta',
		'type'		=> 'text'
	));


	/** Landing fields  **/
	$landing_cmb = new_cmb2_box( array(
        'id'            => 'landing_settings_metabox',
        'title'         => 'Тексты и настройки',
        'object_types'  => array('landing'), // Post type
        'context'       => 'normal',
        'priority'      => 'low',
        'show_names'    => true,
    ));

	$landing_cmb->add_field(array(
		'name' 		=> 'Аннотация',
		'desc'	  	=> 'Отображается в карточках и списках',
		'default' 	=> '',
		'id'   		=> 'landing_excerpt',
		'type'		=> 'textarea_small'
	));

	$landing_cmb->add_field(array(
		'name' 		=> 'Полный текст',
		'desc'	  	=> 'Отображается на странице Вникнуть',
		'default' 	=> '',
		'id'   		=> 'landing_content',
		'type'		=> 'wysiwyg',
		'options' 	=> array(
			'textarea_rows' => 8
		)
	));

	$landing_cmb->add_field( array(
		'name' => 'Сбросить настройки цветовой схемы',
		'id'   => 'landing_reset_colors',
		'type' => 'checkbox',
	));


	/** Project fields  **/
	$project_cmb = new_cmb2_box( array(
        'id'            => 'project_settings_metabox',
        'title'         => 'Настройки проекта',
        'object_types'  => array('project'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

	$project_cmb->add_field(array(
		'name' 		=> 'Подзаголовок',
		'desc'	  	=> 'Используется в шаблонах карточек и страницы проекта как аналог метаданных',
		'default' 	=> '',
		'id'   		=> 'subtitle_meta',
		'type'		=> 'text'
	));

	$project_cmb->add_field(array(
		'name' 		=> 'Аннотация',
		'desc'	  	=> 'Аннотация - для отображения в списках и карточках',
		'default' 	=> '',
		'id'   		=> 'project_excerpt',
		'type'		=> 'textarea_small'
	));

    $project_cmb->add_field(array(
        'name' => 'Ве выводить в архиве',
        'desc' => '',
        'id'   => 'exclude_from_archive',
        'type' => 'text',
    ));
    $project_cmb->add_field(array(
        'name' => 'Выводить в начале архива',
        'desc' => '',
        'id'   => 'archive_priority_output',
        'type' => 'checkbox',
    ));

	/** Page settings */
	$home_cmb = new_cmb2_box( array(
        'id'            => 'page_settings_metabox',
        'title'         => 'Настройки страницы',
        'object_types'  => array('page'), // Post type
		//'show_on'      => array( 'key' => 'page-template', 'value' => 'page.php' ),
        'context'       => 'normal',
        'priority'      => 'low',
        'show_names'    => true,
    ));

	$home_cmb->add_field( array(
		'name' => 'Не выводить футер страницы',
		'id'   => 'dont_show_footer',
		'type' => 'checkbox',
		'default' 	=> '',
	));


	/** Homepage settings */
	$home_cmb = new_cmb2_box( array(
        'id'            => 'home_settings_metabox',
        'title'         => 'Настройки главной',
        'object_types'  => array('page'), // Post type
		'show_on'      => array( 'key' => 'page-template', 'value' => 'page-home.php' ),
        'context'       => 'normal',
        'priority'      => 'low',
        'show_names'    => true,
    ));

	$home_cmb->add_field( array(
		'name' => 'Сбросить настройки цветовой схемы',
		'id'   => 'landing_reset_colors',
		'type' => 'checkbox',
	));

	$partners_field_id = $home_cmb->add_field( array(
		'name' 		  => 'Партнеры',
		'id'          => 'home_partners',
		'type'        => 'group',
		'options'     => array(
			'group_title'   => 'Партнер {#}',
			'add_button'    => 'Добавить',
			'remove_button' => 'Удалить',
			'sortable'      => true,
		),
	));

	$home_cmb->add_group_field( $partners_field_id, array(
		'name' => 'Название',
		'id'   => 'home_partner_title',
		'type' => 'text'
	));

	$home_cmb->add_group_field( $partners_field_id, array(
		'name' => 'Веб-сайт',
		'id'   => 'home_partner_url',
		'type' => 'text_url'
	));

}



/** Posts 2 Posts **/
add_action( 'p2p_init', 'tst_p2p_connection_types' );

function tst_p2p_connection_types() {



	p2p_register_connection_type(array(
        'name' 	=> 'landing_project',
        'from' 	=> 'landing',
        'to' 	=> array('project'),
		'admin_column' => 'to',
		'to_labels' => array(
			'column_title' => 'Лэндинги',
		),
		'admin_box' => array(
			'show' => 'any',
			'context' => 'advanced',
			'can_create_post' => false
		)
    ));



	p2p_register_connection_type(array(
        'name' 	=> 'import_attachments',
        'from' 	=> array('post', 'import'),
        'to' 	=> 'attachment',
		'admin_column' => false,
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
