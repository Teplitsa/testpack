<?php add_action('init', function(){

    if(empty($_GET['icamp'])) {
        return;
    }

    $fp = @fopen('/home/dev/web/ngo2.ru/public_html/korablik/wp-content/themes/giger-kms/inc/import/campaigns2import.csv', 'r');
    if($fp) {

        $line_number = 1;
        $uploads_dir = wp_upload_dir();
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

                    echo '<pre>' . print_r($line[0].' - campaign found: '.$old_campaign->ID, 1) . '</pre>';

                    if($line[20]) {

                        $new_site_file = $uploads_dir['basedir'].'/'.$line[20];
                        $new_site_dirname = dirname($new_site_file);
                        $new_site_filename = ctl_sanitize_title(basename($new_site_file));

                        $file_uploads_subdir = str_replace('/'.basename($line[20]), '', $line[20]);
                        if( !file_exists($new_site_dirname.'/'.$new_site_filename) ) { // Thumbnail URL on the old site

                            if ( !file_exists($new_site_dirname) ) {
                                echo '<pre>Creating the directory: ' . print_r($new_site_dirname, 1) . '</pre>';
                                mkdir($new_site_dirname);
                            }

                            $is_copied = copy(
                                'http://www.korablik-fond.ru/wp-content/uploads/' . $line[20],
                                $new_site_dirname.'/'.$new_site_filename
                            );
                            if ($is_copied) {
                                echo '<pre>' . print_r('Thumbnail copied: ' . $new_site_dirname . '/' . $new_site_filename.', basedir: '.$file_uploads_subdir, 1) . '</pre>';
                            }

                        }

                        if(file_exists($new_site_dirname.'/'.$new_site_filename)) { // Insert the campaign attachment

                            echo '<pre>' . print_r('Creating media-library entry for file '.$new_site_dirname.'/'.$new_site_filename, 1) . '</pre>';
                            $filetype = wp_check_filetype($new_site_filename, null);

                            $attachment = array(
                                'post_mime_type' => $filetype['type'],
                                'post_title'     => preg_replace('/\.[^.]+$/', '', $new_site_filename),
                                'post_content'   => '',
                                'post_status'    => 'inherit',
                            );

                            $attach_id = wp_insert_attachment($attachment, $file_uploads_subdir.'/'.$new_site_filename, $old_campaign->ID);
                            echo '<pre>Attach ID: ' . print_r($attach_id, 1) . '</pre>';

                            require_once(ABSPATH . 'wp-admin/includes/image.php');

                            $attach_data = wp_generate_attachment_metadata($attach_id, $old_campaign->ID);
                            wp_update_attachment_metadata($attach_id, $attach_data);

                            set_post_thumbnail($old_campaign->ID, $attach_id);
                        }

                    }

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