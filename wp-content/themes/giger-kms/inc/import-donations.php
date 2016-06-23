<?php add_action('init', function(){

    if(empty($_GET['idon'])) {
        return;
    }

    $fp = @fopen('d:\dev\htdocs\korablik\wp-content\themes\giger-kms\inc\import\donations2import.csv', 'r');
    if($fp) {

        $line_number = 1;
        $new_campaigns = array();
        $donations_skipped = 0;
        while(($line = fgetcsv($fp, 0, '|', '"')) !== false) {

            if($line_number == 1) { // fields and indexes
                echo '<pre>' . print_r($line, 1) . '</pre>';
            } else {

                $old_donation = get_posts(array(
                    'post_type' => 'leyka_donation',
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

                $old_donation = reset($old_donation);

                if( !$old_donation ) {

                    echo '<pre>' . print_r('Donation #'.$line[0].' not found, inserting...', 1) . '</pre>';

                    if(empty($new_campaigns[$line[59]])) {

                        $new_campaign = get_posts(array(
                            'post_type' => 'leyka_campaign',
                            'post_status' => 'any',
                            'meta_query' => array(
                                array(
                                    'key'     => '_old_site_id',
                                    'value'   => $line[59], // Old campaign ID
                                    'type'    => 'numeric',
                                    'compare' => '=',
                                ),
                            ),
                        ));
                        if($new_campaign) {

                            $new_campaign = reset($new_campaign);
                            $new_campaigns[$line[59]] = $new_campaign->ID;

                            echo '<pre>' . print_r('Campaign #'.$line[59].' found ('.$new_campaign->ID.')', 1) . '</pre>';

                        } else {

                            echo '<pre>' . print_r('Campaign with old ID #'.$line[59].' not found, skipping...', 1) . '</pre>';
                            $donations_skipped++;
                            continue;

                        }
                    }

                    $donation_id = wp_insert_post(array(
                        'post_parent' => $line[7],
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

                    update_post_meta($donation_id, 'leyka_donation_amount', $line[52]);
                    update_post_meta($donation_id, 'leyka_donation_currency', $line[53]);
                    update_post_meta($donation_id, 'leyka_main_curr_amount', $line[54]);
                    update_post_meta($donation_id, 'leyka_donor_name', $line[55]);
                    update_post_meta($donation_id, 'leyka_donor_email', $line[56]);
                    update_post_meta($donation_id, 'leyka_payment_method', $line[57]);
                    update_post_meta($donation_id, 'leyka_gateway', $line[58]);
                    update_post_meta($donation_id, 'leyka_campaign_id', $new_campaigns[$line[59]]);
                    update_post_meta($donation_id, '_leyka_donor_email_date', $line[60]);
                    update_post_meta($donation_id, '_leyka_managers_emails_date', $line[61]);
                    update_post_meta($donation_id, '_status_log', maybe_unserialize($line[62]));
                    update_post_meta($donation_id, 'leyka_payment_type', $line[63]);
                    update_post_meta($donation_id, 'leyka_recurrents_cancel_date', $line[64]);
                    update_post_meta($donation_id, 'leyka_gateway_response', $line[66]);
                    update_post_meta($donation_id, '_rebilling_is_active', $line[130]);

                    update_post_meta($donation_id, '_old_site_id', $line[0]);
                    update_post_meta($donation_id, '_old_site_thumbnail_url', $line[20]);

                    echo '<pre>' . print_r('Donation #'.$line[0].' inserted - new ID is '.$donation_id.'.', 1) . '</pre>';

                } else {

                    echo '<pre>' . print_r($line[0].' - donation found:', 1) . '</pre>';
                    echo '<pre>' . print_r($old_donation, 1) . '</pre>';

                }
            }

            $line_number++;
        }

//        echo '<pre>' . print_r($new_campaigns, 1) . '</pre>';
//        echo '<pre>' . print_r(count($new_campaigns), 1) . '</pre>';
        echo '<pre>Total donations scanned: ' . print_r($line_number-1, 1) . '</pre>';
        echo '<pre>Total donations skipped: ' . print_r($donations_skipped, 1) . '</pre>';

        fclose($fp);

    } else {
        echo '<pre>' . print_r('File not found!', 1) . '</pre>';
    }
    die();
}, 1000);