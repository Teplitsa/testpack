<?php add_action('init', function(){

    if(empty($_GET['iposts'])) {
        return;
    }

    $fp = @fopen('/home/dev/web/ngo2.ru/public_html/korablik/wp-content/themes/giger-kms/inc/import/posts2import.csv', 'r');
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
                    $post_id = wp_insert_post(array(
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

                    update_post_meta($post_id, '_old_site_id', $line[0]);
                    update_post_meta($post_id, '_old_site_thumbnail_url', $line[20]);

                    echo '<pre>' . print_r('Post #'.$line[0].' inserted - new ID is '.$post_id.'.', 1) . '</pre>';

                } else {

                    $post_id = $old_post->ID;
                    echo '<pre>' . print_r($line[0] . ' - post found: ' . $old_post->ID, 1) . '</pre>';
                }

                $uploads_dir = wp_upload_dir();
                if($line[20]) { // Thumbnail found

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
                        if($is_copied) {
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

                        $attach_id = wp_insert_attachment($attachment, $file_uploads_subdir.'/'.$new_site_filename, $post_id);
                        echo '<pre>Attach ID: ' . print_r($attach_id, 1) . '</pre>';

                        require_once(ABSPATH . 'wp-admin/includes/image.php');

                        $attach_data = wp_generate_attachment_metadata($attach_id, $post_id);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        set_post_thumbnail($post_id, $attach_id);
                    }

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