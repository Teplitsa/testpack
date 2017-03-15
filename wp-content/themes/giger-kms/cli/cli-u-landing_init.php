<?php
/** Update post_type/term structure 
 *
 *  landings
 *  
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

$landings_sections = array(
    'dront-birds' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                array(
                    'template_group' => 'cover-general',
                    'cover_general_cover_post' => array( 'dront-birds', 'landing' ),
                    'cover_general_cover_file_id' => 'thumbnail',
                ),
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'dront-ornotologlab', 'landing' ),
                    'tripleblock_picture_element2_post' => array( 'birds-territ', 'project' ),
                    'tripleblock_picture_element2_file_id' => array( 'di-vesna-ptitsy-buklet-2014', 'attachment' ),
                    'tripleblock_picture_element3_post' => array( 'birds-kadastr', 'project' ),
                ),
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'revers',
                    'tripleblock_picture_element1_post' => array( 'birds-lep', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'ornotologlab-activity', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'ugrozy-lep', 'project' ),
                ),
                array(
                    'template_group' => 'singleblock-text',
                    'singleblock_text_title' => '20 млн. птиц',
                    'singleblock_text_subtitle' => 'гибнет ежегодно от поражения электическим током на воздушных линиях электропередачи в России',
                    'singleblock_text_summary' => '',
                    'singleblock_text_link_text' => 'Птицы и ЛЭП',
                    'singleblock_text_element_post' => array( 'ugrozy-lep', 'project' ),
                ),
                array(
                    'template_group' => 'singleblock-picture',
                    'singleblock_picture_label_order' => 'left_bottom',
                    'singleblock_picture_element_post' => array( 'dozhit', 'project' ),
                ),
                
                array(
                    'template_group' => 'doubleblock-element',
                    'doubleblock_element_picture_position' => 'left',
                    'doubleblock_element_title' => 'Удод',
                    'doubleblock_element_subtitle' => 'птица 2016 года',
                    'doubleblock_element_summary' => 'Удод - птица осторожная, но при этом она не из пугливых. Когда пернатый начинает нервничать или его что-то напугало, хохол распускается как веер.',
                    'doubleblock_element_link_text' => 'Читать подробнее',
                    'doubleblock_element_element_post' => array( 'portrety-prirody-2014', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-people2cards',
                    'tripleblock_people2cards_block_order' => 'direct',
                    'tripleblock_people2cards_element1_post' => array( 'portrety-prirody-2014', 'project' ),
                    'tripleblock_people2cards_element2_post' => array( 'portrety-prirody-2013', 'project' ),
                    'tripleblock_people2cards_people_ids' => array(
                        array( 'fufaeva-irina-vladimirovna', 'person' ),
                        array( 'anufrieva-nataliya-gennadievna', 'person' ),
                        array( 'temnuhin-valerij-borisovich', 'person' ),
                    ),
                ),
                
                array(
                    'template_group' => 'doubleblock-picturepeople',
                    'doubleblock_picturepeople_picture_position' => 'revers',
                    'doubleblock_picturepeople_element1_post' => array( 'rodnaya-priroda', 'project' ),
                    'doubleblock_picturepeople_people_ids' => array(
                        array( 'toropova-nataliya-lvovna', 'person' ),
                        array( 'chebotareva-olga-vasilevna', 'person' ),
                        array( 'sharlovskij-aleksej-valentinovich', 'person' ),
                    ),
                ),
                
                array(
                    'template_group' => 'news',
                ),
                
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    
    'dront-nizhaes' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                
                array(
                    'template_group' => 'cover-general',
                    'cover_general_cover_post' => '',
                ),
                
                array(
                    'template_group' => 'doubleblock-element',
                    'doubleblock_element_picture_position' => 'left',
                    'doubleblock_element_title' => 'Актуальный статус',
                    'doubleblock_element_subtitle' => '11.08.2016',
                    'doubleblock_element_summary' => 'Правительство РФ перенесло срок ввода Нижерогодской АЭС с 2025 на 2030 год. Планируемый тип оборудования ВВЭР-ТОИ. Планируемая мощность - 2510 МВт.',
                    'doubleblock_element_link_text' => 'Распоряжение от 1 августа 2016 г. № 1634-р',
                    'doubleblock_element_element_post' => array( 'publications-albums', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'revers',
                    'tripleblock_picture_element1_post' => array( 'metod-lep', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'centerpt-projects', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'striks-seminars', 'project' ),
                ),
                
                array(
                    'template_group' => 'singleblock-picture',
                    'singleblock_picture_label_order' => 'left_top',
                    'singleblock_picture_element_post' => array( 'zakon-lep', 'project' ),
                ),
                
                array(
                    'template_group' => 'singleblock-text',
                    'singleblock_text_title' => '2006 - 2016 гг.',
                    'singleblock_text_subtitle' => 'история АЭС в планах, согласованиях, отчетах',
                    'singleblock_text_summary' => '',
                    'singleblock_text_link_text' => 'Когда построим?',
                    'singleblock_text_element_post' => array( 'law-clinic', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'reptiles-activity', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'centerpt-projects', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'sochinskiy', 'project' ),
                ),
                
                array(
                    'template_group' => 'news',
                ),
                
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    
    'dront-chebpotop' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                
                array(
                    'template_group' => 'cover-general',
                    'cover_general_cover_post' => '',
                ),
                
                array(
                    'template_group' => 'singleblock-picture',
                    'singleblock_picture_label_order' => 'left_bottom',
                    'singleblock_picture_element_post' => array( 'ecodom-2010', 'project' ),
                ),
                
                array(
                    'template_group' => 'doubleblock-element',
                    'doubleblock_element_picture_position' => 'left',
                    'doubleblock_element_title' => 'Актуальный статус',
                    'doubleblock_element_subtitle' => '16.09.2013',
                    'doubleblock_element_summary' => 'Правительство РФ утвердило планы по расширению Чебоксарской ГЭС, Экоцентр Дронт направил в правительство официальный запрос.',
                    'doubleblock_element_link_text' => 'Комментарии экологов',
                    'doubleblock_element_element_post' => array( 'zakon-lep', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'revers',
                    'tripleblock_picture_element1_post' => array( 'metod-lep', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'vysok-vody', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'pishet-gorshkov', 'project' ),
                ),
                
                array(
                    'template_group' => 'singleblock-text',
                    'singleblock_text_title' => 'Чебпотоп',
                    'singleblock_text_subtitle' => 'Общественная кампания с 2010 года',
                    'singleblock_text_summary' => '',
                    'singleblock_text_link_text' => 'История вопроса',
                    'singleblock_text_element_post' => array( 'chebges-about', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'birds-defender', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'public-debates', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'obereg-activity', 'project' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-2cards',
                    'tripleblock_2cards_block_order' => 'revers',
                    'tripleblock_2cards_element1_post' => array( 'portrety-prirody-2013', 'project' ),
                    'tripleblock_2cards_element2_post' => array( 'velikie-reki', 'project' ),
                    'tripleblock_2cards_element3_post' => array( 'chebges-cuts', 'project' ),
                ),
                
                array(
                    'template_group' => 'news',
                ),
                
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    
    'dront-publications' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                
                array(
                    'template_group' => 'cover-archive',
                    'cover_archive_title' => 'Статьи и публикации',
                    'cover_archive_subtitle' => 'На базе экоцентра «Дронт» выпущено много книг и методических пособий.',
                    'cover_archive_url' => '/item/dront-publications/archive',
                ),
                
                array(
                    'template_group' => 'threeforth-archive',
                    'threeforth_archive_block_order' => 'direct',
                    'threeforth_archive_element1_post' => array( 'datt-rastenia-detyam', 'attachment' ),
                    'threeforth_archive_label1_order' => 'right_top',
                    'threeforth_archive_link_title' => 'Все публикации',
                    'threeforth_archive_link_url' => '/item/dront-publications/archive',
                ),
                
                array(
                    'template_group' => 'subtitle',
                    'subtitle_subtitle_text' => 'Прочие публикации',
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'datt-limits_to_growth', 'attachment' ),
                    'tripleblock_picture_element2_post' => array( 'datt-mir-mezhdu-dvuh-ekologiy', 'attachment' ),
                    'tripleblock_picture_element3_post' => array( 'datt-factor_four', 'attachment' ),
                ),
                
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'revers',
                    'tripleblock_picture_element1_post' => array( 'datt-chto-takoe-prirodoohrannoe-dvizhenie', 'attachment' ),
                    'tripleblock_picture_element2_post' => array( 'datt-zhitkov_buturlin', 'attachment' ),
                    'tripleblock_picture_element3_post' => array( 'datt-serebrovsky-1918', 'attachment' ),
                ),
                
                array(
                    'template_group' => 'cover-archive',
                    'cover_archive_title' => 'Ежемесячная газета «Берегиня»',
                    'cover_archive_subtitle' => '',
                    'cover_archive_url' => '/item/dront-bereginya/archive',
                ),
                
                array(
                    'template_group' => 'threeforth-archive',
                    'threeforth_archive_block_order' => 'direct',
                    'threeforth_archive_element1_post' => array( 'datt-16-11', 'attachment' ),
                    'threeforth_archive_label1_order' => 'right_top',
                    'threeforth_archive_link_title' => 'Все выпуски',
                    'threeforth_archive_link_url' => '/item/dront-bereginya/archive',
                ),
                
                array(
                    'template_group' => 'news',
                ),
                
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    
    'dront-bereginya' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                
                array(
                    'template_group' => 'cover-archive',
                    'cover_archive_title' => 'Ежемесячная газета «Берегиня»',
                    'cover_archive_subtitle' => '',
                    'cover_archive_url' => '/item/dront-bereginya/archive',
                ),
                
                array(
                    'template_group' => 'threeforth-archive',
                    'threeforth_archive_block_order' => 'direct',
                    'threeforth_archive_element1_post' => array( 'datt-16-11', 'attachment' ),
                    'threeforth_archive_label1_order' => 'right_top',
                    'threeforth_archive_link_title' => 'Все выпуски',
                    'threeforth_archive_link_url' => '/item/dront-bereginya/archive',
                ),
                
                array(
                    'template_group' => 'news',
                ),
            
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    'homepage' => array( 'post_type' => 'page', 
        'sections' => array(
            '_wds_builder_template' => array(
                array(
                    'template_group' => 'cover-minimal',
                    'cover_minimal_cover_post' => array( 'miting-spasem-dubravu', 'event' ),
                    'cover_minimal_cover_file_id' => 'striks.jpg',
                    'cover_minimal_cover_title' => '',
                    'cover_minimal_cover_desc' => '',
                ),
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'v-kerzhenskom-zapovednike-nachalsya-sezon-zimnih-lyzhnyh-ekskursij', 'post' ),
                    'tripleblock_picture_element2_post' => '',
                    'tripleblock_picture_element2_file_id' => array( 'datt-nk_1987_1', 'attachment' ),
                    'tripleblock_picture_element3_post' => array( 'donate', 'leyka_campaign' ),
                ),
                array(
                    'template_group' => 'homenews',
                    'homenews_element1_file_id' => 'datt-nk_1997_1',
                    'homenews_element2_file_id' => 'datt-nk_1995_1',
                ),
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'subtitle',
                    'subtitle_subtitle_text' => 'Сделано "Дронт"',
                ),
                array(
                    'template_group' => 'doubleblock-picturepicture',
                    'doubleblock_picturepicture_element1_post' => array( 'dront-chebpotop', 'landing' ),
                    'doubleblock_picturepicture_label1_order' => 'left_top',
                    'doubleblock_picturepicture_element2_post' => array( 'reptiles-photo', 'project' ),
                    'doubleblock_picturepicture_label2_order' => 'left_bottom',
                ),
                array(
                    'template_group' => 'singleblock-picture',
                    'singleblock_picture_element_post' => array( 'dront-bereginya', 'landing' ),
                    'singleblock_picture_label_order' => 'left_bottom',
                ),
                array(
                    'template_group' => 'threeforth-section',
                    'threeforth_section_block_order' => 'direct',
                    'threeforth_section_element1_post' => array( 'dront-ecomap', 'landing' ),
                    'threeforth_section_label1_order' => 'right_top',
                    'threeforth_section_section' => 'departments',
                ),
                
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
    
    'dront-urban' => array(
        'sections' => array(
            '_wds_builder_template' => array(
                array(
                    'template_group' => 'cover-general',
                    'cover_general_cover_post' => array( 'dront-birds', 'landing' ),
                    'cover_general_cover_file_id' => 'thumbnail',
                ),
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'direct',
                    'tripleblock_picture_element1_post' => array( 'dront-ornotologlab', 'landing' ),
                    'tripleblock_picture_element2_post' => array( 'birds-territ', 'project' ),
                    'tripleblock_picture_element2_file_id' => array( 'di-vesna-ptitsy-buklet-2014', 'attachment' ),
                    'tripleblock_picture_element3_post' => array( 'birds-kadastr', 'project' ),
                ),
                array(
                    'template_group' => 'tripleblock-picture',
                    'tripleblock_picture_block_order' => 'revers',
                    'tripleblock_picture_element1_post' => array( 'birds-lep', 'project' ),
                    'tripleblock_picture_element2_post' => array( 'ornotologlab-activity', 'project' ),
                    'tripleblock_picture_element2_file_id' => '',
                    'tripleblock_picture_element3_post' => array( 'ugrozy-lep', 'project' ),
                ),
                array(
                    'template_group' => 'singleblock-text',
                    'singleblock_text_title' => '20 млн. птиц',
                    'singleblock_text_subtitle' => 'гибнет ежегодно от поражения электическим током на воздушных линиях электропередачи в России',
                    'singleblock_text_summary' => '',
                    'singleblock_text_link_text' => 'Птицы и ЛЭП',
                    'singleblock_text_element_post' => array( 'ugrozy-lep', 'project' ),
                ),
                array(
                    'template_group' => 'singleblock-picture',
                    'singleblock_picture_label_order' => 'left_bottom',
                    'singleblock_picture_element_post' => array( 'dozhit', 'project' ),
                ),
        
                array(
                    'template_group' => 'doubleblock-element',
                    'doubleblock_element_picture_position' => 'left',
                    'doubleblock_element_title' => 'Удод',
                    'doubleblock_element_subtitle' => 'птица 2016 года',
                    'doubleblock_element_summary' => 'Удод - птица осторожная, но при этом она не из пугливых. Когда пернатый начинает нервничать или его что-то напугало, хохол распускается как веер.',
                    'doubleblock_element_link_text' => 'Читать подробнее',
                    'doubleblock_element_element_post' => array( 'portrety-prirody-2014', 'project' ),
                ),
        
                array(
                    'template_group' => 'tripleblock-people2cards',
                    'tripleblock_people2cards_block_order' => 'direct',
                    'tripleblock_people2cards_element1_post' => array( 'portrety-prirody-2014', 'project' ),
                    'tripleblock_people2cards_element2_post' => array( 'portrety-prirody-2013', 'project' ),
                    'tripleblock_people2cards_people_ids' => array(
                        array( 'fufaeva-irina-vladimirovna', 'person' ),
                        array( 'anufrieva-nataliya-gennadievna', 'person' ),
                        array( 'temnuhin-valerij-borisovich', 'person' ),
                    ),
                ),
        
                array(
                    'template_group' => 'doubleblock-picturepeople',
                    'doubleblock_picturepeople_picture_position' => 'revers',
                    'doubleblock_picturepeople_element1_post' => array( 'rodnaya-priroda', 'project' ),
                    'doubleblock_picturepeople_people_ids' => array(
                        array( 'toropova-nataliya-lvovna', 'person' ),
                        array( 'chebotareva-olga-vasilevna', 'person' ),
                        array( 'sharlovskij-aleksej-valentinovich', 'person' ),
                    ),
                ),
        
                array(
                    'template_group' => 'news',
                ),
        
            ),
            
            '_wds_builder_cta_template' => array(
                array(
                    'template_group' => 'help',
                ),
            ),
            
        ),
    ),
);

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);
    
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    global $wpdb;

    $landing_count = 0;
    
    foreach( $landings_sections as $post_name => $post_pb_param ) {
        
        $post_type = isset( $post_pb_param['post_type'] ) ? $post_pb_param['post_type'] : 'landing';
        
        $landing = tst_get_pb_post( $post_name, $post_type );
        
        if( !$landing ) {
            printf( "%s %s not found!\n", $post_type, $post_name );
        }
        
        $landing_data = array();
        $landing_data['meta_input'] = array();
        
        if( $landing ) {
            $landing_data['ID'] = $landing->ID;
            $landing_data['post_title'] = $landing->post_title;
        }
        $landing_data['post_type'] = $post_type;
        $landing_data['post_status'] = 'publish';
        
        foreach( $post_pb_param['sections'] as $area_name => $landing_pb_meta ) {
        
            foreach( $landing_pb_meta as $section_index => $section ) {
                foreach( $section as $option_key => $option_val ) {
                    if( is_array( $option_val ) ) {
                        if( isset( $option_val[0] ) && is_array( $option_val[0] ) ) {
                            $posts_level2 = array();
                            foreach( $option_val as $option_key_level2 => $option_val_l2 ) {
                                if( is_array( $option_val_l2 ) ) {
                                    $post = tst_get_pb_post( $option_val_l2[0], $option_val_l2[1] );
    //                                 printf( "level2-found-post: %d\n", $post->ID );
                                    if( $post ) {
                                        $posts_level2[] = $post->ID;
                                    }
                                }
                            }
                            $landing_pb_meta[$section_index][$option_key] = implode( ', ', $posts_level2 );
                        }
                        else {
                            $post = tst_get_pb_post( $option_val[0], $option_val[1] );
    //                         printf( "level1-found-post: %d\n", $post->ID );
                            $landing_pb_meta[$section_index][$option_key] = $post ? $post->ID : '';
                        }
                    }
                    elseif( preg_match( '/_file_id$/', $option_key ) ) {
                        if( $option_val == 'thumbnail' ) {
                            if( $landing ) {
                                $thumbnail_id = get_post_thumbnail_id( $landing->ID );
                                $landing_pb_meta[$section_index][$option_key] = $thumbnail_id;
                            }
                        }
                        elseif( preg_match( '/^datt-/', $option_val ) ) {
                            $attachment = tst_get_pb_post( $option_val, 'attachment' );
                            $landing_pb_meta[$section_index][$option_key] = $attachment ? $attachment->ID : '';
                        }
                        elseif( is_numeric( $option_val ) && (int)$option_val ) {
                            $landing_pb_meta[$section_index][$option_key] = $option_val;
                        }
                        elseif( $option_val ) {
                            $thumbnail_id = TST_Import::get_instance()->maybe_import_local_file( dirname( __FILE__ ) . '/sideload/' . $option_val );
                            $landing_pb_meta[$section_index][$option_key] = $thumbnail_id;
                        }
                    }
                }
            }
            
            $department_projects = get_posts( array(
                'connected_type' => 'landing_project',
                'connected_items' => $landing,
                'connected_meta' => array(
                    array(
                        'key' => 'type',
                        'value' => 'department',
                    )
                )
            ) );
            
            $problem_projects = get_posts( array(
                'connected_type' => 'landing_project',
                'connected_items' => $landing,
                'connected_meta' => array(
                    array(
                        'key' => 'type',
                        'value' => 'problem',
                    )
                )
            ) );
            
            $direction_projects = get_posts( array(
                'connected_type' => 'landing_project',
                'connected_items' => $landing,
                'connected_meta' => array(
                    array(
                        'key' => 'type',
                        'value' => 'department',
                    )
                )
            ) );
            
            if( count( $department_projects ) ) {
    //             foreach( $department_projects as $project ) {
    //                 printf( "department_prject_title: %s\n", $project->post_title );
    //             }
    //             $landing_pb_meta[0]['col1_post_type_col1'] = $project_department->post_type;
    //             $landing_pb_meta[0]['col1_post_id_col1'] = $project_department->ID;
            }
            
    //         print_r( $landing_pb_meta );
            
            $landing_data['meta_input'][$area_name] = $landing_pb_meta;
        }
        
        $landing_id = $landing ? wp_update_post( $landing_data ) : wp_insert_post( $landing_data );
        printf( "post init ok: %d\n", $landing_id );
        
        if( is_wp_error($landing_id) ){
            echo $res->get_error_message() . "\n";
        }
        
        delete_post_meta( $landing->ID, 'color_scheme_' . $landing->post_type . '-' . $landing->post_name . '-archive' );
        delete_post_meta( $landing->ID, 'color_scheme_' . $landing->post_type . '-' . $landing->post_name . '-main-items' );
        
        
        $landing_count++;
        printf( "%s - %d - %s - done\n", $post_type, $landing_id, $post_name );
    }
    
    echo $landing_count." posts processed. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

    wp_cache_flush();



    //Cleanup
    echo 'Flush rewrite rules'.chr(10);
    flush_rewrite_rules(false);


    //Final
    echo 'Memory '.memory_get_usage(true).chr(10);
    echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
}
catch (TstNotCLIRunException $ex) {
    echo $ex->getMessage() . "\n";
}
catch (TstCLIHostNotSetException $ex) {
    echo $ex->getMessage() . "\n";
}
catch (Exception $ex) {
    echo $ex;
}