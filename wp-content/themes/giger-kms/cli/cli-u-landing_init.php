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
        
        array(
            'template_group' => 'help',
        ),
        
    ),
    
    'dront-nizhaes' => array(
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
        
        array(
            'template_group' => 'help',
        ),
        
    ),
    
    'dront-chebpotop' => array(
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
        
        array(
            'template_group' => 'help',
        ),
        
    ),
    
    'dront-publications' => array(
        array(
            'template_group' => 'news',
        ),
        
        array(
            'template_group' => 'help',
        ),
    ),
);

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $landing_count = 0;
    
    foreach( $landings_sections as $landing_name => $landing_pb_meta ) {
        
        $landing = tst_get_pb_post( $landing_name, 'landing' );
        
        if( !$landing ) {
            printf( "landing %s not found!\n", $landing_name );
        }
        
        $landing_data = array();
        
        if( $landing ) {
            $landing_data['ID'] = $landing->ID;
        }
        $landing_data['post_type'] = 'landing';
        $landing_data['post_status'] = 'publish';
        $landing_data['post_title'] = $landing->post_title;
        
        $landing_pb_meta = $landings_sections[ $landing_name ];
        
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
                elseif( in_array( $option_key, array( 'cover_general_cover_file_id' ) ) ) {
                    if( $option_val == 'thumbnail' ) {
                        $thumbnail_id = get_post_thumbnail_id( $landing->ID );
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
        
        $landing_data['meta_input'] = array( '_wds_builder_template' => $landing_pb_meta );
        
        $landing_id = wp_insert_post( $landing_data );
        printf( "landing init ok: %d\n", $landing_id );
        
        if( is_wp_error($landing_id) ){
            echo $res->get_error_message() . "\n";
        }
        
        $landing_count++;
        
        printf( "%d - %s - done\n", $landing_id, $landing_name );
    }
    
    echo $landing_count." landing pages processed. Time in sec: ".(microtime(true) - $time_start).chr(10).chr(10);

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