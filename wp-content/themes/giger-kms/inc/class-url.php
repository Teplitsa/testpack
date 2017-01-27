<?php
/** Class to manage urls and redirects
 * */

class TST_URL {

    private static $CUSTOM_REDIRECTS = array( 
        #'wp/about' => 'about-us',
    );
    
    public static function get_custom_redirect( $request_uri ) {
        $redirect = "";
        $matches = array();
        foreach( self::$CUSTOM_REDIRECTS as $old => $new ) {
            if( preg_match( '/^('.preg_quote($old, '/').')+(\/.*)?$/', $request_uri, $matches ) ) {
                $old_request_uri = isset( $matches[1] ) ? $matches[1] : "";
                $sub_uri = isset( $matches[2] ) ? $matches[2] : "";
                if( $old_request_uri ) {
                    $redirect = home_url( $new . $sub_uri . '/' );
                }
            }
        }
        return $redirect;
    }
    
    public static function add_protocol( $url ) {
        $url = preg_replace( '/^(http:|https:)/', '', $url );
        $url = self::get_site_protocol() . $url;
        return $url;
    }
    
    public static function get_site_protocol() {
        $site_protocol = preg_replace( '/(.*?)\/\/.*/', '\1', site_url() );
        return $site_protocol ? $site_protocol : ( is_ssl() ? 'https' : 'http' );
    }

    public static function get_site_domain_with_path() {
        if(defined('WP_CONTENT_URL') && WP_CONTENT_URL){
            $res = str_replace( array( 'http://', 'https://', '//', '/wp-content' ), '', WP_CONTENT_URL );
        }
        else {
            $res = get_site_url();
            $res = str_replace( array( 'http://', 'https://', '//', '/core' ), '', $res );
        }
        
        return $res;
    }
} //class

