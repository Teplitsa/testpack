<?php
/** Class to manage urls and redirects
 * */

class TST_URL {

    private static $CUSTOM_REDIRECTS_REGEXP = array( 
        
        # change these rules to tune redirects 
        'wp\/?\?p=100' => 'item/co-infections',
        'wp\/?\?p=98' => 'item/co-infections',
        
        
        'wp\/?\?p=1403' => 'our-projects',
        'wp\/?\?p=1512' => 'our-projects',
        'wp\/?\?p=1521' => 'our-projects',
        'wp\/proekty' => 'our-projects',
        'wp\/proekty\/1-dekabrya-2008-goda' => 'item/events-results/december-day-2008',
        'wp\/proekty\/forum-soobshhestva-pacientov-s-vich' => 'item/forum-patients',
        'wp\/proekty\/konkurs-maketa-bannera' => 'our-projects',
        'wp\/proekty\/meropriyatiya' => 'item/events-results/memory-day-2009',
        'wp\/proekty\/organizaciya-modeli-kompleksnoj-preemstvennoj-pomoshhi-semyam-s-detmi-rannego-vozrasta-zatronutym-problemoj-vich' => 'our-projects',
        'wp\/?\?p=33' => 'our-projects',
        
        
        'wp\/author\/admin' => 'news',
        'wp\/author\/admin\/page\/1' => 'news',
        'wp\/author\/admin\/page\/2' => 'news',
        'wp\/author\/admin\/page\/3' => 'news',
        'wp\/author\/admin\/page\/4' => 'news',
        'wp\/author\/admin\/page\/5' => 'news',
        'wp\/author\/admin\/page\/6' => 'news',
        'wp\/tag\/urok' => 'news',
        'wp\/tag\/arvt' => 'news',
        'wp\/tag\/deti' => 'news',
        'wp\/tag\/epidemiya' => 'news',
        'wp\/tag\/foto' => 'news',
        'wp\/tag\/gepatit' => 'news',
        'wp\/tag\/krasnaya-lenta' => 'news',
        'wp\/tag\/krasnoj' => 'news',
        'wp\/tag\/kushkul' => 'news',
        'wp\/tag\/lekarstva' => 'news',
        'wp\/tag\/lyudi' => 'news',
        'wp\/tag\/marafon' => 'news',
        'wp\/tag\/masshtab' => 'news',
        'wp\/tag\/novaya-zhizn' => 'news',
        'wp\/tag\/novayazhizn56' => 'news',
        'wp\/tag\/orenburg' => 'news',
        'wp\/tag\/organizatsiya' => 'news',
        'wp\/tag\/pereboi' => 'news',
        'wp\/tag\/podrostki' => 'news',
        'wp\/tag\/profilaktika' => 'news',
        'wp\/tag\/sajt' => 'news',
        'wp\/tag\/seminary' => 'news',
        'wp\/tag\/shkoly' => 'news',
        'wp\/tag\/spid' => 'news',
        'wp\/tag\/ucheniki' => 'news',
        'wp\/tag\/vich-pozitivnyh' => 'news',
        'wp\/tag\/zdorove' => 'news',
        'wp\/tag\/zhenskoe' => 'news',
        'wp\/tag\/zhizn' => 'news',
        'wp\/novosti\/meropriyatiya\/blagotvoritelnyj-spektakl' => 'news',
        'wp\/novosti\/meropriyatiya\/stigma-i-samostigma' => 'news',
        'wp\/novosti\/pravo-rebenka-na-profilaktiku-i-lechenie-vich-infekcii' => 'news',
        'wp\/novosti\/u-menya-vich-obnimi-menya-podderzhi' => 'news',
        'wp\/novosti\/u-menya-vich-obnimi-menya-podderzhi\/?\?replytocom=188' => 'news',
        'wp\/category\/gruppa-vzaimopomoshhi-2' => 'news',
        'wp\/category\/gruppa-vzaimopomoshhi-2\/page\/1' => 'news',
        'wp\/category\/gruppa-vzaimopomoshhi-2\/page\/2' => 'news',
        'wp\/category\/novosti' => 'news',
        'wp\/category\/novosti\/meropriyatiya' => 'news',
        'wp\/category\/novosti\/meropriyatiya\/page\/1' => 'news',
        'wp\/category\/novosti\/meropriyatiya\/page\/2' => 'news',
        'wp\/category\/novosti\/meropriyatiya\/page\/3' => 'news',
        'wp\/category\/novosti\/meropriyatiya\/page\/4' => 'news',
        'wp\/category\/novosti\/page\/1' => 'news',
        'wp\/category\/novosti\/page\/2' => 'news',
        'wp\/category\/novosti\/page\/3' => 'news',
        'wp\/category\/novosti\/page\/4' => 'news',
        'wp\/category\/novosti\/page\/5' => 'news',
        'wp\/category\/novosti\/page\/6' => 'news',
        'wp\/category\/novosti\/proekty' => 'news',
        'wp\/category\/o-nas' => 'news',
        'wp\/category\/pravo-na-zdorove' => 'news',
        
        
        'wp\/pravo-na-zdorove' => 'health-rights',
        'wp\/pravo-na-zdorove\/adresa' => 'health-rights',
        'wp\/pravo-na-zdorove\/dispanserizatsiya' => 'health-rights',
        'wp\/pravo-na-zdorove\/gepatit' => 'health-rights',
        'wp\/pravo-na-zdorove\/lechenie-arvt' => 'health-rights',
        'wp\/pravo-na-zdorove\/materinstvo' => 'health-rights',
        'wp\/pravo-na-zdorove\/posle-mls' => 'health-rights',
        'wp\/pravo-na-zdorove\/tajna-diagnoza' => 'health-rights',
        'wp\/pravo-na-zdorove\/tuberkulyoz' => 'health-rights',
        'wp\/pravo-na-zdorove\/zhenskie-sekrety' => 'health-rights',
        
        
        'wp\/o-nas\/izmeneniya-v-ano-novaya-zhizn' => 'about-us',
        'wp\/o-nas\/ya-otdam-vse-svoi-dengi-chtob-izobreli-lekarstvo' => 'about-us',
        'wp\/?\?p=182' => 'about-us',
        'wp\/?\?p=219' => 'about-us',
        'wp\/about\/contakts' => 'about-us',
        'wp\/about\/principy-ano-novaya-zhizn' => 'about-us',
        'wp\/?\?p=1537' => 'about-us',
        'wp\/about\/442-fz' => 'about-us',
        'wp\/?\?p=102' => 'about-us',
        'wp\/?\?p=182' => 'about-us',
        
        
        'wp\/gruppa-vzaimopomoshhi\/kontakty-gruppy' => 'support-group',
        'wp\/?\?p=196' => 'support-group',
        'wp\/?\?p=1544' => 'support-group',
        'wp\/?\?p=2' => 'support-group',
        
        
        'wp\/?\?p=1488' => '',
        'wp\/?\?p=1495' => '',
        'wp\/?\?p=1594' => '',
        'wp\/?\?p=1605' => '',
        'wp\/?\?p=1615' => '',
        
        
        'wp\/?\?p=194' => 'item\/support-group\/group-finance',
        'wp\/gruppa-vzaimopomoshhi\/kopilka' => 'item\/support-group\/group-finance',
        
        
        'wp\/?\?p=222' => 'item\/support-group\/group-testimonials',
        'wp\/gruppa-vzaimopomoshhi\/otzyvy' => 'item\/support-group\/group-testimonials',
        'wp\/gruppa-vzaimopomoshhi\/otzyvy\/?\?replytocom=60' => 'item\/support-group\/group-testimonials',
        
        
        'wp\/?\?p=286' => 'item\/support-group\/group-stat',
        'wp\/gruppa-vzaimopomoshhi\/statistika' => 'item\/support-group\/group-stat',
        
        
        'wp\/?\?p=96' => 'item\/pregnancy',
        
        
        'wp\/gruppa-vzaimopomoshhi\/znakomstva' => 'item\/dating',
        
        'wp' => '',
        'wp\/feed' => 'feed',
        'wp\/.*\/feed' => 'feed',
    );
    
    public static function get_custom_redirect( $request_uri ) {
//         echo $request_uri . "\n";
        
        $redirect = "";
        $matches = array();
        foreach( self::$CUSTOM_REDIRECTS_REGEXP as $old => $new ) {
            if( preg_match( '/^(' . $old . ')+\/?$/', $request_uri, $matches ) ) {
                $old_request_uri = isset( $matches[1] ) ? $matches[1] : "";
                $sub_uri = isset( $matches[2] ) ? $matches[2] : "";
                if( $old_request_uri ) {
                    $redirect = home_url( $new . $sub_uri . '/' );
                    break;
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

