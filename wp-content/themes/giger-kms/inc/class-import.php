<?php

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

				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_parent' => 0,
					'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
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
        $ret_attachment_id = $attachment_id;
        
        $original_file = get_attached_file( $attachment_id );
        $old_url = get_post_meta( $attachment_id, 'old_url', true );
        $old_parent_page_url = get_post_meta( $attachment_id, 'old_parent_page_url', true );
        
        $of_info = pathinfo( $original_file );
//        print_r( $of_info );
        
        $of_base_name = $of_info['basename'];
        $tmp_dir = get_temp_dir();
        
        $new_file_base_name = preg_replace( '/\.\w+$/', '.pdf', $of_base_name );
        $new_file = $tmp_dir . $new_file_base_name;
        printf( "new PDF file: %s\n", $new_file );
        
        if( $localpdf ) {
            $localpdf_file = preg_replace( '/\/$/', '', $localpdf) . '/' . $new_file_base_name;
            copy( $localpdf_file, $new_file );
            echo sprintf( "copy from: %s\n", $compiled_command );
        }
        else {
            $command = 'lowriter --headless --convert-to pdf:writer_pdf_Export --outdir %s %s';
            $compiled_command = sprintf( $command, substr( $tmp_dir, 0, -1 ), $original_file );
            echo sprintf( "%s\n", $compiled_command );
            
            system( $compiled_command );
        }

        if( file_exists( $new_file ) && $localpdf ) {
            printf( "new file OK\n" );
            $new_attachment_id = $this->import_file_from_path( $new_file );
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
            }
            
            unlink( $new_file );
        }
        elseif( file_exists( $new_file ) ) {
            $this->copy_to_localpdf( $new_file, $new_file_base_name );
            unlink( $new_file );
        }
        
        return $ret_attachment_id;
    }
    
    public function copy_to_localpdf( $new_file, $new_file_base_name ) {
        $upload_dir = wp_upload_dir();
        $pdf_dirname = $upload_dir['basedir'].'/localpdf';
        if ( ! file_exists( $pdf_dirname ) ) {
            wp_mkdir_p( $pdf_dirname );
        }
        
        $localpdf_file = $pdf_dirname . '/' . $new_file_base_name;
        if( ! file_exists( $localpdf_file ) ) {
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
        if( !is_array( $section["clean_content_xpath"] ) && isset( $section['xpath']['title'] ) ) {
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
} //class
