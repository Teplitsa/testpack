#!/usr/bin/php
<?
$USAGE="Usage: command -r [new domain]\n Example: php setupenv.php -r test.me\n";

error_reporting(1);

# --- Global variables -------------------------------------------
$search_dbstr = "http://orbi.local/";
$replace_dbstr  = "";
#------------------------------------------------------------------

/* CMD arguments */
#-------------
$opts = array(
	's:' => 'search:',
	'r:' => 'replace:',
);
$required = array(
	'r:'
);

$arg_count 	= $_SERVER[ 'argc' ];
$args_array = $_SERVER[ 'argv' ];
$short_opts = array_keys( $opts );

$long_opts = array_values( $opts );
$options = getopt( implode( '', $short_opts ), $long_opts );

# --- Options processing -------------------------------------------
if ( isset( $options[ 'help' ] ) ) {
	echo $USAGE;}

//If no arguments given
if ( $arg_count == 1 ) {
	echo $USAGE;
	exit;}

if (isset($options["s"])){
	$search_dbstr = $options["s"];}
        else{
		echo "using default value ".$search_dbstr." for option s\n";
		}

if (isset($options["r"])){
	$replace_dbstr = "http://".$options['r']."/";}
        else{
		echo "Abort! Option required, use -r\n";
		echo $USAGE;
		exit;}

# --- Script logic -------------------------------------------

/**
 * Search through your local wp-config.phpfile for a set of defines used to set up
 * WordPress db access.
 *
 * @return array    List of db connection details.
 */
function db_credentials( ) {

$filename = "wp-config.php";

if ( file_exists( $filename ) && is_file( $filename ) && is_readable( $filename ) ) {
	$file = @fopen( $filename, 'r' );
	$file_content = fread( $file, filesize( $filename ) );
	@fclose( $file );
	}

preg_match_all( '/define\s*?\(\s*?([\'"])(DB_NAME|DB_USER|DB_PASSWORD|DB_HOST|DB_CHARSET|DB_COLLATE)\1\s*?,\s*?([\'"])([^\3]*?)\3\s*?\)\s*?;/si', $file_content, $defines );
	if ( ( isset( $defines[ 2 ] ) && ! empty( $defines[ 2 ] ) ) && ( isset( $defines[ 4 ] ) && ! empty( $defines[ 4 ] ) ) ) {
		foreach( $defines[ 2 ] as $key => $define ) {
			switch( $define ) {
				case 'DB_NAME':
					$name = $defines[ 4 ][ $key ];
					break;
				case 'DB_USER':
					$user = $defines[ 4 ][ $key ];
					break;
				case 'DB_PASSWORD':
					$pass = $defines[ 4 ][ $key ];
					break;
				case 'DB_HOST':
					$host = $defines[ 4 ][ $key ];
					break;
				case 'DB_CHARSET':
					$char = $defines[ 4 ][ $key ];
					break;
				case 'DB_COLLATE':
					$coll = $defines[ 4 ][ $key ];
					break;
			}
		}
	}
	return array(
		'host' => $host,
		'name' => $name,
		'user' => $user,
		'pass' => $pass,
		'char' => $char,
		'coll' => $coll
	);
}

/**
 * Replaces specified strings in database
 *
 */
function db_replace($db_creds, $search, $replace ) { 
	//Install srdb package used for changing DB strings
	exec("composer require interconnectit/search-replace-db");

	//replace old URL with new one
	exec("php wp-content/vendor/interconnectit/search-replace-db/srdb.cli.php -v=true -h localhost -u ".$db_creds[user]." --name ".$db_creds['name']." -p ".$db_creds[pass]." -s ".$search." -r ".$replace);
	echo "php wp-content/vendor/interconnectit/search-replace-db/srdb.cli.php -v=true -h localhost -u ".$db_creds[user]." --name ".$db_creds['name']." -p ".$db_creds[pass]." -s=".$search." -r=".$replace."\n";

}

//Get DB credentials from wp-config.php fie
$db_creds=db_credentials();

//Run "composer install" command
exec("composer install");

//Import DB dump file
exec("mysql --user=".$db_creds[user]." --password=".$db_creds[pass]." ".$db_creds[name]." < ./_bak/orbilocal.sql");
echo "mysql --user=".$db_creds[user]." --password=".$db_creds[pass]." ".$db_creds[name]." < ./_bak/orbilocal.sql\n";

db_replace($db_creds, $search_dbstr, $replace_dbstr);

//TODO: need logic to add and edit .htaccess file

?>
