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
            'template_group' => 'col1-section',
            'col1_post_type_col1' => 'post',
            'col1_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
        ),
        array(
            'template_group' => 'col3-6-3-3-section',
            'col3_6_3_3_post_type_col1' => 'post',
            'col3_6_3_3_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
            'col3_6_3_3_post_type_col2' => 'post',
            'col3_6_3_3_post_id_col2' => 'vyshlo-v-svet-posobie-zashhita-ekologicheskih-prav-grazhdan-pri-realizatsii-gradostroitelnoj-deyatelnosti-nizhegorodskij-opyt',
            'col3_6_3_3_post_type_col3' => 'import',
            'col3_6_3_3_post_id_col3' => 'operativnaya-sluzhba-ohrany-prirody-2',
        ),
        array(
            'template_group' => 'col3-3-3-6-section',
            'col3_3_3_6_post_type_col1' => 'post',
            'col3_3_3_6_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
            'col3_3_3_6_post_type_col2' => 'post',
            'col3_3_3_6_post_id_col2' => 'vyshlo-v-svet-posobie-zashhita-ekologicheskih-prav-grazhdan-pri-realizatsii-gradostroitelnoj-deyatelnosti-nizhegorodskij-opyt',
            'col3_3_3_6_post_type_col3' => 'import',
            'col3_3_3_6_post_id_col3' => 'operativnaya-sluzhba-ohrany-prirody-2',
        ),
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
        
        $project_department = TST_Import::get_instance()->get_post_by_meta_value( 'landing_department', $landing_name );
        $prokect_problem = TST_Import::get_instance()->get_post_by_meta_value( 'landing_problem', $landing_name );
        $prokect_direction =TST_Import::get_instance()->get_post_by_meta_value( 'landing_direction', $landing_name );
        
        if( $project_department ) {
            $landing_pb_meta[0]['col1_post_type_col1'] = $project_department->post_type;
            $landing_pb_meta[0]['col1_post_id_col1'] = $project_department->ID;
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