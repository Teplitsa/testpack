<?php

class TST_Import {
	private static $_instance = null;

	private function __construct() {
	}

	public static function get_instance() {
        if( !self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function get_post_by_old_url( $old_url ) {
        $args = array(
            'post_type' => array( 'post', 'landing', 'project', 'event', 'person', 'import', 'archive_page' ),
            'meta_query' => array(
                array(
                    'key' => 'old_url',
                    'value' => $old_url,
                )
            )
        );
        $posts = get_posts( $args );
        return count( $posts ) ? $posts[0] : null;
    }

    public function get_attachment_by_old_url( $old_url ) {
        $args = array(
            'post_type' => 'attachment',
            'meta_query' => array(
                array(
                    'key' => 'old_url',
                    'value' => $old_url,
                )
            )
        );
        $posts = get_posts( $args );
        return count( $posts ) ? $posts[0] : null;
    }
    
    public function set_attachment_old_page_url( $attachment_id, $old_page_url ) {
        update_post_meta( $attachment_id, 'old_parent_page_url', $old_page_url );
    }

    public function import_file( $url ) {
        $attachment_id = 0;
        //remote
        $file = wp_remote_get($url, array('timeout' => 50, 'sslverify' => false));

        $response_code = $file['response']['code'];

        if( !is_wp_error($file) && isset($file['body']) ){
            if( $response_code == '200' && isset($file['headers']['content-type'])) {

                $filename = basename($url);
                $upload_file = wp_upload_bits($filename, null, $file['body']);

                if (!$upload_file['error']) {
                    $wp_filetype = wp_check_filetype($filename, null );

                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_parent' => 0,
                        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'meta_input'	=> array(
                            'old_url' => $url,
                        ),
                    );

                    $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

                    if (!is_wp_error($attachment_id)) {
                        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                        wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                    }
                }
            }
        }
        unset($file);          
        
        return $attachment_id;
    }
    
    public function remove_inline_styles( $content ) {
        $content = preg_replace('/(style\s*=\s*(:?"|\').*?(:?"|\'))/', '', $content);
        $content = preg_replace('/(width\s*=\s*(:?"|\').*?(:?"|\'))/', '', $content);
        $content = preg_replace('/(height\s*=\s*(:?"|\').*?(:?"|\'))/', '', $content);
        return $content;
    }
    
    public function remove_url_tag( $url, $content ) {
        $content = preg_replace( '/<\s*img[^>]+' . preg_quote( $url, '/' ) . '.*?>/is', '', $content);
        $content = preg_replace( '/<\s*a[^>]+' . preg_quote( $url, '/' ) . '.*?>[^<]*?<\s*\/\s*a\s*>/is', '', $content);
        return $content;
    }
    
} //class
