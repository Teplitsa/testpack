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
//add_filter( 'pre_wp_nav_menu', 'tst_pre_wp_nav_menu_social', 10, 2 );
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
		'app.net'            => 'fa-adn',
		'behance.net'        => 'fa-behance',
		'bitbucket.org'      => 'fa-bitbucket',
		'codepen.io'         => 'fa-codepen',
		'delicious.com'      => 'fa-delicious',
		'deviantart.com'     => 'fa-deviantart',
		'digg.com'           => 'fa-digg',
		'dribbble.com'       => 'fa-dribbble',
		'facebook.com'       => 'fa-facebook',
		'flickr.com'         => 'fa-flickr',
		'foursquare.com'     => 'fa-foursquare',
		'github.com'         => 'fa-github',
		'gittip.com'         => 'fa-gittip',
		'plus.google.com'    => 'fa-google-plus-square',
		'instagram.com'      => 'fa-instagram',
		'jsfiddle.net'       => 'fa-jsfiddle',
		'linkedin.com'       => 'fa-linkedin',
		'pinterest.com'      => 'fa-pinterest',
		'qzone.qq.com'       => 'fa-qq',
		'reddit.com'         => 'fa-reddit',
		'renren.com'         => 'fa-renren',
		'soundcloud.com'     => 'fa-soundcloud',
		'spotify.com'        => 'fa-spotify',
		'stackexchange.com'  => 'fa-stack-exchange',
		'stackoverflow.com'  => 'fa-stack-overflow',
		'steamcommunity.com' => 'fa-steam',
		'stumbleupon.com'    => 'fa-stumbleupon',
		't.qq.com'           => 'fa-tencent-weibo',
		'trello.com'         => 'fa-trello',
		'tumblr.com'         => 'fa-tumblr',
		'twitter.com'        => 'fa-twitter',
		'vimeo.com'          => 'fa-vimeo-square',
		'vine.co'            => 'fa-vine',
		'vk.com'             => 'fa-vk',
		'weibo.com'          => 'fa-weibo',
		'weixin.qq.com'      => 'fa-weixin',
		'wordpress.com'      => 'fa-wordpress',
		'xing.com'           => 'fa-xing',
		'yahoo.com'          => 'fa-yahoo',
		'youtube.com'        => 'fa-youtube',
		'feed'               => 'fa-rss',
		'odnoklassniki.ru'   => 'icon-odnoklassniki'
	));

	// Process each menu item
	foreach ( $sorted_menu_items as $item ) {
		$item_output = '';

		// Look for matching icons
		foreach ( $supported_icons as $pattern => $class ) {
			if ( false !== strpos( $item->url, $pattern ) ) {
				$item_output .= '<li class="' . esc_attr( str_replace( array('fa-', 'icon-'), '', $class ) ) . '">';
				$item_output .= '<a href="' . esc_url( $item->url ) . '">';				
				$item_output .= '<i class="fa fa-fw ' .esc_attr($class). '">';
				$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
				$item_output .= '</i></a></li>';
				break;
			}
		}

		// No matching icons
		if ( '' === $item_output ) {
			$item_output .= '<li class="external-link-square">';
			$item_output .= '<a href="' . esc_url( $item->url ) . '">';
			$item_output .= '<i class="fa fa-fw fa-external-link-square">';
			$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
			$item_output .= '</i></a></li>';
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
