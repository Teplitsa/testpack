<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bb
 */



/** Default filters **/
add_filter( 'tst_the_content', 'wptexturize'        );
add_filter( 'tst_the_content', 'convert_smilies'    );
add_filter( 'tst_the_content', 'convert_chars'      );
add_filter( 'tst_the_content', 'wpautop'            );
add_filter( 'tst_the_content', 'shortcode_unautop'  );
add_filter( 'tst_the_content', 'do_shortcode' );

add_filter( 'tst_the_title', 'wptexturize'   );
add_filter( 'tst_the_title', 'convert_chars' );
add_filter( 'tst_the_title', 'trim'          );

/* jpeg compression */
add_filter( 'jpeg_quality', create_function('', 'return 95;' ));

 
/** Custom excerpts  **/

/** more link */
function tst_continue_reading_link() {
	$more = tst_get_more_text();
	return '&nbsp;<a href="'. esc_url( get_permalink() ) . '"><span class="meta-nav">'.$more.'</span></a>';
}

function tst_get_more_text(){
	
	return __('More', 'tst')."&nbsp;&raquo;";
}

/** excerpt filters  */
add_filter( 'excerpt_more', 'tst_auto_excerpt_more' );
function tst_auto_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_length', 'tst_custom_excerpt_length' );
function tst_custom_excerpt_length( $l ) {
	return 30;
}

/** inject */
add_filter( 'get_the_excerpt', 'tst_custom_excerpt_more' );
function tst_custom_excerpt_more( $output ) {
	global $post;
	
	if(is_singular() || is_search())
		return $output;
	
	$output .= tst_continue_reading_link();
	return $output;
}


/** Current URL  **/
if(!function_exists('tst_current_url')){
function tst_current_url() {
   
    $pageURL = 'http';
   
    if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {$pageURL .= "s";}
    $pageURL .= "://";
   
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
   
    return $pageURL;
}
}


/** Extract posts IDs from query **/
function tst_get_posts_ids_from_query($query){
	
	$ids = array();
	if(!$query->have_posts())
		return $ids;
	
	foreach($query->posts as $qp){
		$ids[] = $qp->ID;
	}
	
	return $ids;
}


/** Favicon **/
function tst_favicon(){
	
	$favicon_test = WP_CONTENT_DIR. '/favicon.ico'; //in the root not working don't know why
    if(!file_exists($favicon_test))
        return;
        
    $favicon = content_url('favicon.ico');
	echo "<link href='{$favicon}' rel='shortcut icon' type='image/x-icon' >";
}
add_action('wp_head', 'tst_favicon', 1);
add_action('admin_head', 'tst_favicon', 1);
add_action('login_head', 'tst_favicon', 1);


/** Adds custom classes to the array of body classes **/
function tst_body_classes( $classes ) {
	

	return $classes;
}
//add_filter( 'body_class', 'tst_body_classes' );


/** Support for social icons in menu **/
add_filter( 'pre_wp_nav_menu', 'tst_pre_wp_nav_menu_social', 10, 2 );
function tst_pre_wp_nav_menu_social( $output, $args ) {
	if ( ! $args->theme_location || 'social' !== $args->theme_location ) {
		return $output;
	}

	// Get the menu object
	$locations = get_nav_menu_locations(); 
	$menu      = (isset($locations[ $args->theme_location ])) ? wp_get_nav_menu_object( $locations[ $args->theme_location ] ) : false;

	if ( ! $menu || is_wp_error( $menu ) ) {
		return $output;
	}

	$output = '';

	// Get the menu items
	$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

	// Set up the $menu_item variables
	_wp_menu_item_classes_by_context( $menu_items );

	// Sort the menu items
	$sorted_menu_items = array();
	foreach ( (array) $menu_items as $menu_item ) {
		$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
	}

	unset( $menu_items, $menu_item );

	// Supported social icons (filterable); [url pattern] => [css class]
	$supported_icons = apply_filters( 'tst_supported_social_icons', array(
		'instagram.com'      => 'icon-instagram',	
		'facebook.com'       => 'icon-facebook',		
		'twitter.com'        => 'icon-twitter',
		'vk.com'             => 'icon-vk',
		//'youtube.com'        => 'icon-youtube',		
		'odnoklassniki.ru'   => 'icon-ok',
		'ok.ru'              => 'icon-ok'
	));

	// Process each menu item
	foreach ( $sorted_menu_items as $item ) {
		$item_output = '';

		// Look for matching icons
		foreach ( $supported_icons as $pattern => $class ) {
			if ( false !== strpos( $item->url, $pattern ) ) {
				
				$icon = '<svg class="sh-icon"><use xlink:href="#'.$class.'" /></svg>';
				
				$item_output .= '<li class="' . esc_attr( str_replace( array('fa-', 'icon-'), '', $class ) ) . '">';
				$item_output .= '<a href="' . esc_url( $item->url ) . '">';				
				$item_output .= $icon;
				$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
				$item_output .= '</a></li>';
				break;
			}
		}

		// No matching icons
		if ( '' === $item_output ) {
			//$item_output .= '<li class="external-link-square">';
			//$item_output .= '<a href="' . esc_url( $item->url ) . '">';
			//$item_output .= '<i class="fa fa-fw fa-external-link-square">';
			//$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
			//$item_output .= '</i></a></li>';
		}

		// Add item to list
		$output .= $item_output;
		unset( $item_output );
	}

	// If there are menu items, add a wrapper
	if ( '' !== $output ) {
		$output = '<ul class="' . esc_attr( $args->menu_class ) . '">' . $output . '</ul>';
	}

	return $output;
}


/** Deregister taxonomy for object **/
if(!function_exists('deregister_taxonomy_for_object_type')):
function deregister_taxonomy_for_object_type( $taxonomy, $object_type) {
	global $wp_taxonomies;

	if ( !isset($wp_taxonomies[$taxonomy]) )
		return false;

	if ( ! get_post_type_object($object_type) )
		return false;
	
	foreach($wp_taxonomies[$taxonomy]->object_type as $index => $object){
		
		if($object == $object_type)
			unset($wp_taxonomies[$taxonomy]->object_type[$index]);
	}
	
	return true;
}
endif;


/** Mixed content fixes */
//add_filter('the_content', 'tst_http_the_content', 100);
function tst_http_the_content($html){
	
	if(false !== strpos($html, 'http:')){
		$html = str_replace('http://', '//', $html);
	}
	
	return $html;
}


/** Material fixes **/

// main menu
add_filter('nav_menu_link_attributes', 'tst_main_menu_link', 2, 4);
function tst_main_menu_link($atts, $item, $args, $depth){
	
	if($args->menu_class == 'mdl-navigation'){
		$atts['class'] = 'mdl-navigation__link';
	}
	
	return $atts;
}



/** Options in customizer **/
add_action('customize_register', 'tst_customize_register');
function tst_customize_register(WP_Customize_Manager $wp_customize) {

    //$wp_customize->add_section('tst_spec_settings', array(
    //    'title'      => __('Website settings', 'tst'),
    //    'priority'   => 30,
    //));
   

    $wp_customize->add_setting('default_thumbnail', array(
        'default'   => false,
        'transport' => 'refresh', // postMessage
    ));
	
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_thumbnail', array(
        'label'    => __('Default thumbnail', 'tst'),
        'section'  => 'title_tagline',
        'settings' => 'default_thumbnail',
        'priority' => 1,
    )));

    
    $wp_customize->add_setting('footer_text', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('footer_text', array(
        'type'     => 'textarea',		
        'label'    => __('Footer text', 'tst'),
        'section'  => 'title_tagline',
        'settings' => 'footer_text',
        'priority' => 30,
    ));
}


/** Facebook author tag **/
add_action('wp_head', 'tst_facebook_author_tag');
function tst_facebook_author_tag() {

	global $post;
	
	if(!is_singular('post'))
		return;
		
	$author = tst_get_post_author($post);
	if(!$author || is_wp_error($author))
		return;
	
	$fb = (function_exists('get_field') && $author) ? get_field('auctor_facebook', 'auctor_'.$author->term_id) : '';
	
	if(!empty($fb)) {
?>
	<meta property="article:author" content="<?php echo esc_url($fb);?>" />
<?php
	}
}

add_action('plugins_loaded', function(){

    if( !function_exists('get_field') ) {

        function get_field($name, $id = false) {

            if( !$id ) {

                global $post;
                if($post) {
                    $id = $post->ID;
                } else {
                    return '';
                }
            }

            return get_post_meta($id, $name, true);
        }
    }
});


/** Humans txt **/
class TST_Humans_Txt {
	
	private static $_instance = null;		
	
	private function __construct() {	
		
		add_action('init', array( $this, 'rewrite'));
		add_filter('redirect_canonical', array( $this, 'canonical'));
		add_action('template_redirect', array( $this, 'template_redirect'));
		add_action('wp_head', array($this, 'head_link'));
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }
		
        return self::$_instance;
    }
	
	
	public function rewrite() { //rewrite rules
		global $wp_rewrite, $wp;
		
		add_rewrite_rule('humans\.txt$', $wp_rewrite->index.'?humans=1', 'top');
		$wp->add_query_var('humans');
	}


	public function canonical($redirect) { //revome slash in link
		
		$humans = get_query_var( 'humans' );
		if (!empty($humans))
			return false;

		return $redirect;
	}
	
	public function head_link(){ // add link at header
					
		$url = esc_url(home_url('humans.txt'));
		echo "<link rel='author' href='{$url}'>\n";
	}

	public function template_redirect(){ //show text
	
		if(1 != get_query_var('humans'))
			return;
			
		//serve correct headers
		header( 'Content-Type: text/plain; charset=utf-8' ); 
	
		//prepare default content
		$content = "/* MADE BY */

The Project by Teplitsa. Technologies for Social Good
www: te-st.ru

Idea & Project Lead
Gleb Suvorov
suvorov.gleb[at]gmail.com

Design & Development:
Anna Ladoshkina 
webdev[at]foralien.com

Contributors:

Denis Cherniatev
denis.cherniatev[at]gmail.com

Lev Zvyagincev
ahaenor[at]gmail.com

Denis Kulandin
kulandin[at]gmail.com

Tools we use with admiration and love to make things real:
WordPress, MDL Framework, Gulp, SASS, Leyka

       _             _    _        _   
      /\ \          /\ \ /\ \     /\_\ 
     /  \ \____     \ \ \\ \ \   / / / 
    / /\ \_____\    /\ \_\\ \ \_/ / /  
   / / /\/___  /   / /\/_/ \ \___/ /   
  / / /   / / /   / / /     \ \ \_/    
 / / /   / / /   / / /       \ \ \     
/ / /   / / /   / / /         \ \ \    
\ \ \__/ / /___/ / /__         \ \ \   
 \ \___\/ //\__\/_/___\         \ \_\  
  \/_____/ \/_________/          \/_/  
";

		//make it filterable
		$content = apply_filters('humans_txt', $content);
		
		//correct line ends
		$content = str_replace("\r\n", "\n", $content);
		$content = str_replace("\r", "\n", $content);
		
		//output
		echo $content;		
		die();		
	}
	
} //class end

$humans = TST_Humans_Txt::get_instance();




