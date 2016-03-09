<?php

add_action('init', 'kds_custom_content', 20);
function kds_custom_content(){
	
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
	register_post_type('programm', array(
        'labels' => array(
            'name'               => 'Программы',
            'singular_name'      => 'Программа',
            'menu_name'          => 'Программы',
            'name_admin_bar'     => 'Добавить программу',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить новую',
            'new_item'           => 'Новая программа',
            'edit_item'          => 'Редактировать программу',
            'view_item'          => 'Просмотр программ',
            'all_items'          => 'Все программы',
            'search_items'       => 'Искать программы',
            'parent_item_colon'  => 'Родительская программма:',
            'not_found'          => 'Программы не найдены',
            'not_found_in_trash' => 'В Корзине программы не найдены'
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
        'rewrite'             => array('slug' => 'programm', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt','page-attributes'),
        'taxonomies'          => array('post_tag'),
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
        'exclude_from_search' => false,
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
	
	
}

/** Metaboxes **/
//add_action( 'cmb2_admin_init', 'tst_custom_metaboxes' );
function tst_custom_metaboxes() {
		
	// Start with an underscore to hide fields from custom fields list
    $prefix = '_kds_';
		
	$marker_cmb = new_cmb2_box( array(
        'id'            => 'marker_settings_metabox',
        'title'         => 'Настройки маркера',
        'object_types'  => array( 'marker' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',		
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	
	$marker_cmb->add_field( array(
		'name'    => 'Полное наименование',		
		'default' => '',
		'id'      => 'marker_fullname',
		'type'    => 'textarea_small'
	) );
	
}