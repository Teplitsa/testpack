<?php

if( !class_exists('TST_Import') ) {

    class TST_Import {

        private static $convert2pdf_ext = array( 'doc', 'docx' );
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
                array(
                    'regexp' => '/[^\/]*(\d{4})[^\/]*\.\w+$/i',
                    'pattern' => '%Y',
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
                'post_type' => array( 'post', 'landing', 'project', 'event', 'person', 'import', 'archive_page' ), // wp does not search without post type
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
        
        public function get_post_by_meta_value( $meta_key, $meta_value ) {
            $args = array(
                'post_type' => array( 'post', 'landing', 'project', 'event', 'person', 'import', 'archive_page' ), // wp does not search without post type
                'post_parent' => 0,
                'meta_query' => array(
                    array(
                        'key' => $meta_key,
                        'value' => $meta_value,
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
            
//            $tmp_file = tempnam( sys_get_temp_dir(), 'dront_' );
//            set_time_limit(0);
//            $fp = fopen ( $tmp_file, 'w+' );
//            $ch = curl_init( $url );
//            curl_setopt( $ch, CURLOPT_TIMEOUT, 180 );
//            curl_setopt( $ch, CURLOPT_FILE, $fp ); 
//            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
//            curl_exec( $ch ); 
//            curl_close( $ch );
//            fclose($fp);

            $file = wp_remote_get($url, array('timeout' => 50, 'sslverify' => false));
            
//             if( !file_exists( $tmp_file ) ) {
//                 printf( "Download file ERROR!\n" );
//                 return $attachment_id;
//             }

            if( !is_wp_error($file) && isset($file['body']) ){
                
                $response_code = $file['response']['code'];
                
                if( $response_code == '200' && isset($file['headers']['content-type'])) {
//            
//            if( file_exists( $tmp_file ) ) {
//                if( true ) {
                    $filename = basename($url);
                    $upload_file = wp_upload_bits($filename, null, $file['body']);

                    if (!$upload_file['error']) {
                        $wp_filetype = wp_check_filetype($filename, null );

                        $attachment_title = preg_replace('/\.[^.]+$/', '', $filename);
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_parent' => 0,
                            'post_title' => $attachment_title,
                            'post_name' => 'datt-' . sanitize_title( $attachment_title ),
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
            else {
                printf( "Download file ERROR!\n" );
            }
            unset($file);

            return $attachment_id;
        }
        
        public function import_big_file( $url ) {
            $attachment_id = 0;
        
//             printf( "file url: %s\n", $url );
            
            $tmp_dir = sys_get_temp_dir() . '/tst_dront';
            if( !is_dir( $tmp_dir ) ) {
                mkdir( $tmp_dir );
            }
            
//             print_r( $tmp_dir ); echo "\n";
            
            $tmp_file = tempnam( $tmp_dir, 'dront_' );
            set_time_limit(0);
            $fp = fopen ( $tmp_file, 'w+' );
            
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 300 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt( $ch, CURLOPT_FILE, $fp );
            curl_exec( $ch );
            curl_close( $ch );
            fclose($fp);
            
//             printf( "file: %s\n", $tmp_file );
            
            $filename_no_ext = pathinfo( $url, PATHINFO_FILENAME );
            $extension = pathinfo( $url, PATHINFO_EXTENSION );
            $filedir = dirname( $tmp_file );
            $new_file = $filedir . "/" . $filename_no_ext . '.' . $extension;
            echo $new_file . "\n";
            
            rename( $tmp_file, $new_file );
            $tmp_file = $new_file;
            
            $attachment_id = tst_upload_file_from_path( $tmp_file );
//             printf( "res_att_id: %d\n", $attachment_id );

            if( file_exists( $tmp_file ) ) {
                unlink( $tmp_file );
            }
            
            if( $attachment_id ) {
                update_post_meta( $attachment_id, 'old_url', $url );
            }
            
            return $attachment_id;
        }
        
        public function import_local_file( $path ) {
            
            $attachment_id = tst_upload_file_from_path( $path );
            
            if( $attachment_id ) {
                update_post_meta( $attachment_id, 'old_url', $path );
            }
            
            return $attachment_id;
        }

        public function import_file_from_path($path) {

            if(!$path || !file_exists($path))
                return false;

            $attachment_id = false;
            $file = file_get_contents($path);

            if($file){

                $filename = basename($path);
                $upload_file = wp_upload_bits($filename, null, $file);

                if (!$upload_file['error']) {
                    $wp_filetype = wp_check_filetype($filename, null );

                    $attachment_title = preg_replace('/\.[^.]+$/', '', $filename);
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_parent' => 0,
                        'post_title' => $attachment_title,
                        'post_name' => 'datt-' . sanitize_title( $attachment_title ),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );
                }

            }

            return $attachment_id;
        }

        public function get_ext_from_url( $file_url ) {
            $matches = array();
            $ext = '';
            if( preg_match( '/.*\.(\w+)$/i', $file_url, $matches ) ) {
//            print_r( $matches );
                if( isset( $matches[1] ) ) {
                    $ext = $matches[1];
                }
            }

            printf( "ext: %s\n", $ext );
            return $ext;
        }

        public function is_must_convert2pdf( $file_url ) {
            $ext = $this->get_ext_from_url( $file_url );
            return in_array( $ext, self::$convert2pdf_ext );
        }

        public function replace_file_type_hints( $text ) {
            foreach( self::$convert2pdf_ext as $ext ) {
                $text = preg_replace( '/\('.$ext.'(\)|<)/', '(pdf$1', $text );
                $text = preg_replace( '/(\)|>)'.$ext.'\)/', '$1pdf)', $text );
            }
            return $text;
        }

        public function convert2pdf( $attachment_id, $localpdf = '' ) {
    
            $wp_upload_dir = wp_upload_dir();
            if( $localpdf ) {
                $localpdf = $wp_upload_dir['basedir'].'/localpdf';
            }
    
            $ret_attachment_id = $attachment_id;

            $original_file = get_attached_file( $attachment_id );
            $old_url = get_post_meta( $attachment_id, 'old_url', true );
            $old_parent_page_url = get_post_meta( $attachment_id, 'old_parent_page_url', true );

            $of_info = pathinfo( $original_file );
//        print_r( $of_info );

            $of_base_name = $of_info['basename'];
            $of_dir = $of_info['dirname'];
            $of_dir = str_replace( $wp_upload_dir['basedir'], '', $of_dir );
//        printf( "of_dir: %s\n", $of_dir );

            $new_file_prefix = preg_replace( '/\/$/', '', $of_dir );
            $new_file_prefix = preg_replace( '/^\//', '', $new_file_prefix );
            $new_file_prefix = str_replace( '/', '_', $new_file_prefix );
            $new_file_prefix = str_replace( '\\', '_', $new_file_prefix );
            $new_file_prefix = $new_file_prefix . '_';
            $tmp_dir = get_temp_dir();

            $new_file_base_name_no_prefix = preg_replace( '/\.\w+$/', '.pdf', $of_base_name );
            $new_file_base_name = $new_file_prefix . $new_file_base_name_no_prefix;
            $new_file = $tmp_dir . $new_file_base_name;
            $new_file_no_prefix = $tmp_dir . $new_file_base_name_no_prefix;

            printf( "original file: %s\n", $original_file );
            printf( "new PDF file NO PREFIX: %s\n", $new_file_no_prefix );
            printf( "new PDF file: %s\n", $new_file );

            if( $localpdf ) {
                $localpdf_file = preg_replace( '/\/$/', '', $localpdf) . '/' . $new_file_base_name;
                printf( "local PDF file: %s\n", $localpdf_file );
                if( file_exists( $localpdf_file ) ) {
                    copy( $localpdf_file, $new_file_no_prefix );
                }
                else {
                    printf( "local PDF not found\n" );
                }
            }
            else {
                TST_Convert2PDF::get_instance()->doc2pdf( $original_file, $new_file );
            }

            if( $localpdf && file_exists( $new_file_no_prefix ) ) {
                printf( "new file OK\n" );
                $new_attachment_id = $this->import_file_from_path( $new_file_no_prefix );
                printf( "converted attachment id: %s\n", $new_attachment_id );

                if( $new_attachment_id ) {
                    $ret_attachment_id = $new_attachment_id;

                    if( $old_url ) {
                        delete_post_meta( $attachment_id, 'old_url' );
                        update_post_meta( $new_attachment_id, 'old_url', $old_url );
                    }

                    if( $old_parent_page_url ) {
                        delete_post_meta( $attachment_id, 'old_parent_page_url' );
                        update_post_meta( $new_attachment_id, 'old_parent_page_url', $old_parent_page_url );
                    }

                    $attachment_terms = wp_get_post_terms( $attachment_id, 'attachment_tag' );
                    foreach( $attachment_terms as $tag ) {
                        wp_set_object_terms( $new_attachment_id, $tag->term_id, 'attachment_tag' );
                    }
                    wp_delete_object_term_relationships( $attachment_id, 'attachment_tag' );
                    unset( $attachment_terms );
                }

                unlink( $new_file_no_prefix );
            }
            elseif( file_exists( $new_file ) ) {
                printf( "SAVE local PDF\n");
                $this->copy_to_localpdf( $new_file, $new_file_base_name );
                unlink( $new_file );
            }
            else {
                printf( "NO local PDF: %d - %s\n", $attachment_id, $of_base_name );
            }

            return $ret_attachment_id;
        }

        public function copy_to_localpdf( $new_file, $new_file_base_name ) {
            $wp_upload_dir = wp_upload_dir();
            $pdf_dirname = $wp_upload_dir['basedir'].'/localpdf';
            if ( ! file_exists( $pdf_dirname ) ) {
                wp_mkdir_p( $pdf_dirname );
            }

            $localpdf_file = $pdf_dirname . '/' . $new_file_base_name;
            if( ! file_exists( $localpdf_file ) ) {
                printf( "new local PDF: %s\n", $localpdf_file );
                copy( $new_file, $localpdf_file );
            }
            else {
                printf( "localpdf exists: %s\n", $localpdf_file );
            }
        }

        public function remove_inline_styles( $content ) {
            $content = preg_replace('/(style\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content);
            $content = preg_replace('/(width\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content);
            $content = preg_replace('/(height\s*=\s*(?:"|\').*?(?:"|\'))/', '', $content);
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
            $res = preg_replace( '/\n/is', ' ', $res );
            $res = preg_replace( '/\s+/is', ' ', $res );
            $res = preg_replace( '/\s+/isu', ' ', $res );
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

            if( $file_date ) {
                printf( "file_date: %s\n", $file_date );
            }

            return $file_date;
        }

        public function get_exact_date_from_url( $url, $parse_rules ) {
            $file_date = '';

            foreach( $parse_rules as $k => $v ) {
                if( preg_match( $v['regexp'], $url, $matches ) ) {
                    if( isset( $matches[1] ) ) {
                        $date_str = $matches[1];
                        $file_time = strptime( $date_str, $v['pattern'] );
                        if( $file_time ) {
                            $month = $file_time['tm_mon'] + 1;
                            $year = $file_time['tm_year'] + 1900;
                            $day = $file_time['tm_mday'];
                            $file_date = sprintf( '%d-%02d-%02d', $year, $month, $day );
                        }
                    }
                    break;
                }
            }

            if( $file_date ) {
                printf( "file_date: %s\n", $file_date );
            }

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

            return count( $attachment ) ? $attachment[0] : 0;
        }

        public function clean_content_xpath( $content, $section ) {
            if( ( !isset( $section["clean_content_xpath"] ) || !is_array( $section["clean_content_xpath"] ) ) && isset( $section['xpath']['title'] ) ) {
                $section["clean_content_xpath"] = array();
            }

            if( isset( $section['xpath']['title'] ) ) {
                if( is_array( $section['xpath']['title'] ) ) {
//                foreach( $section['xpath']['title'] as $xp ) {
//                    $section["clean_content_xpath"][] = $xp;
//                }
                }
                else {
                    $section["clean_content_xpath"][] = $section['xpath']['title'];
                }
            }

            if( is_array( $section["clean_content_xpath"] ) ) {
                $dom = new DOMDocument( '1.0', 'UTF-8' );

                $dom->loadHTML( '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $content, LIBXML_NOWARNING | LIBXML_NOERROR );

                $nodes2delete = array();
                $xpath = new DomXPath($dom);

                foreach( $section["clean_content_xpath"] as $v ) {
                    if( !$v ) {
                        continue;
                    }
                    $nodes = $xpath->query( $v );
                    $node = $nodes ? $nodes->item(0) : NULL;
                    if( $node ) {
                        $nodes2delete[] = $node;
                    }
                }

                foreach( $nodes2delete as $element ) {
                    try {
                        if( $element->parentNode ) {
                            $element->parentNode->removeChild( $element );
                        }
                    }
                    catch (Exception $ex) {
                    }
                }

                $xpath = new DomXPath($dom);
                $body = $xpath->query( './/body' );
                $body = $body ? $body->item(0) : NULL;
                $content = $body ? $this->get_inner_html( $body ) : '';

                unset( $body );
                unset( $xpath );
                unset( $nodes2delete );

            }

            return $content;
        }

        public function clean_content_regexp( $content, $section ) {
            if( is_array( $section["clean_content_regexp"] ) ) {
                foreach( $section["clean_content_regexp"] as $regexp ) {
                    if( is_array( $regexp ) ) {
                        $limit = $regexp['limit'];
                        $regexp = $regexp['regexp'];
                    }
                    else {
                        $limit = -1;
                    }
                    $content = preg_replace( $regexp , "", $content, $limit );
                }
            }

            return $content;
        }

        public function get_inner_html(DOMNode $element) {
            $innerHTML = "";
            $children  = $element->childNodes;

            if( $children ) {
                foreach ($children as $child) {
                    $innerHTML .= $element->ownerDocument->saveHTML($child);
                }
            }

            return $innerHTML;
        }

        function url2base( $url ) {
            $base_url = preg_replace( '/\/[^\/]*$/', '', $url );
            return $base_url;
        }

        function clean_content( $content, $section ) {

            $content = $this->remove_script( $content );
            $content = $this->clean_content_regexp( $content, $section );
            $content = $this->clean_content_xpath( $content, $section );

            return $content;
        }

        function urls_rel2abs( $content, $base_url, $dront_site_url ) {
            $content = preg_replace('/(src|href)\s*=\s*(["\'])\s*(\/(?!\/)[^\"\' ]+)/', '\1=\2' . $dront_site_url . '\3', $content);
            $content = preg_replace('/(src|href)\s*=\s*(["\'])\s*((?!https?:\/\/)[^\"\' ]+)/', '\1=\2' . $base_url . '/\3', $content);
            return $content;
        }

        function get_headers_from_curl_response( $header_text ) {
            $headers = array();

            foreach (explode("\r\n", $header_text) as $i => $line) {
                if ($i === 0) {
                    $headers['STATUS'] = $line;
                    preg_match( "/HTT\w+\/\d+.\d+\s+(\d+)/", $line, $matches );
                    $headers['STATUS_CODE'] = trim( $matches[1] );
                }
                else {
                    if( $line && strpos( $line, ':' ) ) {
                        list ($key, $value) = explode(': ', $line);
                        $headers[$key] = $value;
                    }
                }
            }

            return $headers;
        }

        function remove_script( $html ) {
            $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            return $html;
        }

        public function maybe_import( $external_file_url ) {
        
            $exist_attachment = $this->get_attachment_by_old_url( $external_file_url );
            $attachment_id = 0;
        
            if( $exist_attachment ) {
                $file_id = $exist_attachment->ID;
                $file_url = wp_get_attachment_url($file_id);
                $attachment_id = $exist_attachment->ID;
        
                printf( "File exist %s\n", $file_url );
            }
            else {
                $attachment_id = $this->import_big_file( $external_file_url );
        
                if( $attachment_id ) {
                    $file_id = $attachment_id;
                    $file_url = wp_get_attachment_url( $attachment_id );
                    printf( "File saved %s\n", $file_url );
                }
                else {
                    printf( "IMPORT FILE ERROR!\n");
                }
            }
            unset( $exist_attachment );
        
            return $attachment_id;
        }
        
        public function maybe_import_local_file( $filename ) {
            $exist_attachment = TST_Import::get_instance()->get_attachment_by_old_url( $filename );
            $thumbnail_id = $exist_attachment ? $exist_attachment->ID : TST_Import::get_instance()->import_local_file( $filename );
            return $thumbnail_id;
        }
        
        
    } //class TST_Import

}


if( !class_exists('TST_ImportOldBereginya') ) {
    
    class TST_ImportOldBereginya {
        private static $_instance = null;
        
        private static $clean_content_xpath = array(
            ".//body/table[2]/tr/td[2]//a[starts-with(@href, '../')]",
            ".//body/table[2]/tr/td[2]//a[@href='/members/bereginya']",
            "(.//body/table[2]/tr/td)[1]",
            "(.//body/table[2]/tr/td)[3]",
            "(.//body/table)[3]",
            "(.//body/table)[1]",
            ".//body/p",
            ".//body//a[starts-with(@href, '/members')]",
            ".//body//a[@class='menu']",
        );

        private function __construct() {
        }

        public static function get_instance() {
            if( !self::$_instance ) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }
        
        public function clean( $dir ) {
            printf( "cleaning...\n" );
            
            $this->iterate_editions( $dir, "clean_edition" );
        }
        
        public function clean_edition( $edition_dir, $year, $month ) {
            printf( "clean edition: %s, %s, %s\n", $edition_dir, $year, $month);
            
            $files = scandir( $edition_dir );
            $edition_dir = preg_replace( '#/$#', '', $edition_dir );
            
            foreach($files as $file) {
                $file_path = $edition_dir . '/' . $file;
//                printf( "2clean candidate: %s\n", $file_path );
                if( file_exists( $file_path ) && preg_match( "#\.htm$#", $file_path ) ) {
//                    printf( "2clean: %s\n", $file_path );
                    $this->clean_file( $file_path );
                }
            }
        }

        public function convert( $dir ) {
            printf( "converting...\n" );
        }

        public function import( $dir ) {
            printf( "importing...\n" );
        }
        
        private function clean_file( $file ) {
            printf( "file: %s\n", $file );

            $dom = new DomDocument();
            libxml_use_internal_errors( true );
            $dom->loadHTMLFile( $file );
            
            $xpath = new DomXPath($dom);

            $nodes2delete = array();
            foreach( self::$clean_content_xpath as $v ) {
                if( !$v ) {
                    continue;
                }
                $nodes = $xpath->query( $v );
//                print_r( $nodes );
                
                $item_index = 0;
                while( $node = $nodes->item( $item_index ) ) {
                    $nodes2delete[] = $node;
                    $item_index += 1;
                }
                
            }

            foreach( $nodes2delete as $element ) {
                if( $element->parentNode ) {
                    $element->parentNode->removeChild( $element );
                }
            }
            
//            $clean_file = preg_replace( "/\.htm$/", "_clean.htm", $file );
            $clean_file = $file;
            $content = $dom->saveHTML();
            $content = mb_convert_encoding($content, 'iso-8859-1', 'HTML-ENTITIES');
            file_put_contents( $clean_file, $content);
            
//            printf( "clean file: %s\n", $clean_file );
        }

        private function get_years_dirs( $dir ) {
            return $this->get_date_dirs( $dir, $this->get_year_dir_regexp() );
        }
        
        private function get_months_dirs( $dir ) {
            return $this->get_date_dirs( $dir, $this->get_month_dir_regexp() );
        }
        
        private function get_date_dirs( $dir, $regexp ) {
            $files = scandir( $dir );
            $dir = preg_replace( '#/$#', '', $dir );
            $res_dirs = array();
            foreach($files as $file) {
                $file_path = $dir . '/' . $file;
                if( is_dir( $file_path ) && preg_match( $regexp, $file_path ) ) {
                    $res_dirs[] = $file_path;
                }
            }
            
            return $res_dirs;
        }
        
        private function get_year_from_dir( $dir ) {
            $year = '';
            if( preg_match( $this->get_year_dir_regexp(), $dir, $matches ) ) {
                $year = $matches[1];
            }
            return $year;
        }
        
        private function get_month_from_dir( $dir ) {
            $month = '';
            if( preg_match( $this->get_month_dir_regexp(), $dir, $matches ) ) {
                $month = $matches[1];
            }
            return $month;
        }
        
        private function get_year_dir_regexp() {
            return "#/(\d+)/?$#";
        }
        
        private function get_month_dir_regexp() {
            return "#/(\d+)(?:-\d+)?/?$#";
        }
        
        public function iterate_editions( $dir, $edition_action ) {
            $years_dirs = $this->get_years_dirs( $dir );
            foreach( $years_dirs as $year_dir ) {
                $year = $this->get_year_from_dir( $year_dir );
                $month_dirs = $this->get_months_dirs( $year_dir );
                foreach( $month_dirs as $month_dir ) {
                    $month = $this->get_month_from_dir( $month_dir );
                    call_user_func( array( $this, $edition_action ), $month_dir, $year, $month);
                }
            }
        }
        
    } //class TST_ImportOldBereginya

}

if( !class_exists('TST_Convert2PDF') ) {
    
    class TST_Convert2PDF {
        
        private static $_instance = null;
        
        private function __construct() {
        }

        public static function get_instance() {
            if( !self::$_instance ) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }
        
        public function doc2pdf( $src, $dst ) {
            $command = 'lowriter --headless --convert-to pdf:writer_pdf_Export --outdir %s %s';

            $src_info = pathinfo( $src );
            $dst_info = pathinfo( $dst );
//             print_r($dst_info);

            $original_file_copy = preg_replace( '/'.$dst_info['extension'].'$/', $src_info['extension'], $dst );
//             print_r( $original_file_copy . "\n" );
            copy( $src, $original_file_copy );
            $compiled_command = sprintf( $command, $dst_info['dirname'], $original_file_copy );
            printf( "%s\n", $compiled_command );

            system( $compiled_command );
            unlink( $original_file_copy );
        }
        
        public function htmldir2pdf( $dir, $dst ) {
            
        }
    }
    
    
}