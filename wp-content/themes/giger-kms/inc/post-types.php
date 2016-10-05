<?php

add_action('init', 'dr_custom_content', 20);
function dr_custom_content(){

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

    register_taxonomy('event_cat', array('event',), array(
        'labels' => array(
            'name'                       => 'Категории мероприятий',
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
        'rewrite'           => array('slug' => 'event-types', 'with_front' => false),
        //'update_count_callback' => '',
    ));

    register_taxonomy('linked-department', array('project', 'event', 'person', 'report',), array(
        'labels' => array(
            'name'                       => 'Связь с подразделениями',
            'singular_name'              => 'Связь с подразделением',
            'menu_name'                  => 'Связанные подразделения',
            'all_items'                  => 'Все связи с подразделениями',
            'edit_item'                  => 'Редактировать связь с подразделением',
            'view_item'                  => 'Просмотреть',
            'update_item'                => 'Обновить связь',
            'add_new_item'               => 'Добавить новую связь с подразделением',
            'new_item_name'              => 'Название новой связи',
            'parent_item'                => 'Родительская связь',
            'parent_item_colon'          => 'Родительское подразделение:',
            'search_items'               => 'Искать связи с подразделениями',
            'popular_items'              => 'Часто используемые',
            'separate_items_with_commas' => 'Разделять запятыми',
            'add_or_remove_items'        => 'Добавить или удалить связи с подразделениями',
            'choose_from_most_used'      => 'Выбрать из часто используемых',
            'not_found'                  => 'Не найдено'
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'linked-departments', 'with_front' => false),
        //'update_count_callback' => '',
    ));

    register_taxonomy('linked-project', array('event', 'person', 'report',), array(
        'labels' => array(
            'name'                       => 'Связь с проектами',
            'singular_name'              => 'Связь с проектом',
            'menu_name'                  => 'Связанные проекты',
            'all_items'                  => 'Все связи с проектами',
            'edit_item'                  => 'Редактировать связь с проектом',
            'view_item'                  => 'Просмотреть',
            'update_item'                => 'Обновить связь',
            'add_new_item'               => 'Добавить новую связь с проектом',
            'new_item_name'              => 'Название новой связи',
            'parent_item'                => 'Родительская связь',
            'parent_item_colon'          => 'Родительская связь:',
            'search_items'               => 'Искать связи с проектами',
            'popular_items'              => 'Часто используемые',
            'separate_items_with_commas' => 'Разделять запятыми',
            'add_or_remove_items'        => 'Добавить или удалить связи с проектами',
            'choose_from_most_used'      => 'Выбрать из часто используемых',
            'not_found'                  => 'Не найдено'
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'linked-projects', 'with_front' => false),
        //'update_count_callback' => '',
    ));

    register_taxonomy('place_cat', array('place',), array(
        'labels' => array(
            'name'                       => 'Категории мест',
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
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'place-types', 'with_front' => false),
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
            'view_item'          => 'Просмотр проектов',
            'all_items'          => 'Все проекты',
            'search_items'       => 'Искать проекты',
            'parent_item_colon'  => 'Родительский проект:',
            'not_found'          => 'Проекты не найдены',
            'not_found_in_trash' => 'В корзине проекты не найдены'
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
    register_post_type('department', array(
        'labels' => array(
            'name'               => 'Подразделения',
            'singular_name'      => 'Подразделение',
            'menu_name'          => 'Подразделения',
            'name_admin_bar'     => 'Добавить подразделение',
            'add_new'            => 'Добавить новое',
            'add_new_item'       => 'Добавить подразделение',
            'new_item'           => 'Новое подразделение',
            'edit_item'          => 'Редактировать подразделение',
            'view_item'          => 'Просмотр подразделений',
            'all_items'          => 'Все подразделения',
            'search_items'       => 'Искать подразделения',
            'parent_item_colon'  => 'Родительское подразделение:',
            'not_found'          => 'Подразделения не найдены',
            'not_found_in_trash' => 'В корзине подразделения не найдены'
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
        'has_archive'         => 'departments',
        'rewrite'             => array('slug' => 'department', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));

    register_post_type('newspaper', array(
        'labels' => array(
            'name'               => 'Газета',
            'singular_name'      => 'Газета',
            'menu_name'          => 'Газета',
            'name_admin_bar'     => 'Добавить выпуск',
            'add_new'            => 'Добавить новый выпуск',
            'add_new_item'       => 'Добавить выпуск',
            'new_item'           => 'Новый выпуск',
            'edit_item'          => 'Редактировать выпуск',
            'view_item'          => 'Просмотр выпусков',
            'all_items'          => 'Все выпуски',
            'search_items'       => 'Искать выпуски',
            'parent_item_colon'  => 'Родительский выпуск:',
            'not_found'          => 'Выпуски не найдены',
            'not_found_in_trash' => 'В корзине выпуски не найдены'
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
        'has_archive'         => 'newspaper',
        'rewrite'             => array('slug' => 'newspaper', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));

    register_post_type('publication', array(
        'labels' => array(
            'name'               => 'Публикации',
            'singular_name'      => 'Публикация',
            'menu_name'          => 'Публикации',
            'name_admin_bar'     => 'Добавить публикацию',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить публикацию',
            'new_item'           => 'Новая публикация',
            'edit_item'          => 'Редактировать публикацию',
            'view_item'          => 'Просмотр публикаций',
            'all_items'          => 'Все публикации',
            'search_items'       => 'Искать публикации',
            'parent_item_colon'  => 'Родительская публикация:',
            'not_found'          => 'Публикации не найдены',
            'not_found_in_trash' => 'В корзине публикации не найдены'
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
        'has_archive'         => 'publications',
        'rewrite'             => array('slug' => 'publication', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));

    register_post_type('place', array(
        'labels' => array(
            'name'               => 'Места',
            'singular_name'      => 'Место',
            'menu_name'          => 'Место',
            'name_admin_bar'     => 'Добавить место',
            'add_new'            => 'Добавить новое место',
            'add_new_item'       => 'Добавить место',
            'new_item'           => 'Новый место',
            'edit_item'          => 'Редактировать место',
            'view_item'          => 'Просмотр место',
            'all_items'          => 'Все места',
            'search_items'       => 'Искать места',
            'parent_item_colon'  => 'Родительское место:',
            'not_found'          => 'Места не найдены',
            'not_found_in_trash' => 'В корзине места не найдены'
        ),
        'public'              => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
//        'has_archive'         => 'place',
        'rewrite'             => array('slug' => 'place', 'with_front' => false),
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

    register_post_type('report', array(
        'labels' => array(
            'name'               => 'Отчеты',
            'singular_name'      => 'Отчет',
            'menu_name'          => 'Отчеты',
            'name_admin_bar'     => 'Добавить отчет',
            'add_new'            => 'Добавить новый отчет',
            'add_new_item'       => 'Добавить отчет',
            'new_item'           => 'Новый отчет',
            'edit_item'          => 'Редактировать отчет',
            'view_item'          => 'Просмотр отчетов',
            'all_items'          => 'Все отчеты',
            'search_items'       => 'Искать отчеты',
            'parent_item_colon'  => 'Родительский отчет:',
            'not_found'          => 'Отчеты не найдены',
            'not_found_in_trash' => 'В корзине отчеты не найдены'
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
        'has_archive'         => 'reports',
        'rewrite'             => array('slug' => 'report', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-category',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));


	// Pages
	add_post_type_support('page', 'excerpt');
	add_post_type_support('page', 'thumbnail');	

	// Remove post tags
	unregister_taxonomy_for_object_type('post_tag', 'post');
}

/** Metaboxes **/
add_action( 'cmb2_admin_init', 'dr_custom_metaboxes' );
function dr_custom_metaboxes() {
	
	
	/** Post **/
    $format_cmb = new_cmb2_box( array(
        'id'            => 'post_format_metabox',
        'title'         => 'Настройки формата',
        'object_types'  => array( 'post', 'event'), // Post type
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
	
	/** Project **/
    $format_cmb = new_cmb2_box( array(
        'id'            => 'project_format_metabox',
        'title'         => 'Настройки формата',
        'object_types'  => array( 'project'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',		
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));
	
	
	//format
	$format_cmb->add_field( array(
		'name'             => 'Шаблон',
		'desc'             => 'Укажите тип шаблона',
		'id'               => 'template_format',
		'type'             => 'select',
		'show_option_none' => false,
		'default'          => 'general',
		'options'          => array(
			'general' => 'Стандартный',			
			'builder' => 'Конструктор'
		),
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
		'name'     => 'Тексты ссылки',
		'desc'     => 'Текст на кнопке',
		'id'       => 'featured_action_link_text',
		'type'     => 'text'		
	));
}