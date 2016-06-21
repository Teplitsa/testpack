<?php add_action('init', function(){

    if(empty($_GET['icamp'])) {
        return;
    }

    $fp = @fopen('d:\dev\htdocs\korablik\wp-content\themes\giger-kms\inc\import\campaigns2import.csv', 'r');
    if($fp) {

        $line_number = 1;
        while(($line = fgetcsv($fp, 0, '|', '"')) !== false) {

            if($line_number == 1) { // fields and indexes
                echo '<pre>' . print_r($line, 1) . '</pre>';
            } else {

                $old_campaign = get_posts(array('meta_key' => '_old_site_id', 'meta_value' => $line[0],));
                if( !$old_campaign ) {

                    echo '<pre>' . print_r('Campaign #'.$line[0].' not found, inserting...', 1) . '</pre>';
                    $campaign_id = wp_insert_post(array(
                        'post_date' => $line[1],
                        'post_content' => $line[5],
                        'post_title' => $line[4],
                        'post_excerpt' => $line[6],
                        'post_status' => $line[3],
                        'post_type' => $line[9],
                        'comment_status' => $line[12],
                        'ping_status' => $line[11],
                        'post_name' => $line[8],
                        'post_modified' => $line[2],
                        'meta_input' => array(
//                            '' => $line[],
//                            '' => $line[],
//                            '' => $line[],
//                            '' => $line[],
//                            '' => $line[],
//                            '' => $line[],
//                            '' => $line[],
                        ),
//                        '' => $line[],
//                        '' => $line[],
//                        '' => $line[],
                    ), true);
                }
            }


//            echo '<pre>' .$line_number.': '. print_r($fields, 1) . '</pre>';
            $line_number++;
        }

        fclose($fp);

    } else {
        echo '<pre>' . print_r('File not found!', 1) . '</pre>';
    }
    die();
});