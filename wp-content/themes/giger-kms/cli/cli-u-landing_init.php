<?php
/** Update post_type/term structure 
 *
 *  landings
 *  
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
    $time_start = microtime(true);
    include('cli_common.php');
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;

    $landing_count = 0;
    
    # create test landing if not exist
    $landing_name = 'testovyj-lending';
    
    $landing = tst_get_pb_post( $landing_name, 'landing' );
    $landing_data = array();
    
    if( $landing ) {
        $landing_data['ID'] = $landing->ID;
    }
    
    $landing_data['post_title'] = 'Тестовый лэндинг';
    $landing_data['post_parent'] = 0;
    $landing_data['post_type'] = 'landing';
    $landing_data['post_content'] = '';
    $landing_data['post_status'] = 'publish';
    $landing_data['post_name'] = $landing_name;
    
    $landing_pb_meta = array ( 0 => array(
        'template_group' => 'col3-section',
        'col3_post_type_col1' => 'post',
        'col3_post_id_col1' => 'ekologicheskij-tsentr-dront-okazyvaet-sodejstvie-komitetu-ohrany-prirody-i-upravleniya-prirodopolzovaniem-nizhegorodskoj-oblasti-v-rasprostranenii-unikalnoj-knigi-pozvonochnye-zhivotnye-nizhegorodskoj',
        'col3_post_type_col2' => 'post',
        'col3_post_id_col2' => 'vyshlo-v-svet-posobie-zashhita-ekologicheskih-prav-grazhdan-pri-realizatsii-gradostroitelnoj-deyatelnosti-nizhegorodskij-opyt',
        'col3_post_type_col3' => 'import',
        'col3_post_id_col3' => 'operativnaya-sluzhba-ohrany-prirody-2',
    ) );
    $landing_data['meta_input'] = array( '_wds_builder_template' => $landing_pb_meta );
    
    $landing_id = wp_insert_post( $landing_data );
    $landing_count++;
    # end test landing
    
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