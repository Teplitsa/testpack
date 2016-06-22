<?php add_action('init', function(){

    if(empty($_GET['icamp'])) {
        return;
    }

    $fp = @fopen('d:\dev\htdocs\korablik\wp-content\themes\giger-kms\inc\import\campaigns2import.csv', 'r');
    if($fp) {

        $line_number = 1;
        while(($line = fgetcsv($fp, 0, '|', '"')) !== false) {

            if($line_number == 1) { // fields and indexes
//                echo '<pre>' . print_r($line, 1) . '</pre>';
            } else {

                $old_campaign = get_posts(array(
                    'post_type' => 'leyka_campaign',
                    'post_status' => 'any',
                    'meta_query' => array(
                        array(
                            'key'     => '_old_site_id',
                            'value'   => $line[0],
                            'type'    => 'numeric',
                            'compare' => '=',
                        ),
                    ),
                ));

                $old_campaign = reset($old_campaign);

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
                    ), true);

                    update_post_meta($campaign_id, 'target_state', $line[67]);
                    update_post_meta($campaign_id, 'is_finished', !!$line[68]);
                    update_post_meta($campaign_id, 'campaign_target', $line[69]);
                    update_post_meta($campaign_id, 'campaign_template', $line[70]);
                    update_post_meta($campaign_id, 'payment_title', $line[71]);
                    update_post_meta($campaign_id, 'count_views', $line[77]);
                    update_post_meta($campaign_id, 'count_submits', $line[78]);
                    update_post_meta($campaign_id, 'total_funded', $line[90]);
                    update_post_meta($campaign_id, 'date_target_reached', $line[99]);

                    update_post_meta($campaign_id, '_old_site_id', $line[0]);
                    update_post_meta($campaign_id, '_old_site_thumbnail_url', $line[20]);

                    if(mb_stripos($line[4], 'проект') === false) {
                        if( !!$line[68] ) {
                            wp_set_object_terms($campaign_id, array(21, 19), 'campaign_cat');
                        } else {
                            wp_set_object_terms($campaign_id, array(21, 18), 'campaign_cat');
                        }
                    } else {
                        wp_set_object_terms($campaign_id, array(24), 'campaign_cat');
                    }

                    echo '<pre>' . print_r('Campaign #'.$line[0].' inserted - new ID is '.$campaign_id.'.', 1) . '</pre>';

                } else {

                    echo '<pre>' . print_r($line[0].' - campaign found:', 1) . '</pre>';
                    echo '<pre>' . print_r($old_campaign, 1) . '</pre>';

                }
            }

            $line_number++;
        }

        fclose($fp);

    } else {
        echo '<pre>' . print_r('File not found!', 1) . '</pre>';
    }
    die();
}, 1000);