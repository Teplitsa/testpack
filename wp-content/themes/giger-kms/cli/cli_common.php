<?php
//error_reporting(0);

//Exceptions
class TstNotCLIRunException extends Exception {
}

class TstCLIHostNotSetException extends Exception {
}


function find_wordpress_base_path() {
    $dir = dirname(__FILE__);
    do {
        if( file_exists($dir."/wp-config.php") ) {
            return $dir;
        }
    } while( $dir = realpath("$dir/..") );
    return null;
}

define('BASE_PATH', find_wordpress_base_path()."/");
define('WP_USE_THEMES', false);
define('WP_CURRENT_THEME', 'asi-teplitsa'); //make this detectable how accurate this shoul be ???


if(php_sapi_name() !== 'cli') {
    throw new TstNotCLIRunException("Should be run from command line!");
}

$options = getopt("", array('host:', 'step:'));
$tst_host = isset($options['host']) ? $options['host'] : '';
$step = isset($options['step']) && $options['step'] >= 0 ? (int)$options['step'] : 1;

if(empty($tst_host)) {
    throw new TstCLIHostNotSetException("Host must be defined!");
}
else {

    $_SERVER = array(
        "HTTP_HOST" => $tst_host,
        "SERVER_NAME" => $tst_host,
        "REQUEST_URI" => "/",
        "REQUEST_METHOD" => "GET",
        "SERVER_PROTOCOL" => "http", // 'https',
		'SCRIPT_FILENAME' => 'index.php'
    );

//global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;

    require_once(BASE_PATH.'/core/wp-blog-header.php'); // Use actual root path to wp-blog-header.php
    header("HTTP/1.0 200 OK");
    echo "HOST: " . $tst_host . chr(10);
    /*
     * ATTENTION!!!!! WP CHANGES CURRENT SYSTEM DATE-TIME TO UTC INSIDE THE SCRIPT!!!!!!!!
     */
    echo "DATETIME: " . date( 'Y-m-d H:i:s' ) . chr(10);
    echo "gmt_offset=" . get_option('gmt_offset') . chr(10);
    echo "script_timezone=" . date('T') . chr(10);
    echo "timezone_string=" . get_option('timezone_string') . chr(10);
}