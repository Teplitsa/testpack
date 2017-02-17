<?php
/** Update post_type/term structure 
 *
 *  landings
 *  
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

$landings_sections = array(
    'dront-ornotologlab' => array(
        array(
            'template_group' => 'cover-general',
            'cover_general_cover_post' => '',
        ),
        array(
            'template_group' => 'tripleblock-picture',
            'tripleblock_picture_block_order' => 'direct',
            'tripleblock_picture_element1_post' => array( 'obereg-activity', 'project' ),
            'tripleblock_picture_element2_post' => array( 'ecodom-2014', 'project' ),
            'tripleblock_picture_element2_file_id' => '',
            'tripleblock_picture_element3_post' => array( 'dozhit', 'project' ),
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
            'cover_general_cover_post' => array( 'ugrozy-lep', 'project' ),
        ),
        
//         array(
//             'template_group' => 'col3-3-3-6-section',
//             'col3_3_3_6_post_type_col1' => 'post',
//             'col3_3_3_6_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
//             'col3_3_3_6_post_type_col2' => 'post',
//             'col3_3_3_6_post_id_col2' => 'vyshlo-v-svet-posobie-zashhita-ekologicheskih-prav-grazhdan-pri-realizatsii-gradostroitelnoj-deyatelnosti-nizhegorodskij-opyt',
//             'col3_3_3_6_post_type_col3' => 'import',
//             'col3_3_3_6_post_id_col3' => 'operativnaya-sluzhba-ohrany-prirody-2',
//         ),
    ),
    'dront-nizhaes' => array(
        array(
            'template_group' => 'col1-section',
            'col1_post_type_col1' => 'post',
            'col1_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
        ),
    ),
    'dront-obereg' => array(
        array(
            'template_group' => 'col1-section',
            'col1_post_type_col1' => 'post',
            'col1_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
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
//                     printf( "%s - %s\n", $option_val[0], $option_val[1] );
                    $post = tst_get_pb_post( $option_val[0], $option_val[1] );
                    printf( "found-post: %d\n", $post->ID );
                    $landing_pb_meta[$section_index][$option_key] = $post ? $post->ID : '';
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
            foreach( $department_projects as $project ) {
                printf( "department_prject_title: %s\n", $project->post_title );
            }
//             $landing_pb_meta[0]['col1_post_type_col1'] = $project_department->post_type;
//             $landing_pb_meta[0]['col1_post_id_col1'] = $project_department->ID;
        }
        
        $landing_data['meta_input'] = array( '_wds_builder_template' => $landing_pb_meta );
        
        $landing_id = wp_insert_post( $landing_data );
        
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