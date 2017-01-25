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
    // Markers:
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
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'layer', 'with_front' => false),
        //'update_count_callback' => '',
    ));
    // Markers - end

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
        'supports'            => array('title', 'excerpt', 'editor'),
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

    register_post_type('book', array(
        'labels' => array(
            'name'               => 'Книга',
            'singular_name'      => 'Книги',
            'menu_name'          => 'Книги',
            'name_admin_bar'     => 'Добавить книгу',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить книгу',
            'new_item'           => 'Новая книга',
            'edit_item'          => 'Редактировать',
            'view_item'          => 'Просмотр',
            'all_items'          => 'Все книги',
            'search_items'       => 'Искать книги',
            'parent_item_colon'  => 'Родительская книга:',
            'not_found'          => 'Книги не найдены',
            'not_found_in_trash' => 'В корзине книги не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        'capability_type'     => 'post',
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'books', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-media-text',
        'supports'            => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
        'taxonomies'          => array( 'post_tag' ),
    ));

    register_post_type('story', array(
        'labels' => array(
            'name'               => 'История',
            'singular_name'      => 'Истории',
            'menu_name'          => 'Истории',
            'name_admin_bar'     => 'Добавить историю',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить историю',
            'new_item'           => 'Новая история',
            'edit_item'          => 'Редактировать',
            'view_item'          => 'Просмотр',
            'all_items'          => 'Все истории',
            'search_items'       => 'Искать истории',
            'parent_item_colon'  => 'Родительская история:',
            'not_found'          => 'Истории не найдены',
            'not_found_in_trash' => 'В корзине истории не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => false,
        'capability_type'     => 'post',
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'stories', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-media-text',
        'supports'            => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
        'taxonomies'          => array( 'post_tag' ),
    ));

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

	/* Pages **/
	$page_cmb = new_cmb2_box( array(
        'id'            => 'page_settings_metabox',
        'title'         => 'Настройки страницы',
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		//'show_on_cb'    => 'tst_show_on_general_pages',
        //'cmb_styles'    => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

	$page_cmb->add_field( array(
		'name' => 'ID иконки',
		'id'   => 'icon_id',
		'type' => 'text',
		'desc' => 'ID можно выбрать по ссылке https://material.io/icons/#ic_markunread'
	));

    /** "About" page */
    $about_page_cmb = new_cmb2_box( array(
        'id'            => 'team_settings_metabox',
        'title'         => 'Команда',
        'object_types'  => array('page'),
        'show_on'       => array('key' => 'id', 'value' => array(39,)),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        'closed'        => false,
    ));

    $group_field_id = $about_page_cmb->add_field( array(
        'id'          => 'team',
        'type'        => 'group',
        'description' => 'Настройки команды проекта',
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => '№ {#}', // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => 'Добавить',
            'remove_button' => 'Удалить',
            'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );
    $about_page_cmb->add_group_field($group_field_id, array(
        'name' => 'Имя',
        'id'   => 'name',
        'type' => 'text',
        // 'repeatable' => true,
    ));
    $about_page_cmb->add_group_field($group_field_id, array(
        'name' => 'Должность/обязанности',
        'id'   => 'position',
        'type' => 'text',
        // 'repeatable' => true,
    ));
    $about_page_cmb->add_group_field( $group_field_id, array(
        'name' => 'Фото',
        'id'   => 'image',
        'type' => 'file',
    ) );

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

    // Markers CPT CFs:
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

    $marker_cmb->add_field(array(
        'name'    => 'Адрес',
        'id'      => 'marker_address',
        'type'    => 'text',
        'default' => ''
    ));

    $marker_cmb->add_field( array(
        'name' => 'Маркер',
        'desc' => 'Укажите позицию на карте',
        'id'   => 'marker_location',
        'type' => 'pw_map',
        'split_values' => true, // Save latitude and longitude as two separate fields
    ));

    $marker_cmb->add_field(array(
        'name'    => 'Телефон',
        'id'      => 'marker_phones',
        'type'    => 'textarea_small',
        'default' => ''
    ));

    // Marker groups taxonomy CFs:
    $markern_cat_term = new_cmb2_box( array(
        'id'               => 'marker_cat_data',
        'title'            => 'Настройки маркеров',
        'object_types'     => array('term'),
        'taxonomies'       => array('marker_cat')
    ));
    $markern_cat_term->add_field( array(
        'name'             => 'Цвет маркера',
        'id'               => 'layer_marker_color',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'orange',
        'options'          => array(
            'orange'   => 'Оранжевый',
            'green'  => 'Зеленый',
            'red'  => 'Красный',
        )
    ));
    $markern_cat_term->add_field(array(
        'name'    => 'Класс иконки',
        'desc' 	  => 'Справочник по классам иконок WP - <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Найдите нужную и скопируйте класс</a>',
        'id'      => 'layer_marker_icon',
        'type'    => 'text',
        'default' => ''
    ));

    /** Book **/
    $book_cmb = new_cmb2_box( array(
        'id'            => 'book_info_metabox',
        'title'         => 'Подробности',
        'object_types'  => array( 'book' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

    $book_cmb->add_field( array(
        'name' => 'Автор',
        'id'   => 'book_author',
        'type' => 'text',
    ));
    
    $book_cmb->add_field( array(
        'name' => 'Файл для скачивания',
        'id'   => 'book_att_id',
        'type' => 'file',
    ));

    /** Story **/
    $story_cmb = new_cmb2_box( array(
        'id'            => 'story_settings_metabox',
        'title'         => 'Автор',
        'object_types'  => array( 'story' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

    $story_cmb->add_field( array(
        'name' => 'Имя',
        'id'   => 'story_author_name',
        'type' => 'text',
    ));

    $story_cmb->add_field( array(
        'name' => 'Возраст',
        'id'   => 'story_author_age',
        'type' => 'text',
    ));

    $story_cmb->add_field( array(
        'name' => 'Пол',
        'id'   => 'story_author_gender',
        'type' => 'text',
        'type'    => 'radio_inline',
        'options' => array(
            'female' => 'Женщина',
            'male' => 'Мужчина',
        ),
        'default' => 'female',
    ));

    //Homepage
	$home_cmb = new_cmb2_box( array(
        'id'            => 'home_settings_metabox',
        'title'         => 'Настройки страницы',
        'object_types'  => array( 'page'), // Post type
		'show_on'      => array( 'key' => 'page-template', 'value' => 'page-home.php' ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ));

	$home_cmb->add_field( array(
		'name' => 'ID статей карточек 1-го блока',
		'desc' => 'Строго 5 элементов в нужном порядке',
		'id'   => 'block_one',
		'type' => 'post_search_text',
		'post_type' => array('item'),
		'select_type' => 'checkbox',
		'select_behavior' => 'add'
	) );

	$home_cmb->add_field( array(
		'name' => 'ID статей карточек 2-го блока',
		'desc' => 'Строго 3 элемента в нужном порядке',
		'id'   => 'block_two',
		'type' => 'post_search_text',
		'post_type' => array('item'),
		'select_type' => 'checkbox',
		'select_behavior' => 'add'
	));

	$home_cmb->add_field( array(
		'name'        		=> 'Инф. поддержка - 1',
		'id'          		=> 'infosup_one',
		'type' 				=> 'post_search_text',
		'post_type'			=> array('item'),
		'select_type' 		=> 'checkbox',
		'select_behavior'	=> 'add'
	));

	$home_cmb->add_field( array(
		'name'        		=> 'Инф. поддержка - 2',
		'id'          		=> 'infosup_two',
		'type' 				=> 'post_search_text',
		'post_type'			=> array('item'),
		'select_type' 		=> 'checkbox',
		'select_behavior'	=> 'add'
	));

	$home_cmb->add_field( array(
		'name'        		=> 'Инф. поддержка - 3',
		'id'          		=> 'infosup_three',
		'type' 				=> 'post_search_text',
		'post_type'			=> array('item'),
		'select_type' 		=> 'checkbox',
		'select_behavior'	=> 'add'
	));

	$home_cmb->add_field( array(
		'name'        		=> 'Инф. поддержка - 4',
		'id'          		=> 'infosup_four',
		'type' 				=> 'post_search_text',
		'post_type'			=> array('item'),
		'select_type' 		=> 'checkbox',
		'select_behavior'	=> 'add'
	));
}
