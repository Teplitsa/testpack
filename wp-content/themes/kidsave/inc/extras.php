<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bb
 */



/** Default filters **/
add_filter( 'kds_the_content', 'wptexturize'        );
add_filter( 'kds_the_content', 'convert_smilies'    );
add_filter( 'kds_the_content', 'convert_chars'      );
add_filter( 'kds_the_content', 'wpautop'            );
add_filter( 'kds_the_content', 'shortcode_unautop'  );
add_filter( 'kds_the_content', 'do_shortcode' );

add_filter( 'kds_the_title', 'wptexturize'   );
add_filter( 'kds_the_title', 'convert_chars' );
add_filter( 'kds_the_title', 'trim'          );

/* jpeg compression */
add_filter( 'jpeg_quality', create_function('', 'return 95;' ));

 
/** Custom excerpts  **/

/** more link */
function kds_continue_reading_link() {
	$more = kds_get_more_text();
	return '&nbsp;<a href="'. esc_url( get_permalink() ) . '"><span class="meta-nav">'.$more.'</span></a>';
}

function kds_get_more_text(){
	
	return __('More', 'kds')."&nbsp;&raquo;";
}

/** excerpt filters  */
add_filter( 'excerpt_more', 'kds_auto_excerpt_more' );
function kds_auto_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_length', 'kds_custom_excerpt_length' );
function kds_custom_excerpt_length( $l ) {
	return 30;
}

/** inject */
add_filter( 'get_the_excerpt', 'kds_custom_excerpt_more' );
function kds_custom_excerpt_more( $output ) {
	global $post;
	
	if(is_singular() || is_search())
		return $output;
	
	$output .= kds_continue_reading_link();
	return $output;
}


/** Current URL  **/
if(!function_exists('kds_current_url')){
function kds_current_url() {
   
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
function kds_get_posts_ids_from_query($query){
	
	$ids = array();
	if(!$query->have_posts())
		return $ids;
	
	foreach($query->posts as $qp){
		$ids[] = $qp->ID;
	}
	
	return $ids;
}

function kds_get_post_id_from_posts($posts){
		
	$ids = array();
	if(!empty($posts)){ foreach($posts as $p) {
		$ids[] = $p->ID;
	}}
	
	return $ids;
}


/** Favicon **/
function kds_favicon(){
	
	$favicon_test = WP_CONTENT_DIR. '/favicon.ico'; //in the root not working don't know why
    if(!file_exists($favicon_test))
        return;
        
    $favicon = content_url('favicon.ico');
	echo "<link href='{$favicon}' rel='shortcut icon' type='image/x-icon' >";
}
add_action('wp_head', 'kds_favicon', 1);
add_action('admin_head', 'kds_favicon', 1);
add_action('login_head', 'kds_favicon', 1);

/** Add feed link **/
add_action('wp_head', 'kds_feed_link');
function kds_feed_link(){
	
	$name = get_bloginfo('name');
	echo '<link rel="alternate" type="'.feed_content_type().'" title="'.esc_attr($name).'" href="'.esc_url( get_feed_link() )."\" />\n";
}


/** Adds custom classes to the array of body classes **/
//add_filter( 'body_class', 'kds_body_classes' );
function kds_body_classes( $classes ) {
	

	return $classes;
}



/** Mixed content fixes */
//add_filter('the_content', 'kds_http_the_content', 100);
function kds_http_the_content($html){
	
	if(false !== strpos($html, 'http:')){
		$html = str_replace('http://', '//', $html);
	}
	
	return $html;
}




/** Options in customizer **/
add_action('customize_register', 'kds_customize_register');
function kds_customize_register(WP_Customize_Manager $wp_customize) {
      
    $wp_customize->add_setting('footer_text', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('footer_text', array(
        'type'     => 'textarea',		
        'label'    => 'Текст в футере',
        'section'  => 'title_tagline',
        'settings' => 'footer_text',
        'priority' => 30,
    ));
	
	$wp_customize->add_setting('newsletter_form_id', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('newsletter_form_id', array(
        'type'     => 'text',		
        'label'    => 'ID формы подписки',
        'section'  => 'title_tagline',
        'settings' => 'newsletter_form_id',
        'priority' => 40,
    ));
	
	$wp_customize->add_setting('help_campaign_id', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('help_campaign_id', array(
        'type'     => 'text',		
        'label'    => 'ID основной кампании помощи',
        'section'  => 'title_tagline',
        'settings' => 'help_campaign_id',
        'priority' => 45,
    ));
	
	//Images
	$wp_customize->add_setting('default_thumbnail', array(
        'default'   => false,
        'transport' => 'refresh', // postMessage
    ));
	
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_thumbnail', array(
        'label'    => 'Миниатюра по умолчанию',
        'section'  => 'title_tagline',
        'settings' => 'default_thumbnail',
        'priority' => 60,
    )));
	
	$wp_customize->remove_setting('site_icon'); //remove favicon
}



/** Admin bar **/
//add_action('wp_head', 'kds_adminbar_corrections');
//add_action('admin_head', 'kds_adminbar_corrections');
function kds_adminbar_corrections(){
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	add_action( 'admin_bar_menu', 'kds_adminbar_logo', 10 );
}


function kds_adminbar_logo($wp_admin_bar){	
	
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => '',
	) );
}
