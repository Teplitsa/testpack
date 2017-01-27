<?php
/** Class to manage Categories
 * */

class TST_URL {

    public static $OLD_POST_TYPE_TO_NEW = array( 
        'announcement' => 'event',
        'news' => 'news',
        'ngo' => 'ngoprofile',
        'report' => 'report',
    );

    private static $CUSTOM_REDIRECTS = array( 
        'infoproducts/fedaral-news' => 'ngonews',
        'infoproducts/regional-news' => 'regions',
        'infoproducts/anonsy' => 'events',
        'infoproducts/ngolife' => 'ngolife',
        'infoproducts/opinion' => 'opinions',
        'infoproducts/articles' => 'articles',
        'news-submission' => 'notify',
        'subscribe-by-email/ezhednevnyj-dajdzhest' => 'subscribe',
        'subscribe-by-email/ezhenedelnyj-dajdzhest' => 'subscribe-weekly',
        'subscribe-by-email/spetsialnye-vypuski' => 'subscribe-special',
        'podpiska-na-rss' => 'subscribe-rss',
        'about/izdaniya' => 'agency/publications',
        'about/reports' => 'agency/reports',
        'about/editorial-policy' => 'editorial',
        'infoproducts/directors' => 'topic/directors',
        'feedback' => 'contacts',
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
} //class

