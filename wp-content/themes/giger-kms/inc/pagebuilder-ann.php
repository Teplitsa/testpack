<?php
/** Temp version of pagebuilder fields to be merged **/



/** Cover general */
add_filter( 'wds_page_builder_fields_cover-general', 'tst_add_cover_general_field' );
function tst_add_cover_general_field( $fields ) {

	$prefix = "cover_general_";
	$new_fields = array(
		array(
			'name'        		=> 'Связанный проект - заставка', //to do for private only
			'id'          		=> $prefix.'cover_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'  		=> array('project'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Buttoned general */
add_filter( 'wds_page_builder_fields_cover-buttoned', 'tst_add_cover_buttoned_field' );
function tst_add_cover_buttoned_field( $fields ) {

	$prefix = "cover_buttoned_";
	$new_fields = array(
		array(
			'name'        		=> 'Связанный проект или анонс', //to do for private only
			'id'          		=> $prefix.'cover_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'  		=> array('project', 'event'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Tripple picture */
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
			'type'		=> 'textarea'
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
			'type'		=> 'textarea'
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
