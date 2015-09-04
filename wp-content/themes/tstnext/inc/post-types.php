<?php
add_action('init', 'tst_custom_content', 20);
if( !function_exists('tst_custom_content') ) {
    function tst_custom_content(){

        /** Existing post types settings: */
        deregister_taxonomy_for_object_type('post_tag', 'post');
    //	remove_post_type_support('page', 'thumbnail');

//        register_taxonomy('auctor', array('post',), array(
//            'labels' => array(
//                'name'                       => 'Авторы',
//                'singular_name'              => 'Автор',
//                'menu_name'                  => 'Авторы',
//                'all_items'                  => 'Все авторы',
//                'edit_item'                  => 'Редактировать автора',
//                'view_item'                  => 'Просмотреть',
//                'update_item'                => 'Обновить автора',
//                'add_new_item'               => 'Добавить нового автора',
//                'new_item_name'              => 'Название нового автора',
//                'parent_item'                => 'Родительский автор',
//                'parent_item_colon'          => 'Родительская автор:',
//                'search_items'               => 'Искать авторов',
//                'popular_items'              => 'Часто используемые',
//                'separate_items_with_commas' => 'Разделять запятыми',
//                'add_or_remove_items'        => 'Добавить или удалить авторов',
//                'choose_from_most_used'      => 'Выбрать из часто используемых',
//                'not_found'                  => 'Не найдено'
//            ),
//            'hierarchical'      => false,
//            'show_ui'           => true,
//            'show_in_nav_menus' => false,
//            'show_tagcloud'     => false,
//            'show_admin_column' => true,
//            'query_var'         => true,
//            'rewrite'           => array('slug' => 'auctor', 'with_front' => false),
//            //'update_count_callback' => '',
//        ));


        /** Post types: */
    //    register_post_type('gratitude', array(
    //        'labels' => array(
    //            'name'               => 'Благодарности',
    //            'singular_name'      => 'Благодарность',
    //            'menu_name'          => 'Благодарности',
    //            'name_admin_bar'     => 'Добавить благодарность',
    //            'add_new'            => 'Добавить новую',
    //            'add_new_item'       => 'Добавить новую благодарность',
    //            'new_item'           => 'Новая благодарность',
    //            'edit_item'          => 'Редактировать благодарность',
    //            'view_item'          => 'Просмотр благодарности',
    //            'all_items'          => 'Все благодарности',
    //            'search_items'       => 'Искать благодарности',
    //            'parent_item_colon'  => 'Родительская благодарность:',
    //            'not_found'          => 'Благодарности не найдены',
    //            'not_found_in_trash' => 'В корзине благодарности не найдены'
    //        ),
    //        'public'              => true,
    //        'exclude_from_search' => false,
    //        'publicly_queryable'  => true,
    //        'show_ui'             => true,
    //        'show_in_nav_menus'   => false,
    //        'show_in_menu'        => true,
    //        'show_in_admin_bar'   => true,
    //        //'query_var'           => true,
    //        'capability_type'     => 'post',
    //        'has_archive'         => false,
    //        'rewrite'             => array('slug' => 'gratitude', 'with_front' => false),
    //        'hierarchical'        => false,
    //        'menu_position'       => 5,
    //        'menu_icon'           => 'dashicons-smiley',
    //        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
    //        'taxonomies'          => array(),
    //    ));

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
            'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
            'taxonomies'          => array(),
        ));
    }

}

/* Alter post labels... */

/** Posts 2 posts */
//add_action( 'p2p_init', 'apl_connection_types' );
//function apl_connection_types() {
//	p2p_register_connection_type( array(
//		'name' => 'project_post',
//		'from' => 'project',
//		'to'   => 'post',
//		'sortable'   => true,
//		'reciprocal' => false,
//		'prevent_duplicates' => true,
//		'admin_box' => array(
//			'show' => 'any',
//			'context' => 'normal',
//			'can_create_post' => true
//		),
//		'admin_column' => 'to'
//	) );
//}