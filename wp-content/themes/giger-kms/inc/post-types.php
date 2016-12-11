<?php

add_action('init', 'tst_custom_content', 20);
function tst_custom_content(){

	register_taxonomy('section', array('post', 'item', 'page'), array(
		'labels' => array(
			'name'                       => 'Разделы',
			'singular_name'              => 'Раздел',
			'menu_name'                  => 'Разделы',
			'all_items'                  => 'Все разделы',
			'edit_item'                  => 'Редактировать раздел',
			'view_item'                  => 'Просмотреть',
			'update_item'                => 'Обновить раздел',
			'add_new_item'               => 'Добавить новый раздел',
			'new_item_name'              => 'Название новый раздел',
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



	/** Post types **/
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
        'rewrite'             => false,
        'hierarchical'        => false,
        'menu_position'       => 20,
		'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));

	register_post_type('item', array(
        'labels' => array(
            'name'               => 'Статья',
            'singular_name'      => 'Статьи',
            'menu_name'          => 'Статьи',
            'name_admin_bar'     => 'Добавить статью',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить статью',
            'new_item'           => 'Новую статью',
            'edit_item'          => 'Редактировать статью',
            'view_item'          => 'Просмотр статей',
            'all_items'          => 'Все статьи',
            'search_items'       => 'Искать статьи',
            'parent_item_colon'  => 'Родительские статьи:',
            'not_found'          => 'Статьи не найдены',
            'not_found_in_trash' => 'В Корзине статьи не найдены'
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
        'rewrite'             => array('slug' => 'item', 'with_front' => false),
        'hierarchical'        => true,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-media-text',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'page-attributes'),
        'taxonomies'          => array('section'),
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


	/** Item **/
	$item_cmb = new_cmb2_box( array(
        'id'            => 'item_settings_metabox',
        'title'         => 'Настройки статьи',
        'object_types'  => array( 'item'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

	$item_cmb->add_field( array(
		'name' => 'Генерировать сайдбар',
		'id'   => 'has_sidebar',
		'type' => 'checkbox',
	));
	
	
	$item_cmb->add_field( array(
		'name' => 'ID иконки',
		'id'   => 'icon_id',
		'type' => 'text',
		'desc' => 'ID можно выбрать по ссылке https://material.io/icons/#ic_markunread'
	));
	
	
}
