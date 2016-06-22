<?php add_action('init', function(){

    if(empty($_GET['iposts'])) {
        return;
    }

    $fp = @fopen('d:\dev\htdocs\korablik\wp-content\themes\giger-kms\inc\import\posts2import.csv', 'r');
    if($fp) {

        $line_number = 1;
        $campaigns_found = 0;
        while(($line = fgetcsv($fp, 0, '|', '"')) !== false) {

            if($line_number == 1) { // fields and indexes
                echo '<pre>' . print_r($line, 1) . '</pre>';
            } else {

                if($line[3] != 'publish') {
                    continue;
                }

                $leyka_old_campaign_id = array();
                $campaign_found = false;
                if(preg_match('/leyka_payment_form id=[0-9]+/', $line[5], $leyka_old_campaign_id)) {

                    $leyka_old_campaign_id = reset($leyka_old_campaign_id);
                    $leyka_old_campaign_id = str_replace('leyka_payment_form id=', '', $leyka_old_campaign_id);
//                    echo '<pre>' . $line[4].' - '.print_r($leyka_old_campaign_id, 1) . '<hr></pre>';

                    $new_site_campaign = get_posts(array(
                        'post_type' => 'leyka_campaign',
                        'post_status' => 'any',
                        'meta_query' => array(
                            array(
                                'key'     => '_old_site_id',
                                'value'   => $leyka_old_campaign_id,
                                'type'    => 'numeric',
                                'compare' => '=',
                            ),
                        ),
                    ));
                    if($new_site_campaign) {

                        $new_site_campaign = reset($new_site_campaign);
                        echo '<pre>New site campaign found by old ID: ' . print_r($new_site_campaign->post_title.' (old ID: '.get_post_meta($new_site_campaign->ID, '_old_site_id', true).')', 1) . '</pre>';
                        $campaign_found = true;

                    }

                }

                if( !$campaign_found ) { // Try to find by title

                    $new_site_campaign = get_posts(array(
                        'post_type' => 'leyka_campaign',
                        'post_status' => 'any',
                        's' => $line[4],
                    ));
                    echo '<pre>Search by title: '.$line[4]. '</pre>';
                    if($new_site_campaign) {

                        $new_site_campaign = reset($new_site_campaign);
                        echo '<pre>New site campaign found by title: ' . print_r($new_site_campaign->post_title.' (old ID: '.get_post_meta($new_site_campaign->ID, '_old_site_id', true).')', 1) . '</pre>';
                        $campaign_found = true;

                    } else {
                        echo '<pre>' . print_r($line[4]." ({$line[0]})", 1) . ' - campaign not found by title</pre>';
                    }
                }

                if($campaign_found) { // Update campaign's metadata

                    update_post_meta($new_site_campaign->ID, 'campaign_child_age', $line[49]);
                    update_post_meta($new_site_campaign->ID, 'campaign_child_city', $line[50]);
                    update_post_meta($new_site_campaign->ID, 'campaign_child_diagnosis', $line[51]);
//                update_post_meta($new_site_campaign->ID, '', $line[]);

                } else { // Insert new post

                    $old_post = get_posts(array(
                        'post_type' => 'post',
                        'meta_query' => array(
                            array(
                                'key'     => '_old_site_id',
                                'value'   => $line[0],
                                'type'    => 'numeric',
                                'compare' => '=',
                            ),
                        ),
                    ));

                    $old_post = reset($old_post);

                    if( !$old_post ) {

                        echo '<pre>' . print_r('Post #'.$line[0].' not found, inserting...', 1) . '</pre>';
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

//                        update_post_meta($campaign_id, 'target_state', $line[67]);
//                        update_post_meta($campaign_id, 'is_finished', !!$line[68]);
//                        update_post_meta($campaign_id, 'campaign_target', $line[69]);
//                        update_post_meta($campaign_id, 'campaign_template', $line[70]);
//                        update_post_meta($campaign_id, 'payment_title', $line[71]);
//                        update_post_meta($campaign_id, 'count_views', $line[77]);
//                        update_post_meta($campaign_id, 'count_submits', $line[78]);
//                        update_post_meta($campaign_id, 'total_funded', $line[90]);
//                        update_post_meta($campaign_id, 'date_target_reached', $line[99]);

                        update_post_meta($campaign_id, '_old_site_id', $line[0]);
                        update_post_meta($campaign_id, '_old_site_thumbnail_url', $line[20]);

                        echo '<pre>' . print_r('Post #'.$line[0].' inserted - new ID is '.$campaign_id.'.', 1) . '</pre>';

                    } else {

                        echo '<pre>' . print_r($line[0].' - post found:', 1) . '</pre>';
                        echo '<pre>' . print_r($old_post, 1) . '</pre>';

                    }
                }

                if($campaign_found) {
                    $campaigns_found++;
                }
            }

            $line_number++;
        }
        echo '<pre>Total campaigns found: ' . print_r($campaigns_found, 1) . '</pre>';
        echo '<pre>Total lines: ' . print_r($line_number-1, 1) . '</pre>';

        fclose($fp);

    } else {
        echo '<pre>' . print_r('File not found!', 1) . '</pre>';
    }
    die();
}, 1000);