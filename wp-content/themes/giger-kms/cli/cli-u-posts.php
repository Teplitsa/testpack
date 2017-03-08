<?php
/**
 * Set posts params or create post if not exist
 *
 **/
set_time_limit (0);
ini_set('memory_limit','512M');

$process_posts = array(
    // landings
    array(
        'post_type' => 'landing',
        'post_name' => 'dront-publications',
        'post_title' => 'Публикации',
        'post_excerpt' => 'На базе экоцентра «Дронт» выпущено много книг и методических пособий. Самые удачные работы мы публикуем в этом разделе — научные исследования, методички для школьников, студентов и преподавателей, отчеты о проделанной работе, результаты акций.',
        'post_content' => '',
    ),
    
    // attachments
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-rastenia-detyam',
        'post_date' => '2016-12-31',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-katalog-20gerbaria-20botsada-3',
        'post_date' => '2016-12-30',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-flora_nn_x',
        'post_date' => '2016-12-29',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-stepi',
        'post_date' => '2015-12-01',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-udod-2str',
        'post_date' => '2015-11-01',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-udod-1str',
        'post_date' => '2015-10-01',
    ),
    array(
        'post_type' => 'attachment',
        'post_name' => 'datt-kak-20zashitit-20derevo',
        'post_date' => '2015-10-05',
    ),
    
    // pages
    array(
        'post_type' => 'page',
        'post_name' => 'homepage',
        'post_title' => 'Экоцентр "Дронт"',
        'post_excerpt' => '',
        'post_content' => 'Помогает деятельности экологических НПО и активистов Нижегородской области',
        'meta_input' => array(
            'home_partners' => array (
                0 =>
                array (
                    'home_partner_title' => 'Международный социально-экологический союз',
                    'home_partner_url' => 'https://www.seu.ru',
                ),
                1 => array (
                    'home_partner_title' => 'Российский социально-экологический союз',
                    'home_partner_url' => 'https://rusecounion.ru',
                ),
                2 => array (
                    'home_partner_title' => 'Нижегородское отделение международного социально- экологического союза',
                    'home_partner_url' => 'https://www.nro-msoes.ru',
                ),
            )
        ),
    ),
    
    // leyka_campaign
    array(
        'post_type' => 'leyka_campaign',
        'post_name' => 'donate',
        'post_title' => 'Поддержите работу экоцентра «Дронт»',
        'post_excerpt' => 'Сделайте пожертвование на программы сохранения биоразнообразия',
        'post_content' => '',
    ),
);

try {
    $time_start = microtime(true);
    include('cli_common.php');
    
    echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

    global $wpdb;
    $posts_ok = 0;
    $posts_error = 0;
    
    foreach( $process_posts as $index => $post_data ) {
        
        if( !isset( $post_data['post_name'] ) || !$post_data['post_name'] ) {
            printf( "post_name must be defined\n" );
            continue;
        }
        
        if( !isset( $post_data['post_type'] ) || !$post_data['post_type'] ) {
            printf( "post_type must be defined\n" );
            continue;
        }
        
        $post = tst_get_pb_post( $post_data['post_name'], $post_data['post_type'] );
        
        $post_data['post_status'] = 'publish';
        
        if( isset( $post_data['post_content'] ) && preg_match( '/[-_a-zA-Z0-9].txt$/', $post_data['post_content'] ) ) {
            $fulltext_file_path = dirname( __FILE__ ) . '/data/txt/' . $post_data['post_content'];
            $post_data['post_content'] = file_exists( $fulltext_file_path ) ? file_get_contents( $fulltext_file_path ) : '';
        }
        
        if( isset( $post_data['post_date'] ) ) {
            $post_data['post_date_gmt'] = get_gmt_from_date( $post_data['post_date'] );
        }
        
        if( $post ) {
            $post_data['ID'] = $post->ID;
            
            if( !isset( $post_data['post_title'] ) ) {
                $post_data['post_title'] = $post->post_title;
            }
        }
        else {
            if( !isset( $post_data['post_title'] ) ) {
                printf( "post_title must be defined to insert post\n" );
                continue;
            }
        }
        
        $post_id = wp_insert_post( $post_data );
            
        if( $post_id && !is_wp_error( $post_id ) ) {
            $msg = $post ? 'post updated' : 'post inserted';
            $posts_ok += 1;
        }
        else {
            $msg = $post ? 'error update' : 'error insert';
            $posts_error += 1;
        }
        
        printf( "%s: %s - %s\n", $msg, $post_data['post_type'], $post_data['post_name'] );
    }
                
	printf( "\nPosts processed: %d; ok - %d; error - %d\n", count( $process_posts ), $posts_ok, $posts_error );

	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in sec: ' . (microtime(true) - $time_start).chr(10).chr(10);
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
