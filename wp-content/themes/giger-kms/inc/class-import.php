<?php

class TST_Import {
    private static $date_from_url = array(
        'bereginya' => array(
            array(
                'regexp' => '/\/(\d+\/\d+-\d+).\w+$/i',
                'pattern' => '%Y/%y-%m',
            ),
            array(
                'regexp' => '/\/(\d+-\d+).\w+$/i',
                'pattern' => '%Y-%m',
            ),
            
        ),
        'report' => array(
            array(
                'regexp' => '/\/(\d{4})[^\/]*.\w+$/i',
                'pattern' => '%Y',
            ),
        ),
        'common' => array(
            array(
                'regexp' => '/(\d+-\d+-\d+).\w+$/i',
                'pattern' => '%d-%m-%y',
            ),
            
        ),
    );
    
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
            ),
            'fields'         => 'ids',
        );
        $posts = get_posts( $args );
        $post_id = count( $posts ) ? $posts[0] : null;
        return $post_id ? get_post( $post_id ) : null;
    }

    public function get_attachment_by_old_url( $old_url ) {
        $args = array(
            'post_type' => 'attachment',
            'meta_query' => array(
                array(
                    'key' => 'old_url',
                    'value' => $old_url,
                )
            ),
            'fields'         => 'ids',
        );
        $posts = get_posts( $args );
        $post_id = count( $posts ) ? $posts[0] : null;
        return $post_id ? get_post( $post_id ) : null;
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
    
    public function get_file_name( $url, $content ) {
        $title = '';
        
        $matches = array();
        preg_match( '/<a[^>]*' . preg_quote( $url, '/' ) . '.*?>(.*?)<\/a>/i', $content, $matches);
        $title = isset( $matches[1] ) ? $matches[1] : '';
        $title = $this->clean_string( $title );
        
        return $title;
    }
    
    public function clean_string( $s ) {
        $res = preg_replace( '/\r\n/is', ' ', $s );
        $res = preg_replace( '/\n/is', ' ', $s );
        $res = preg_replace( '/\s+/is', ' ', $s );
        $res = trim( strip_tags( $res ) );
        return $res;
    }
    
    public function get_date_from_url( $url, $parse_rules ) {
        $file_date = '';
        
        foreach( $parse_rules as $k => $v ) {
            if( preg_match( $v['regexp'], $url, $matches ) ) {
//                print_r( $matches );
                if( isset( $matches[1] ) ) {
                    $date_str = $matches[1];
                    $file_time = strptime( $date_str, $v['pattern'] );
                    if( $file_time ) {
                        $month = $file_time['tm_mon'] + 1;
                        $year = $file_time['tm_year'] + 1900;
                        $file_date = sprintf( '%d-%02d-01', $year, $month );
                    }
                }
                break;
            }
        }
        
        printf( "file_date: %s\n", $file_date );
        
        return $file_date;
    }
    
    public function set_file_date( $file_id, $url, $tag_slug = '' ) {
        $date_parse_rules = array();
        if( $tag_slug && isset( self::$date_from_url[ $tag_slug ] ) ) {
            $date_parse_rules[$tag_slug] = self::$date_from_url[ $tag_slug ];
        }
        
        if( !count( $date_parse_rules ) ) {
            $date_parse_rules = self::$date_from_url;
        }
        
        foreach( $date_parse_rules as $tag_slug => $parse_rules ) {
            $file_date = TST_Import::get_instance()->get_date_from_url( $url, $parse_rules );
            if( $file_date ) {
                update_post_meta( $file_id, 'file_date', $file_date );
                break;
            }
        }
    }
    
    function get_attachment_guid_by_url( $url ) {
        $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
        
        $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
        $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
        
        if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
            return;
        }
        
        return WP_CONTENT_URL . $parsed_url[1];
    }

    function get_attachment_id_by_url( $url ) {
        $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
        
        $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
        $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
        
        if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
            return;
        }
        
        global $wpdb;
        $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE %s;", '%' . $parsed_url[1] ) );
        
        return $attachment[0];
    }
    
} //class
