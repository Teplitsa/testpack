<?php
/**
 * Temp pagebuilder functions. REMOVE IT AFTER PB SETUP COMPLETE!
 **/


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


/** Subtitle **/
add_filter( 'wds_page_builder_fields_subtitle', 'tst_add_subtitle_field' );
function tst_add_subtitle_field( $fields ) {

	$prefix = "subtitle_";
	$new_fields = array(
		array(
			'name'        		=> 'Текст подзагловока', //to do for private only
			'id'          		=> $prefix.'subtitle_text',
			'type'        		=> 'text'
		)
	);

	return array_merge( $fields, $new_fields );
}

/** Cover  - general **/
add_filter( 'wds_page_builder_fields_cover-general', 'tst_add_cover_general_field' );
function tst_add_cover_general_field( $fields ) {

	$prefix = "cover_general_";
	$new_fields = array(
		array(
			'name'        		=> 'Связанный проект - заставка', //to do for private only
			'id'          		=> $prefix.'cover_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'  		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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

/** Cover  - minimal **/
add_filter( 'wds_page_builder_fields_cover-minimal', 'tst_add_cover_minimal_field' );
function tst_add_cover_minimal_field( $fields ) {

	$prefix = "cover_minimal_";
	$new_fields = array(
		array(
			'name'        		=> 'Связанный элемент - заставка', //to do for private only
			'id'          		=> $prefix.'cover_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'  		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
		array(
			'name' 		=> 'Заголовок',
			'desc'	  	=> 'Если не указан - будет указан заголовок поста',
			'default' 	=> '',
			'id'   		=> $prefix.'cover_title',
			'type'		=> 'text'
		),
		array(
			'name' 		=> 'Подпись',
			'desc'	  	=> 'Если не указана - будет сгенерирована автоматически из аннотации поста',
			'default' 	=> '',
			'id'   		=> $prefix.'cover_desc',
			'type'		=> 'textarea_small'
		)
	);

	return array_merge( $fields, $new_fields );
}

/** Cover archive **/
add_filter( 'wds_page_builder_fields_cover-archive', 'tst_add_cover_archive_field' );
function tst_add_cover_archive_field( $fields ) {

    $prefix = "cover_archive_";
    $new_fields = array(
        array(
            'name'        		=> 'Связанный пост - заставка', //to do for private only
            'id'          		=> $prefix.'cover_post',
            'type'        		=> 'post_search_text', // This field type
            'post_type'  		=> array('project', 'event', 'post'),
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
        array(
            'id'   => $prefix.'post_type',
            'type' => 'hidden',
            'default' => ''
        )
    );

    return array_merge( $fields, $new_fields );
}

/** Triple block - picture/panel/card **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}

/** Tripple block - picture/panel/panel **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-labels'
		)
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Double block - 1 element: picture / text **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
	);

	return array_merge( $fields, $new_fields );
}

/** Triple block - people/panel/panel **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}

/** Triple block - panel/picture/person **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}


/** Double block - picture/people **/
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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-labels'
		)
	);

	return array_merge( $fields, $new_fields );
}

/** Double block - picture/picture **/
add_filter( 'wds_page_builder_fields_doubleblock-picturepicture', 'tst_add_doubleblock_picturepicture_field' );
function tst_add_doubleblock_picturepicture_field( $fields ) {

	$prefix = "doubleblock_picturepicture_";
	$new_fields = array(
		array(
			'name'        		=> 'Элемент - 1',
			'id'          		=> $prefix.'element1_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name' 				=> 'Положение ссылки - 1',
			'id' 				=> $prefix.'label1_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left_bottom',
			'options' 			=> array(
				'left_bottom'	=> 'Левый нижний угол',
				'left_top' 		=> 'Левый верхний угол',
			)
		),
		array(
			'name'        		=> 'Элемент',
			'id'          		=> $prefix.'element2_post',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name' 				=> 'Положение ссылки - 2',
			'id' 				=> $prefix.'label2_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left_bottom',
			'options' 			=> array(
				'left_bottom'	=> 'Левый нижний угол',
				'left_top' 		=> 'Левый верхний угол',
			)
		),
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}


/** Double block - picture3_4/panel **/
add_filter( 'wds_page_builder_fields_threeforth-picture', 'tst_add_threeforth_picture_field' );
function tst_add_threeforth_picture_field( $fields ) {

	$prefix = "threeforth_picture_";

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
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),
		array(
			'name'        		=> 'Элемент - плашка', //to do for private only
			'id'          		=> $prefix.'element2_post',
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
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
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}


/** Double block - picture3_4/person **/
add_filter( 'wds_page_builder_fields_threeforth-person', 'tst_add_threeforth_person_field' );
function tst_add_threeforth_person_field( $fields ) {

	$prefix = "threeforth_person_";
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
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),

		array(
			'name' 				=> 'Положение ссылки - на картинке',
			'id' 				=> $prefix.'label1_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left_bottom',
			'options' 			=> array(
				'left_bottom'	=> 'Левый нижний угол',
				'right_top' 	=> 'Правый верхний угол',
			)
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
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-labels'
		)
	);

	return array_merge( $fields, $new_fields );
}


/** Double block - picture3_4/section **/
add_filter( 'wds_page_builder_fields_threeforth-section', 'tst_add_threeforth_section_field' );
function tst_add_threeforth_section_field( $fields ) {

	$prefix = "threeforth_section_";
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
			'desc'				=> 'Укажите элемент или файл публикации (ниже)',
			'type'        		=> 'post_search_text', // This field type
			'post_type'   		=> array('project', 'event', 'landing', 'post', 'leyka_campaign', 'page'),
			'select_type' 		=> 'radio',
			'select_behavior' 	=> 'replace'
		),

		array(
			'name' 				=> 'Положение ссылки - на картинке',
			'id' 				=> $prefix.'label1_order',
			'type' 				=> 'select',
			'show_option_none'	=> false,
			'default' 			=> 'left_bottom',
			'options' 			=> array(
				'left_bottom'	=> 'Левый нижний угол',
				'right_top' 	=> 'Правый верхний угол',
			)
		),
		array(
			'name'        		=> 'Раздел', //to do for private only
			'id'          		=> $prefix.'section',
			'desc'				=> 'Укажите ярлык раздела',
			'type'        		=> 'text'
		),
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-labels'
		)
	);

	return array_merge( $fields, $new_fields );
}



/** News block **/
add_filter( 'wds_page_builder_fields_news', 'tst_add_news_field' );
function tst_add_news_field( $fields ) {

	$prefix = "news_";
	$new_fields = array(
		array(
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-1-panels__color-2-panels'
		)
	);

	return array_merge( $fields, $new_fields );
}

add_filter( 'wds_page_builder_fields_homenews', 'tst_add_homenews_field' );
function tst_add_homenews_field( $fields ) {

	$prefix = "homenews_";
	$new_fields = array(
		array(
			'name'    => 'Публикация 1 - файл',
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
			'name'    => 'Публикация 2 - файл',
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
			'id'      => $prefix.'img1_file',
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
			'id'      => $prefix.'img2_file',
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
			'id'   => $prefix.'color_scheme',
			'type' => 'hidden',
			'default' => 'color-donation'
		)
	);

	return array_merge( $fields, $new_fields );
}




//temp
function tst_wds_get_field_name_prefix( $regex, $filter ) {
    preg_match( $regex, $filter, $matches);
    $name_prefix = '';
    if( isset( $matches[1] ) && $matches[1] ) {
        $name_prefix = $matches[1];
        $name_prefix = str_replace( '-', '_', $name_prefix );
    }
    return $name_prefix;
}
