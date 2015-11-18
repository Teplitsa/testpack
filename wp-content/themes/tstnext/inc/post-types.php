<?php

add_action('init', 'tst_custom_content', 20);
if( !function_exists('tst_custom_content') ) {
function tst_custom_content(){

    /** Existing post types settings: */
    deregister_taxonomy_for_object_type('post_tag', 'post');
	remove_post_type_support('page', 'thumbnail' );
	
	
	register_taxonomy('children_group', array('children',), array(
        'labels' => array(
            'name'                       => 'Группы',
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
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'children-status', 'with_front' => false),
        //'update_count_callback' => '',        
    ));
	
    /** Post types: */
	register_post_type('children', array(
        'labels' => array(
            'name'               => 'Дети',
            'singular_name'      => 'Профиль',
            'menu_name'          => 'Наши дети',
            'name_admin_bar'     => 'Добавить профиль',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить профиль',
            'new_item'           => 'Новый профиль',
            'edit_item'          => 'Редактировать профиль',
            'view_item'          => 'Просмотр профиля',
            'all_items'          => 'Все профили',
            'search_items'       => 'Искать профиль',
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
        'has_archive'         => 'all-children',
        'rewrite'             => array('slug' => 'children', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-id',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array('children_status'),
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
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
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
        'menu_position'       => 5,
		'menu_icon'           => 'dashicons-networking',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array(),
    ));
	
	
}

} // if tst_custom_content


/* Alter post labels */
//function tst_change_post_labels($post_type, $args){ /* change assigned labels */
//    global $wp_post_types;
//
//    if($post_type != 'post')
//        return;

//    $labels = new stdClass();
//
//    $labels->name               = "Статьи";
//    $labels->singular_name      = "Статья";
//    $labels->add_new            = "Добавить новую";
//    $labels->add_new_item       = "Добавить новую";
//    $labels->edit_item          = "Редактировать статью";
//    $labels->new_item           = "Новая";
//    $labels->view_item          = "Просмотреть";
//    $labels->search_items       = "Поиск";
//    $labels->not_found          = "Не найдено";
//    $labels->not_found_in_trash = "В Корзине статьи не найдены";
//    $labels->parent_item_colon  = NULL;
//    $labels->all_items          = "Все статьи";
//    $labels->menu_name          = "Статьи";
//    $labels->name_admin_bar     = "Статья";
//
//    $wp_post_types[$post_type]->labels = $labels;
//}
//add_action('registered_post_type', 'tst_change_post_labels', 2, 2);
 
//function tst_change_post_menu_labels(){ /* change adming menu labels */
//    global $menu, $submenu;
//
//    $post_type_object = get_post_type_object('post');
//    $sub_label = $post_type_object->labels->all_items;
//    $top_label = $post_type_object->labels->name;
//
//    /* find proper top menu item */
//    $post_menu_order = 0;
//    foreach($menu as $order => $item){
//
//        if($item[2] == 'edit.php'){
//            $menu[$order][0] = $top_label;
//            $post_menu_order = $order;
//            break;
//        }
//    }
//
//    /* find proper submenu */
//    $submenu['edit.php'][$post_menu_order][0] = $sub_label;
//}
//add_action('admin_menu', 'tst_change_post_menu_labels');
 
//function tst_change_post_updated_labels($messages){     /* change updated post labels */
//    global $post;
//
//    $permalink = get_permalink($post->ID);
//
//    $messages['post'] = array(
//
//    0 => '',
//    1 => sprintf( 'Статья обновлена. <a href="%s">Просмотреть</a>', esc_url($permalink)),
//    2 => "Пользовательское поле обновлено",
//    3 => "Пользовательское поле удалено",
//    4 => "Статья обновлена",
//    5 => isset($_GET['revision']) ? sprintf('Редакция статьи восстановлена из %s', wp_post_revision_title((int)$_GET['revision'], false)) : false,
//    6 => sprintf('Статья опубликована. <a href="%s">Просмотреть</a>', esc_url($permalink)),
//    7 => "Статья сохранена",
//    8 => sprintf('Статья отправлена на рассмотрение. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview','true', $permalink))),
//    9 => sprintf('Статья запланирована. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview','true', $permalink))),
//    10 => sprintf('Черновик статьи обновлен. <a target="_blank" href="%s">Просмотреть</a>', esc_url(add_query_arg('preview', 'true', $permalink)))
//    );
//
//    return $messages;
//}
//add_filter('post_updated_messages', 'tst_change_post_updated_labels');

add_action( 'p2p_init', 'tst_connection_types' );
function tst_connection_types() {
	p2p_register_connection_type( array(
		'name' => 'project_post',
		'from' => 'project',
		'to'   => 'post',
		'sortable'   => true,
		'reciprocal' => false,
		'prevent_duplicates' => true,
		'admin_box' => array(
			'show' => 'any',
			'context' => 'normal',
			'can_create_post' => true
		),
		'admin_column' => 'to'
	) );
}
