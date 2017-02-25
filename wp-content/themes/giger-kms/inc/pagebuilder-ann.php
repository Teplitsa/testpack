<?php
/** Major version of pagebuilder fields  **/

add_action( 'init', 'tst_pagebuilder_conifg', 30 );
function tst_pagebuilder_conifg() {
    add_theme_support( 'wds-simple-page-builder' );

    if(function_exists('wds_page_builder_theme_support')) {
        wds_page_builder_theme_support( array(
            'hide_options'    => false,
            'parts_dir'       => 'pagebuilder',
            'parts_prefix'    => 'part',
            'use_wrap'        => 'off',
            'container'       => 'div',
            'container_class' => 'pagebuilder-part',
            'post_types'      => array( 'page', 'landing' ),
        ) );
    }

	register_page_builder_area( 'cta' );
}


/** Cover general */
add_filter( 'wds_page_builder_fields_cover-general', 'tst_add_cover_general_field' );
function tst_add_cover_general_field( $fields ) {

	$prefix = "cover_general_";
	$new_fields = array(
		array(
			'name'        		=> 'Связанный проект - заставка', //to do for private only
			'id'          		=> $prefix.'cover_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'  		=> array('project', 'event'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - заставка',
			'id'      => $prefix.'cover_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => array('image/jpeg', 'image/jpg', 'image/png'),
			),
		),

	);

	return array_merge( $fields, $new_fields );
}


/** Triple picture */
add_filter( 'wds_page_builder_fields_tripleblock-picture', 'tst_add_tripleblock_picture_field' );
function tst_add_tripleblock_picture_field( $fields ) {

	$prefix = "tripleblock_picture_";
	$new_fields = array(
		array(
			'name' 				=> 'Порядок блоков',
			'id' 				=> $prefix.'block_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'direct',
			'options' 			=> array(
				'direct' => 'Прямой',
				'revers' => 'Обратный'
			)
		),
		array(
			'name'        		=> 'Элемент - картинка', //to do for private only
			'id'          		=> $prefix.'element1_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - плашка',
			'id'      => $prefix.'element2_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => 'application/pdf',
			),
		),
		array(
			'name'        		=> 'Элемент - карточка', //to do for private only
			'id'          		=> $prefix.'element3_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Tripple 2 cards */
add_filter( 'wds_page_builder_fields_tripleblock-2cards', 'tst_add_tripleblock_2cards_field' );
function tst_add_tripleblock_2cards_field( $fields ) {

	$prefix = "tripleblock_2cards_";
	$new_fields = array(
		array(
			'name' 				=> 'Порядок блоков',
			'id' 				=> $prefix.'block_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'direct',
			'options' 			=> array(
				'direct' => 'Прямой',
				'revers' => 'Обратный'
			)
		),
		array(
			'name'        		=> 'Элемент - картинка', //to do for private only
			'id'          		=> $prefix.'element1_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - плашка',
			'id'      => $prefix.'element2_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => 'application/pdf',
			),
		),
		array(
			'name'        		=> 'Элемент - плашка 2', //to do for private only
			'id'          		=> $prefix.'element3_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - плашка 2',
			'id'      => $prefix.'element3_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => 'application/pdf',
			),
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Single block - picture **/
add_filter( 'wds_page_builder_fields_singleblock-picture', 'tst_add_singleblock_picture_field' );
function tst_add_singleblock_picture_field( $fields ) {

	$prefix = "singleblock_picture_";
	$new_fields = array(
		array(
			'name' 				=> 'Положение ссылки',
			'id' 				=> $prefix.'label_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left_bottom',
			'options' 			=> array(
				'left_bottom'	=> 'Левый нижний угол',
				'left_top' 		=> 'Левый верхний угол',
			)
		),
		array(
			'name'        		=> 'Элемент', //to do for private only
			'id'          		=> $prefix.'element_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Single block - text **/
add_filter( 'wds_page_builder_fields_singleblock-text', 'tst_add_singleblock_text_field' );
function tst_add_singleblock_text_field( $fields ) {

	$prefix = "singleblock_text_";
	$new_fields = array(
		array(
			'name' 		=> 'Заголовок',
			'id'   		=> $prefix.'title',
			'type'		=> 'text'
		),
		array(
			'name' 		=> 'Подзаголовок',
			'id'   		=> $prefix.'subtitle',
			'type'		=> 'text'
		),
		array(
			'name' 		=> 'Аннотация',
			'id'   		=> $prefix.'summary',
			'type'		=> 'textarea_small'
		),
		array(
			'name' 		=> 'Текст ссылки',
			'id'   		=> $prefix.'link_text',
			'type'		=> 'text'
		),
		array(
			'name'        		=> 'Элемент',
			'id'          		=> $prefix.'element_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Double block - element **/
add_filter( 'wds_page_builder_fields_doubleblock-element', 'tst_add_doubleblock_element_field' );
function tst_add_doubleblock_element_field( $fields ) {

	$prefix = "doubleblock_element_";
	$new_fields = array(
		array(
			'name' 				=> 'Положение картинки',
			'id' 				=> $prefix.'picture_position',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left',
			'options' 			=> array(
				'left' 	=> 'Слева',
				'right' => 'Справа',
			)
		),
		array(
			'name' 		=> 'Заголовок',
			'id'   		=> $prefix.'title',
			'type'		=> 'text'
		),
		array(
			'name' 		=> 'Подзаголовок',
			'id'   		=> $prefix.'subtitle',
			'type'		=> 'text'
		),
		array(
			'name' 		=> 'Аннотация',
			'id'   		=> $prefix.'summary',
			'type'		=> 'textarea_small'
		),
		array(
			'name' 		=> 'Текст ссылки',
			'id'   		=> $prefix.'link_text',
			'type'		=> 'text'
		),
		array(
			'name'        		=> 'Элемент',
			'id'          		=> $prefix.'element_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Triple block - people with cards **/
add_filter( 'wds_page_builder_fields_tripleblock-people2cards', 'tst_add_tripleblock_people2cards_field' );
function tst_add_tripleblock_people2cards_field( $fields ) {

	$prefix = "tripleblock_people2cards_";
	$new_fields = array(
		array(
			'name' 				=> 'Порядок блоков',
			'id' 				=> $prefix.'block_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'direct',
			'options' 			=> array(
				'direct' => 'Прямой',
				'revers' => 'Обратный'
			)
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element1_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - плашка',
			'id'      => $prefix.'element1_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => 'application/pdf',
			),
		),
		array(
			'name'        		=> 'Элемент - карточка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Сотрудники', //to do for private only
			'id'          		=> $prefix.'people_ids',
			'desc'				=> 'Добавьте не более 3 сотрудников в список',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('person'),
			'select_type' 		=> 'checkbox',
			'select_behavior' 	=> 'add'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Triple block - Picture with people **/
add_filter( 'wds_page_builder_fields_tripleblock-person', 'tst_add_tripleblock_person_field' );
function tst_add_tripleblock_person_field( $fields ) {

	$prefix = "tripleblock_person_";
	$new_fields = array(
		array(
			'name' 				=> 'Порядок блоков',
			'id' 				=> $prefix.'block_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'direct',
			'options' 			=> array(
				'direct' => 'Прямой',
				'revers' => 'Обратный'
			)
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element1_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'    => 'Файл - плашка',
			'id'      => $prefix.'element1_file',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			'query_args' => array(
				'type' => 'application/pdf',
			),
		),
		array(
			'name'        		=> 'Элемент - картинка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Сотрудник', //to do for private only
			'id'          		=> $prefix.'person_post',
			'desc'				=> 'Добавьте 1 сотрудника',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('person'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}


/** Double block - element **/
add_filter( 'wds_page_builder_fields_doubleblock-picturepeople', 'tst_add_doubleblock_picturepeople_field' );
function tst_add_doubleblock_picturepeople_field( $fields ) {

	$prefix = "doubleblock_picturepeople_";
	$new_fields = array(
		array(
			'name' 				=> 'Положение картинки',
			'id' 				=> $prefix.'picture_position',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left',
			'options' 			=> array(
				'left' 	=> 'Слева',
				'right' => 'Справа',
			)
		),
		array(
			'name'        		=> 'Элемент',
			'id'          		=> $prefix.'element1_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'landing'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Сотрудники', //to do for private only
			'id'          		=> $prefix.'people_ids',
			'desc'				=> 'Добавьте не более 3 сотрудников в список',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('person'),
			'select_type' 		=> 'checkbox',
			'select_behavior' 	=> 'add'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Help block **/
add_filter( 'wds_page_builder_fields_help', 'tst_add_help_field' );
function tst_add_help_field( $fields ) {

	$prefix = "help_";
	$new_fields = array(
		array(
			'name'    => 'Изображение - пожертвования',
			'id'      => $prefix.'img1_file_id',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			//'query_args' => array(
			//	'type' => 'application/pdf',  image
			//),
		),
		array(
			'name'    => 'Изображение - компаниям',
			'id'      => $prefix.'img2_file_id',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Добавить файл'
			),
			//'query_args' => array(
			//	'type' => 'application/pdf',  image
			//),
		),
	);

	return array_merge( $fields, $new_fields );
}
