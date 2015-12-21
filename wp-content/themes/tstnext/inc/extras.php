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
		'ok.ru'              => 'icon-ok',
		'livejournal.com'    => 'icon-lj'
	));

	// Process each menu item
	foreach ( $sorted_menu_items as $item ) {
		$item_output = '';

		// Look for matching icons
		foreach ( $supported_icons as $pattern => $class ) { 
			if ( false !== strpos( $item->url, $pattern ) ) {
				
				$icon = '<svg class="sh-icon"><use xlink:href="#'.$class.'" /></svg>';
				$title = (!empty($item->attr_title)) ? " title='".esc_attr($item->attr_title)."'" : '';
				
				$item_output .= '<li class="' . esc_attr( str_replace( array('fa-', 'icon-'), '', $class ) ) . '">';
				$item_output .= '<a href="' . esc_url( $item->url ) . '" target="_blank"'.$title.'>';				
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
add_action('customize_register', 'tst_customize_register', 20);
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
	
	$wp_customize->add_setting('newsletter_form_id', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('newsletter_form_id', array(
        'type'     => 'text',		
        'label'    => __('Newsletter form ID', 'tst'),
        'section'  => 'title_tagline',
        'settings' => 'newsletter_form_id',
        'priority' => 40,
    ));
	
	$wp_customize->remove_setting('site_icon'); //remove favicon
}


/** Facebook author tag **/
//add_action('wp_head', 'tst_facebook_author_tag');
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

/** == Social buttons == **/
function tst_social_share_no_js() {
	
	$title = (class_exists('WPSEO_Frontend')) ? WPSEO_Frontend::get_instance()->title( '' ) : '';
	$link = tst_current_url();
	$text = $title.' '.$link;

	$data = array(
		'vkontakte' => array(
			'label' => 'Поделиться во Вконтакте',
			'url' => 'https://vk.com/share.php?url='.$link.'&title='.$title,
			'txt' => 'Вконтакте',
			'icon' => 'icon-vk',
			'show_mobile' => false
		),
		'facebook' => array(
			'label' => 'Поделиться на Фейсбуке',
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$link,
			'txt' => 'Facebook',
			'icon' => 'icon-facebook',
			'show_mobile' => false
		),		
		'twitter' => array(
			'label' => 'Поделиться ссылкой в Твиттере',
			'url' => 'https://twitter.com/intent/tweet?url='.$link.'&text='.$title,
			'txt' => 'Twitter',
			'icon' => 'icon-twitter',
			'show_mobile' => false		
		),
		'odnoklassniki' => array(
			'label' => 'Поделиться ссылкой в Одноклассниках',
			'url' => 'http://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
			'txt' => 'Одноклассники',
			'icon' => 'icon-ok',
			'show_mobile' => false
			
		),
	);
	
?>
<div class="social-likes-wrapper">
<div class="social-likes social-likes_visible social-likes_ready">

<?php
foreach($data as $key => $obj){		
	if((tst_is_mobile_user_agent() && $obj['show_mobile']) || !tst_is_mobile_user_agent()){
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
		<a href="<?php echo $obj['url'];?>" class="social-likes__button social-likes__button_<?php echo $key;?>" target="_blank" onClick="window.open('<?php echo $obj['url'];?>','<?php echo $obj['label'];?>','top=320,left=325,width=650,height=430,status=no,scrollbars=no,menubar=no,tollbars=no');return false;">
			<svg class="sh-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span>
		</a>
	</div>
<?php 
	}
	
} //foreach

	$text = $title.' '.$link;
	
	$mobile = array(
		'twitter' => array(
			'label' => 'Поделиться ссылкой в Твиттере',
			'url' => 'twitter://post?message='.$text,
			'txt' => 'Twitter',
			'icon' => 'icon-twitter',
			'show_desktop' => false		
		),
		'whatsapp' => array(
			'label' => 'Поделиться ссылкой в WhatsApp',
			'url' => 'whatsapp://send?text='.$text,
			'txt' => 'WhatsApp',
			'icon' => 'icon-whatsup',
			'show_desktop' => false
		),
		'telegram' => array(
			'label' => 'Поделиться ссылкой в Telegram',
			'url' => 'tg://msg?text='.$text,
			'txt' => 'Telegram',
			'icon' => 'icon-telegram',
			'show_desktop' => false
		),
		'viber' => array(
			'label' => 'Поделиться ссылкой в Viber',
			'url' => 'viber://forward?text='.$text,
			'txt' => 'Viber',
			'icon' => 'icon-viber',
			'show_desktop' => false
		),
	);
		
	foreach($mobile as $key => $obj) {
		
		if((!tst_is_mobile_user_agent() && $obj['show_desktop']) || tst_is_mobile_user_agent()) {
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
	<a href="<?php echo $obj['url'];?>" target="_blank" class="social-likes__button social-likes__button_<?php echo $key;?>"><svg class="sh-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span></a>
	</div>	
<?php } } //endforeach ?>

</div>
</div>
<?php
}

function tst_is_mobile_user_agent(){
	//may be need some more sophisticated testing
	$test = false;
	
	if(!isset($_SERVER['HTTP_USER_AGENT']))
		return $test;
	
	if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
		$test = true;
	}
	
	return $test;
}


/** Admin bar **/
add_action('wp_head', 'tst_adminbar_corrections');
add_action('admin_head', 'tst_adminbar_corrections');
function tst_adminbar_corrections(){
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	add_action( 'admin_bar_menu', 'tst_adminbar_logo', 10 );
}


function tst_adminbar_logo($wp_admin_bar){
	
	
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => '',
	) );
}


add_action('wp_footer', 'tst_adminbar_voices');
add_action('admin_footer', 'tst_adminbar_voices');
function tst_adminbar_voices() {
	
?>
<script>	
	jQuery(document).ready(function($){		
		if ('speechSynthesis' in window) {
			var speech_voices = window.speechSynthesis.getVoices(),
				utterance  = new SpeechSynthesisUtterance();
				
				function set_speach_options() {
					speech_voices = window.speechSynthesis.getVoices();
					utterance.text = "I can't lie to you about your chances, but... you have my sympathies.";
					utterance.lang = 'en-GB'; 
					utterance.volume = 0.9;
					utterance.rate = 0.9;
					utterance.pitch = 0.8;
					utterance.voice = speech_voices.filter(function(voice) { return voice.name == 'Google UK English Male'; })[0];
				}
								
				window.speechSynthesis.onvoiceschanged = function() {				
					set_speach_options();
				};
								
				$('#wp-admin-bar-wp-logo').on('click', function(e){
					
					if (!utterance.voice || utterance.voice.name != 'Google UK English Male') {
						set_speach_options();
					}
					speechSynthesis.speak(utterance);
				});
		}			
	});
</script>
<?php
}


/** Reditects for children and news **/
add_action('template_redirect', 'tst_old_redirect');
function tst_old_redirect(){
	global $wp;	
	
	if(!is_404())
		return;	
	
	$redirect = '';
	
	$ids = array(
		'344' => 'children/smetanin-roman/',
		'319' => 'children/bukreev-danil-1-god/',
		'395' => 'children/shott-artyom/',
		'277' => 'children/oganesyan-narek-12-let/',	
		'382' => 'children/nugumanova-diana-16-let/',
		'387' => 'children/astafurova-arina-16-let/	',
		'389' => 'children/sharnina-kristina-3-goda/',	
		'391' => 'children/polyakov-ivan-8-let/	',
		'396' => 'children/tatarnikova-polina-5-let/',
		'363' => 'children/zemskova-anastasiya-11-let/',	
		'364' => 'children/sorokin-aleksandr-10-let/',	
		'365' => 'children/raspaeva-zarina-2-goda/',
		'366' => 'children/ahmedova-darya-16-let/',
		'367' => 'children/kokareva-darya-8-let/',
		'369' => 'children/maslennikov-eduard/',
		'370' => 'children/ziatdinova-veronika-4-goda/',
		'371' => 'children/abushev-samir-2-goda/',
		'373' => 'children/sozykin-leonid-10-let/',	
		'374' => 'children/miller-alyona-3-goda/',
		'375' => 'children/verzakova-polina-8-let/',	
		'376' => 'children/davydov-grigorij-3-goda/',
		'377' => 'children/yakovlev-matvej-3-goda/',
		'378' => 'children/kleshhyov-ivan-14-let/',
		'379' => 'children/posohina-veronika-4-goda/',	
		'380' => 'children/bobrov-dmitrij-16-let/',	
		'333' => 'children/smirnov-grigorij-5-let/',	
		'334' => 'children/bergman-nikita-3-goda/',	
		'335' => 'children/haritonova-margarita-5-let/',	
		'339' => 'children/grinenkov-viktor-3-goda/',	
		'340' => 'children/dzyuba-vyacheslav-17-let/',	
		'341' => 'children/sajgafarov-evgenij-7-let/',	
		'342' => 'children/badamshin-ilyas-7-let/',	
		'345' => 'children/mironov-viktor-11-let/',	
		'346' => 'children/hazheev-ilya-11-let/',
		'347' => 'children/hudyakova-yuliya-5-mesyatsev/',	
		'348' => 'children/sorokin-semyon-4-goda/',	
		'349' => 'children/vittenberg-andrej-4-goda/',	
		'351' => 'children/trubilina-ekaterina-4-goda/',	
		'355' => 'children/erohina-yana-9-let/',	
		'356' => 'children/zolotuhina-elizaveta-11-let/	',
		'357' => 'children/razin-artemij-3-goda/',	
		'360' => 'children/cherepanova-natalya-2-goda/',
		'361' => 'children/levitskij-nikita-7-let/',	
		'362' => 'children/shpilman-kristina-12-let/',	
		'302' => 'children/kartseva-alyona-13-let/',	
		'304' => 'children/fedoseeva-anastasiya-12-let/',
		'306' => 'children/yagafarov-vadim-8-let/',
		'307' => 'children/muraru-valerij-2-goda/',
		'310' => 'children/skarednova-aleksandra-13-let/',	
		'311' => 'children/chistyakov-daniil-4-goda/',
		'314' => 'children/karpuhina-dasha-2-goda/',
		'316' => 'children/kolyshnitsyna-alyona-4-goda/',
		'317' => 'children/gogonina-kseniya-3-goda/',
		'318' => 'children/gejdenrajh-anastasiya-4-goda/',
		'321' => 'children/gilfanov-maksim-4-goda/',
		'323' => 'children/artyomova-anastasiya-1-god/',
		'324' => 'children/kniss-artyom-6-let/',
		'325' => 'children/arzhevitin-andrej-16-let/',
		'326' => 'children/sharov-stanislav-3-goda/',
		'327' => 'children/titova-kseniya-18-let/',
		'328' => 'children/komlev-denis-6-let/',
		'330' => 'children/borisenko-veronika-4-goda/',
		'331' => 'children/merzlikin-arsenij-4-goda/',
		'332' => 'children/chernova-anna-15-let/',
		'264' => 'children/shhyotkin-sergej-9-let/',	
		'265' => 'children/akinshin-aleksandr-21-god/',	
		'266' => 'children/malkov-gleb-22-goda/',
		'268' => 'children/gindullina-karina-9-let/',
		'270' => 'children/karakozov-aleksandr-6-let/',	
		'274' => 'children/naumov-denis-15-let/',
		'275' => 'children/chernyshenko-bogdan-1-god-3-mesyatsa/',	
		'276' => 'children/sundikov-eduard-11-let/',
		'278' => 'children/polushkina-sofiya-4-goda/',	
		'280' => 'children/bykova-anastasiya-8-let/',
		'284' => 'children/ryazanov-stepan-3-goda/',
		'285' => 'children/hudyakov-arsenij-2-goda/',
		'291' => 'children/lebedeva-ekaterina-14-let/',	
		'293' => 'children/voropaev-dmitrij-4-goda/',
		'294' => 'children/karpenko-semyon-4-goda/',
		'297' => 'children/vasileva-violetta-5-let/',
		'299' => 'children/tupaeva-aleksandra-11-let/',	
		'300' => 'children/neznamov-nikita-4-goda/',
		'195' => 'children/mylnikov-styopa-3-goda/',
		'197' => 'children/ledovskij-andrej-18-let/',
		'210' => 'children/losev-mihail-18-let/',
		'225' => 'children/rybkina-valeriya-8-let/',	
		'229' => 'children/mustakimova-alina-3-goda/',	
		'236' => 'children/rakova-lena-11-let/',
		'242' => 'children/islamov-misha-1-god/',
		'245' => 'children/georgiev-ilya-6-let/',
		'250' => 'children/novikova-taisiya-5-let/',	
		'253' => 'children/nevzorov-zhenya-3-goda/',
		'258' => 'children/evdokimova-masha-6-let/',
		'260' => 'children/nekrasova-elena-13-let/',
		'262' => 'children/gerasimov-aleksandr-14-let/',	
		'263' => 'children/hazheev-artur-3-goda/',
		'191' => 'children/bochkaryov-ivan-14-let/',
'1072' => 'blagotvoritelnaya-fotosessiya-chyornoe-i-beloe-v-pomoshh-zhene',	
'1069' => 'priglashaem-na-den-volontyora',	
'1070' => 'proekt-prazdnik-mesyatsa-shhelkunchik-v-gostyah-u-rebyat',	
'1071' => 'tantsevalnyj-triumf-v-pomoshh-onkobolnym-detyam',	
'1067' => 'vperedi-prodolzhenie-yarmarki-dobryh-veshhej-zhdyom-vas',	
'1068' => 'reabilitatsiya-idyom-v-tsirk',	
'1053' => 'pomogite-katyushe-pobedit-bolezn',	
'1052' => 'premera-spektaklya-vsego-odin-den-uspejte-priobresti-bilety',	
'1050' => 'reabilitatsiya-edem-v-limpopo',	
'1051' => 'spasibo-kopejsk-deti-pomogayut-detyam',	
'1047' => 'deti-detyam-vy-tolko-vyzdoravlivajte-v-yuzhnouralske-proshla-aktsiya-dobrye-serdtsa',	
'1048' => '24-oktyabrya-priedet-pesochnaya-animatsiya',	
'1049' => 'proekt-ritmy-tvorchestva-podarki-ko-dnyu-materi',	
'1045' => 'proekt-moya-mama-samaya-krasivaya-ko-dnyu-materi',	
'1046' => 'ochen-nuzhny-detskie-maski',	
'1044' => 'yarmarka-dobryh-veshhej-zhdyot-dobryh-lyudej',	
'1043' => 'proekt-reabilitatsiya-zarabotala-multstudiya-v-tsentre-iskorka',	
'1042' => 'nachalis-zanyatiya-po-multerapii',	
'1041' => 'priglashaem-na-vesyoluyu-vstrechu-s-artistami-teatra-kuklachyova',	
'1036' => '30-oktyabrya-v-18-00-chasov-blagotvoritelnyj-kontsert',	
'1031' => 'nuzhny-sredstva-gigieny-i-uhoda',	
'1032' => 'reabilitatsiya-znakomimsya-s-obezyankami',	
'1033' => 'sozdaj-multfilm',	
'1029' => 'priglashaem-vseh-na-yarmarku-dobryh-veshhej',	
'1030' => '21-oktyabrya-pervaya-vstrecha-aktyorov-blagotvoritelnogo-spektaklya-prazdnik-neposlushaniya',	
'1028' => 'proekt-prazdnik-mesyatsa-osennyaya-poeziya',	
'1027' => 'pomogi-raskrasit-budni',	
'1026' => 'srochno-nuzhno-106-tysyach-na-lekarstva-tyome-vasilevu',	
'1024' => '7-8-oktyabrya-v-chelyabinske-proshyol-obuchayushhij-seminar-osnovy-palliativnoj-pomoshhi-detyam',	
'1025' => 'proekt-podarok-pozdravlyaem-nashih-malchishek-i-devchonok',	
'1022' => 'yarmarka-dobryh-veshhej-dlya-pomoshhi-detyam',	
'1023' => 'priglashaem-na-novosele-iskorki-14-oktyabrya',	
'1021' => 'proekt-prazdnik-mesyatsa-muzykalno-poeticheskij-oktyabr',	
'1020' => 'priglashaem-na-otkrytie-tsentra-iskorka',	
'1019' => 'hotite-izmenenij-k-luchshemu-priglashaem-na-pervyj-chelyabinskij-forum-po-izmeneniyu-zhizni',	
'1018' => 'kak-lechitsya-ot-raka-bez-mamy-dvoe-detej-v-bede',	
'1017' => 'pismo-ot-ani-ili-kto-pomozhet-tem-kto-stal-vzroslym',	
'1016' => 'mechtaesh-o-stsene-hochesh-stat-aktyorom-pomogat-detyam-tvoya-tsel-davaj-poprobuem-vmeste',	
'1015' => 'u-nas-vse-deti-byli-obshhie-reabilitatsionnyj-lager-iskorka-2015-2',	
'1013' => 'proshyol-vtoroj-blagotvoritelnyj-karaoke-turnir',	
'1014' => 'druzya-priglashaem-vas-26-sentyabrya-na-vysadku-pionov-na-territorii-chodkb',	
'1012' => 'supergeroem-mozhet-stat-kazhdyj',	
'1011' => 'blagotvoritelnyj-karaoke-turnir-finishnaya-pryamaya',	
'1009' => 'v-onkootdelenie-vsegda-nuzhny-pampersy-odnorazovye-pelyonki-i-vlazhnye-salfetki',	
'1010' => '2-dnya-do-blagotvoritelnogo-karaoke-turnira-ostalos-poslednee-mesto',	
'1007' => 'pomogite-malyshke-lizochke-zhukovoj',	
'1008' => '23-sentyabrya-na-blagotvoritelnom-karaoke-turnire-v-zhyuri-kirill-rodin-i-darya-ahmedova',	
'1005' => 'roditeli-roditelyam-prosim-podderzhat-drug-druga',	
'1006' => 'pomogite-malenkomu-glebu',	
'1004' => 'a-ty-prinyos-igrushki-v-korobku-hrabrosti',	
'1001' => 'rustam-zajchenko-v-zhyuri-blagotvoritelnogo-karaoke-turnira-horoshie-pesni',	
'1002' => 'v-otdelenii-onkogematologii-nuzhny-moyushhie-sredstva',	
'1003' => 'v-zhyuri-nashego-blagotvoritelnogo-karaoke-izvestnaya-pevitsa-i-vedushhaya-anna-leo',	
'1000' => 'metran-napolnil-korobku-hrabrosti',	
'999' => 'proshyol-dolgozhdannyj-prazdnik-dvora',	
'998' => 'proekt-korobka-hrabrosti-podderzhivayut-vse',	
'995' => 'komanda-lyudej-s-horoshim-nastroeniem-gotova-pomogat',	
'996' => '23-sentyabrya-blagotvoritelnyj-karaoke-kurnir-horoshie-pesni',	
'997' => 'proekt-ritmy-tvorchestva-pora-pristupat',	
'994' => 'proekt-prazdnik-mesyatsa-s-dnyom-znanij',	
'993' => 'novyj-format-proetka-podarok',	
'992' => 'iskorka-priglashaet-na-master-klass-po-gline-na-goncharnom-kruge-speshite',	
'990' => 'predlagaem-poezdku-v-sochi-park-na-oktyabr',	
'991' => 'reabilitatsiya-priglashaem-nashih-tvorcheskih-detej-i-ih-zamechatelnyh-mam-v-teatr-mody',	
'989' => 'proekt-prazdnik-mesyatsa-podarok',	
'987' => 'ya-kak-rebyonok-radovalas-kazhdomu-dnyu-reabilitatsionnyj-lager-iskorka',	
'988' => 'timofej-byl-schastliv',	
'985' => 'prosim-pomoshhi-dlya-denisa-krechetova',	
'986' => 'nam-pesnya-zhit-pomogaet',	
'983' => 'proekt-podarok-imeninniki-leta-prodolzhayut-poluchat-podarki',	
'984' => 'iskorka-primet-uchastie-v-grandioznom-prazdnike-dvora-priglashaem',	
'982' => 'proekt-prazdnik-mesyatsa-skoro-v-shkolu',	
'980' => 'mama-alyony-obrashhaetsya-za-pomoshhyu',	
'981' => 'shedevry-nashih-detej-multstudiya-v-dejstvii',	
'977' => 'srochno-nuzhna-pomoshh-4-h-letnemu-yaroslavu-dektyaryovu-iz-chelyabinska',	
'978' => 'proekt-busy-hrabrosti',	
'979' => 'pro-pro-bono-natashu-i-lekarstva',	
'975' => 'oblastnaya-vyezdnaya-palliativnvaya-sluzhba-prosit-pomoshhi',	
'976' => '23-centyabrya-s-16-do-19-chasov-blagotvoritelnyj-karaoke-turnir-perepoj',	
'974' => 'proekt-podarok-imeninniki-leta',	
'973' => 'leto-ah-leto-vspominaya-lager-iskorka',	
'972' => 'srochno-nuzhny-igrushki-v-korobku-hrabrosti',	
'970' => 'deti-pomogayut-detyam-spasibo-klubu-razvitiya-klyuchi',	
'971' => 'nuzhna-pomoshh-seme-posohinoj-veroniki',	
'969' => 'kogda-vsyo-pozadi-psihologicheskaya-pomoshh-semyam',	
'968' => 'leto-ah-leto-smena-lagerya-iskorka-2015',	
'967' => 'proekt-ritmy-tvorchestva-ishhem-mastera-po-vojlokovalyaniyu',	
'966' => 'trebuetsya-pomoshh-mamam-otdeleniya',	
'965' => 'splav-splav-splav-srochno-zapisyvaemsya',	
'959' => 'pomogite-ulyanke',	
'960' => 'iskorka-uchastvuet-v-blagotvoritelnom-festivale-10-dobryh-del',	
'961' => 'v-onkootdelenie-srochno-nuzhny-bytovye-holodilniki',	
'962' => 'proekt-prazdnik-mesyatsa-leto-v-razgare',	
'963' => 'blagodarim-za-prazdnik-bez-granits-kotoryj-my-sdelali-vmeste',	
'964' => 'pomogite-pojmat-kusochek-leta-irochke-korotovskoj-nuzhna-pomoshh',	
'957' => 'zakonchilas-ocherednya-reabilitatsionnaya-smena-iskorki',	
'958' => 'pro-obezbolivanie-patsientov',	
'955' => 'prosim-pomoshhi-dlya-vyezdnoj-palliativnoj-sluzhby',	
'956' => 'vozvrashhaemsya-iz-lagerya-20-iyulya',	
'953' => '30-iyulya-edem-v-lager',	
'954' => 'pozdravlyaem-nashih-pobeditelej',	
'952' => 'proekt-podarok-mezhdu-vesnoj-i-letom',	
'951' => 'srochno-nuzhny-nagrevateli-dlya-vody-v-otdelenie-onkogematologii',	
'950' => 'pomogite-zhene-dyachenko',	
'946' => 'mify-i-zabluzhdeniya-pro-detej-perenyosshih-rak',	
'947' => 'gotovimsya-k-vsemirnym-igram-pobeditelej-strelba',	
'948' => 'informatsiya-po-sboram-na-vsemirnye-igry-pobeditelej',	
'949' => 'gotovimsya-k-vsemirnym-igram-pobeditelej-shahmaty',	
'945' => 'gotovimsya-k-vsemirnym-igram-pobeditelej-plavanie',	
'942' => 'informatsiya-po-otezdu-v-lager-iskorka',	
'943' => 'programma-vsemirnyh-igr-pobeditelej-v-moskve-26-iyunya-28-iyunya-2015-goda',	
'944' => 'korobka-hrabrosti-rabotniki-yuaiz-prodolzhayut-pomogat-bolnym-detyam',	
'940' => 'gotovimsya-k-vsemirnym-igram-pobeditelej-gde-zhivyom-i-chto-s-soboj-beryom',	
'941' => 'pozdravlyaem-s-dnyom-meditsinskogo-rabotnika',	
'938' => 'informatsiya-o-vylete-na-vsemirnye-igry-pobeditelej',	
'939' => 'lager-iskorka-edinstvennyj-na-urale-provodit-reabilitatsiyu-detej-perenyosshih-onkologicheskie-zabolevaniya',	
'935' => 'pomoshh-vsyakaya-nuzhna-deti-pomogayut-detyam',	
'936' => 'srochno-nuzhna-okaziya-v-bonn',	
'937' => 'sberbank-pomogaet-detyam-onkotsentra',	
'934' => 'gotovimsya-k-ocherednoj-lagernoj-smene',	
'933' => 'gotovimsya-k-vsemirnym-igram-pobeditelej-2',	
'932' => 'gotovimsya-k-vsemirnym-igram-pobeditelej',	
'930' => 'etnodzhem-dlya-nashih-detej',	
'931' => 'spasibo-za-knigu-pro-hrabrogo-malchika-petyu-doblestnyh-vrachej-i-zlobnogo-kolduna-lejkoza',	
'929' => 'proekt-prazdnik-mesyatsa-razvlekatelno-poznavatelnaya-vstrecha',	
'928' => 'komanda-iskorki-uchastvuet-v-mezhunarodnom-forume-pobeditelej',	
'927' => 'iskorka-srochno-ishhet-zabotlivye-ruki-i-dobrye-serdtsa',	
'925' => 'zametki-volontyora-puteshestvennika-v-reabilitatsionnyj-lager-sheredar',	
'926' => 'darite-radost-detyam',	
'924' => 'proekt-ritmy-tvorchestva-risuem',	
'921' => 'iskorka-uchastvuet-v-forume-sotsialnyh-innovatsij-regionov',	
'922' => 'ishhem-igry-dlya-reabilitatsionnogo-lagerya-iskorka',	
'923' => '11-iyunya-snova-prazdnik',	
'919' => 'idyom-v-park-chudes-galileo',	
'920' => 'proekt-prazdnik-mesyatsa-mini-maus-i-mylnye-puzyri',	
'918' => '1-iyunya-proshyol-konkurs-malenkaya-miss-chelyabinsk-2015',	
'915' => 'pozdravlyaem-basharovu-elenu-valerevnu-s-yubileem',	
'916' => 'druzya-zhdut-novostej-ot-mashi',	
'917' => 'blagodarim-vseh-kto-prinyal-uchastie-v-subbotnike',	
'914' => '2-iyunya-prazdnik-mesyatsa-den-zashhity-detej',	
'913' => 'prosim-pomoshhi-v-pokupke-3-komplektov-rezervuarov-ommajya-stoimoctyu-79800-rublej',	
'912' => 'zhdyom-vseh-na-subbotnik-30-maya',	
'911' => 'prosim-pomoch-otdeleniyu-2',	
'910' => 'proekt-podarok-imeninniki-aprelya',	
'909' => 'proekt-prazdnik-mesyatsa-mezhdunarodnyj-den-semi-2',	
'906' => 'prodolzhaetsya-nabor-v-reabilitatsionnyj-lager-iskorka',	
'907' => 'priglashaem-na-konkurs-malenkaya-miss-chelyabinsk-2015',	
'908' => 'zhdyom-na-zelyonom-marafone',	
'905' => 'iskorka-i-renessans-zhizn-predlagayut-nadyozhnuyu-zashhitu',	
'904' => '4-zolota-v-kopilku-chempiona',	
'903' => 'zashhiti-sebya-spasi-rebyonka',	
'899' => 'priglashaem-na-subbotnik-na-territorii-chodkb',	
'900' => '14-maya-otkryvaetsya-reabilitatsionnyj-tsentr-sheredar',	
'901' => 'semiletnemu-dime-uzhe-povezlo-pobedit-rak-davajte-podarim-emu-ispolnenie-mechty',	
'902' => 'idyom-v-kamernyj-teatr-i-na-chempionat-mira-po-thekvondo',	
'898' => 'proekt-prazdnik-mesyatsa-mezhdunarodnyj-den-semi',	
'897' => 'idyom-v-teatr',	
'896' => 'deti-pobedivshie-rak-pobezhdayut-v-sporte',	
'895' => 'obyavlyaem-sbor-sredstv-na-oplatu-konsultatsii-dashi-kokarevoj-s-zarubezhnymi-spetsialistami',	
'894' => 'priglashaem-nashih-detej-v-reabilitatsionnyj-lager-iskorka',	
'893' => 'nashi-pobediteli-edut-v-moskvu-na-vsemirnye-igry-pobeditelej',	
'892' => 'proshli-treti-regionalnye-igry-pobeditelej',	
'890' => 'otezd-avtobusov-na-igry-pobeditelej-25-aprelya-v-8-15',	
'891' => '21-aprelya-komanda-iskorki-prinyala-uchastie-v-sorevnovaniyah-po-strelbe-na-kubok-rektora-yuurgu',	
'889' => 'pashalnye-podarki-ot-ooo-agrofirmy-ariant',	
'887' => '17-aprelya-vsemirnyj-den-gemofilii',	
'888' => 'proekt-moya-mama-samaya-krasivaya-aprel',	
'886' => 'spasibo-11-litseyu',	
'885' => 'gotovimsya-k-igram-pobeditelej-predvaritelnyj-tajming',	
'884' => 'gotovimsya-k-igram-pobeditelej-priglashaem-roditelej-prinyat-uchastie-v-komicheskom-futbole',	
'883' => 'pereneseny-sorevnovaniya-po-strelbe-na-kubok-rektora-yuurgu',	
'882' => 'gotovimsya-k-igram-pobeditelej-ishhem-masterov-parikmaherov-i-hudozhnikom-po-akva-grimu',	
'881' => 'prosim-pomoch-s-moyushhimi-sredstvami',	
'879' => 'proekt-podarok-vesennie-imeninniki',	
'880' => 'spasibo-organizatoram-marketa-5-aprelya-za-pomoshh-nashim-detyam',	
'877' => 'unikalnye-tovary-dlya-vashego-horoshego-nastroeniya',	
'878' => 'reabilitatsiya-6-aprelya-idyom-v-akvarium-2',	
'875' => 'proekt-prazdnik-mesyatsa-my-tantsuem-i-poyom',	
'876' => 'prosim-pomoch-nashim-tvorcheskim-detyam',	
'873' => 'nuzhny-pampersy-7-razmera',	
'874' => 'spasibo-pervoj-gimnazii',	
'872' => 'priglashaem-nashih-detej-prinyat-uchastie-v-festivale-pomnim-i-gordimsya-posvyashhyonnom-70-letiyu-pobedy',	
'871' => 'regionalnye-igry-pobeditelej-2015-zakanchivaetsya-registratsiya',	
'869' => 'blagotvoritelnyj-auktsion-ot-aif-chelyabinsk-31-marta-2',	
'870' => 'nuzhny-detskie-i-vzroslye-maski-prosim-pomoshhi',	
'868' => 'reabilitatsiya-6-aprelya-idyom-v-akvarium',	
'867' => 'obyavlyaem-sbor-sredstv-na-pokupku-metodzhekta-dlya-detej-boryushhihsya-s-lejkozom',	
'865' => 'joga-dlya-sovremennyh-lyudej-priglashaem-vseh',	
'866' => '14-aprelya-ocherednoj-den-krasoty-dlya-mam-v-otdelenii',	
'863' => 'proekt-prazdnik-mesyatsa-chto-nas-zhdyot-v-aprele',	
'864' => 'proekt-podarok-ili-roditeli-poluchayut-syurprizy',	
'861' => 'reabilitatsiya-3-maya-edem-v-limpopo',	
'862' => 'zashhiti-sebya-pomogi-drugomu',	
'858' => 'pozdravlyaem-majorovu-evgeniyu-viktorovnu',	
'859' => '1-gimnaziya-provodit-blagotvoritelnuyu-yarmarku-28-marta',	
'860' => 'priglashaem-nashih-krasavits-prinyat-uchastie-v-konkurse-mini-miss-chelyabinsk-2015',	
'856' => 'blagotvoritelnyj-auktsion-ot-aif-chelyabinsk-31-marta',	
'857' => 'proekt-ritmy-tvorchestva-pashalnaya-melodiya',	
'855' => 'proekt-podarok-ili-neskuchnye-poiski',	
'854' => '24-marta-blagotvoritelnyj-kontsert-v-restorannom-dome-spiridonov',	
'853' => 'argumenty-i-fakty-chelyabinsk-pomogayut-onkobolnym-detyam-yuzhnogo-urala-3',	
'852' => 'proekt-moya-mama-samaya-krasivaya-mart-2',	
'851' => 'proekt-prazdnik-mesyatsa-ili-diskoteka-v-otdelenii',	
'850' => 'argumenty-i-fakty-chelyabinsk-pomogayut-onkobolnym-detyam-yuzhnogo-urala-2',	
'848' => 'proekt-ritmy-tvorchestva-novaya-melodiya',	
'849' => '15-marta-rok-festival-v-podderzhku-detej-onkootdeleniya',	
'846' => 'argumenty-i-fakty-chelyabinsk-pomogayut-onkobolnym-detyam-yuzhnogo-urala',	
'845' => 'komanda-iskorki-uchastvuet-v-kubke-rektora-yuurgu-po-strelbe',	
'844' => 'reabilitatsiya-15-marta-priglashaem-katatsya-na-lyzhah',	
'843' => 'pozdravlyaem-s-8-marta',	
'840' => 'proekt-moya-mama-samaya-krasivaya-mart',	
'841' => 'pomogite-sdelat-prazdnik-detyam',	
'837' => 'reabilitatsiya-edem-na-strausinuyu-fermu',	
'838' => 'reabilitatsiya-7-marta-edem-v-limpopo',	
'839' => 'dan-start-vesennej-blagotvoritelnosti',	
'836' => 'reabilitatsionnye-smeny-lagerya-sheredar',	
'834' => '25-aprelya-regionalnye-igry-pobeditelej',	
'835' => 'idyom-v-kino',	
'832' => '41556-rublej-sobrano-dlya-detej-onkogematologicheskogo-tsentra-na-maslenitse',	
'831' => '95-tysyach-rublej-sobrano-na-vtorom-blagotvoritelnom-turnire-po-boulingu',	
'830' => 'pozdravlyaem-nashu-angelinu-nikolenko',	
'827' => '15-fevralya-v-tsitruse',	
'828' => '22-fevralya-maslenitsa-v-parke-gagarina',	
'829' => 'reabilitatsiya-edem-v-limpopo-2',	
'826' => 'veronike-borisenko-nuzhna-nasha-pomoshh',	
'825' => '14-fevralya-den-priznaniya-v-lyubvi',	
'822' => '19-fevralya-projdyot-ii-blagotvoritelnyj-turnir-po-boulingu',	
'823' => 'ogon-olimpii-i-angelina-15-fevralya',	
'824' => '15-fevralya-mezhdunarodnyj-den-borby-s-detskim-rakom-3',	
'821' => 'palliativnaya-pomoshh-iskorki',	
'820' => 'podderzhi-detej-v-mezhdunarodnyj-den-borby-s-detskim-rakom',	
'817' => 'u-iskorki-poyavilsya-svoj-dom',	
'818' => '19-fevralya-sostoitsya-blagotvoritelnyj-turnir-po-boulingu',	
'819' => 'reabilitatsiya-vsem-pomozhet-glinoterapiya',	
'815' => 'proekt-podarok-vseh-pozdravili',	
'816' => 'reabilitatsiya-kursy-vizazha-dlya-nashih-devochek-i-mam',	
'813' => 'prosim-pomoshhi-v-priobretenii-zimnej-odezhdy',	
'814' => 'proshyol-pervyj-den-krasoty-dlya-mam-v-2015-godu',	
'812' => 'proekt-prazdnik-mesyatsa-vesyolye-svyatki',	
'810' => 'ogon-olimpii-i-angelina',	
'811' => 'proekt-podarok-imeninniki-dekabrya-uspeshno-zakonchili-god',	
'809' => 'kanikuly-v-russkom-pole',	
'808' => 'ishhem-mastera-dlya-raboty-s-polimernoj-glinoj',	
'805' => 'bolejte-tolko-za-sport',	
'806' => '25-yanvarya-priglashaem-nashih-detej-i-roditelej-na-traditsionnoe-katanie-na-lyzhah',	
'807' => 'prazdnichnyj-kalendar-iskorki',	
'803' => 'prazdnichnye-dni-v-otdelenii',	
'804' => 'reabilitatsionnye-programmy-iskorki-na-2015-god',	
'797' => 'priglashaem-masterov-na-den-krasoty-dlya-mam',	
'796' => 'prozrachnaya-blagotvoritelnost-2014-goda',	
'795' => 'stala-dostupna-pomoshh-cherez-sberbank-onlajn',	
'791' => 'zapsibkombank-pomogaet-detyam-pobedit-rak',	
'792' => 'proekt-podarok-imeninniki-noyabrya-ne-ostalis-bez-pozdravleniya',	
'793' => 'proekt-7715-prostoj-nomer-dlya-blagotvoritelnosti',	
'794' => 'proekt-prazdnik-mesyatsa-ili-neobyknovennye-priklyucheniya-deda-moroza-i-snegurochki',	
'788' => 'pomogi-tomu-kto-blizko',	
'789' => '24-dekabrya-proshel-seminar-soveshhanie-s-predstavitelyami-sotsialno-orientirovannyh-organizatsij',	
'790' => 'zakonchilos-puteshestvie-v-stranu-tvorchestva',	
'787' => 'proshyol-blagotvoritelnyj-kontsert-na-chudo-nadejsya-a-sam-ne-ploshaj',	
'786' => 'priglashaem-za-dizajnerskimi-yolkami',	
'784' => 'uroki-muzyki-v-otdelenii',	
'785' => 'priglashaem-na-rozhdestvenskij-festival-sdelaj-krasotu-svoimi-rukami',	
'781' => 'proshyol-novogodnij-parad-chudes-spasibo-vsem-kto-prinyal-uchastie-i-prishyol-poveselitsya',	
'782' => 'puteshestvie-v-stranu-tvorchestva',	
'783' => 'yarmarka-idej-na-parade-chudes-neozhidannye-resheniya',	
'779' => 'proshyol-blagotvoritelnyj-kontsert-marafon-v-kopejske',	
'780' => 'blagotvoritelnyj-kontsert-v-podderzhku-nashih-detej-2',	
'777' => '24-dekabrya-novogodnij-den-krasoty-dlya-mam',	
'778' => 'na-chudo-nadejsya-a-sam-ne-ploshaj',	
'776' => 'predlozhenie-dlya-luchshih-dizajnerov-chelyabinskoj-oblasti',	
'775' => 'spasibo-leninskomu-rajonu',	
'774' => 'pomogite-provesti-vesyoluyu-yarmarku',	
'771' => 'parad-chudes-14-dekabrya-priglashaem-vseh-2',	
'772' => 'studenty-pomogayut-detyam',	
'770' => 'prosim-pomoch-otdeleniyu',	
'768' => 'speshite-delat-dobrye-dela',	
'769' => 'proekt-podarok-podarki-s-dostavkoj',	
'765' => 'parad-chudes-14-dekabrya-priglashaem-vseh',	
'766' => 'blagotvoritelnyj-kontsert-v-podderzhku-nashih-detej',	
'767' => 'proekt-moya-mama-samaya-krasivaya-noyabr',	
'764' => 'vnimanie-servis-7715-s-1-dekabrya-vremenno-nedostupen-dlya-abonentov-mts',	
'760' => 'proekt-prazdnik-mesyatsa-puteshestvie-v-kartonnyj-gorod',	
'761' => 'pozdravlyaem-bf-podari-zhizn-s-dnyom-rozhdeniya',	
'762' => 'prosim-pomoshhi-v-pokupke-asparginazy',	
'763' => 'blagotvoritelnyj-kontsert-marafon-14-dekabrya-v-kopejske',	
'759' => 'iskorka-uchastvuet-v-sotsialnoj-programme-diksi',	
'757' => 'kompaniya-braun-stala-nashim-blagotvoritelem',	
'758' => '22-noyabrya-sostoyalsya-festival-konkurs-tantsevalnyj-triumf',	
'756' => 'sos-prosim-pomoshhi-v-priobretenii-rashodnyh-materialov',	
'755' => 'nash-predstavitel-v-detskom-obshhestvennom-sovete',	
'754' => 'zavershilsya-forum-osobye-semi-zhizn-bez-granits',	
'753' => 'blagotvoritelnost-protiv-raka',	
'752' => 'busy-hrabrosti',	
'751' => 'zaezd-v-russkoe-pole-s-5-yanvarya',	
'749' => 'dobrye-serdtsa-nashih-detej',	
'750' => 'vyberi-radio-rupor-miloserdiya',	
'748' => 'pomogite-poline-burlakovoj',	
'746' => 'proekt-podarok-zakonchili-pozdravlyat-imeninnikov-oktyabrya',	
'747' => 'aktsiya-podari-novyj-god-ot-bf-kogda-ty-nuzhen-2',	
'744' => 'bolshoe-puteshestvie-sila-mechty-i-dushevnyj-bazar',	
'745' => 'forum-osobye-semi-zhizn-bez-granits',	
'743' => 'i-vserossijskij-kongress-sovremennoe-sostoyanie-novye-vozmozhnosti-i-perspektivy-razvitiya-palliativnoj-pomoshhi-detyam-v-regionah-rossijskoj-federatsii',	
'741' => 'proekt-prazdnik-mesyatsa-vstrecha-s-malinkoj',	
'742' => 'prosim-pomoch-nashim-malysham-3',	
'739' => '5-klass-11-litseya-pomogaet-nashim-detyam',	
'740' => 'deti-brosayut-vyzov-professionalam',	
'738' => 'misha-i-ego-tvorchestvo',	
'737' => 'proekt-podarok-pozdravlyaem-osennih-imeninnikov',	
'734' => 'prosim-pomoshhi-v-otdelenie-2',	
'735' => 'proekt-moya-mama-samaya-krasivaya-oktyabr',	
'736' => '2-noyabrya-blagotvoritelnyj-turnir-po-strelbe',	
'730' => 'tantsevalnyj-triumf-v-pomoshh-detyam',	
'731' => '1-noyabrya-dushevnyj-bazar',	
'732' => 'aktsiya-podari-novyj-god-ot-bf-kogda-ty-nuzhen',	
'733' => 'dnevnik-patsienta-v-pomoshh-roditelyam',	
'728' => 'spasibo-za-pomoshh-detskie-maski-pribyli',	
'725' => 'nas-priglashayut-v-kino',	
'726' => 'priglashaem-na-1-blagotvoritelnyj-turnir-po-strelbe',	
'727' => 'novye-melodii-ritmov-tvorchestva',	
'724' => 'spasibo-za-zamechatelnye-knigi',	
'722' => 'vtoroj-blagotvoritelnyj-kontsert-v-polzu-nashih-detej',	
'723' => 'mishe-nuzhna-kolyaska-ishhem-dobryh-lyudej',	
'720' => 'dobrye-serdtsa-v-pomoshh-detyam',	
'721' => 'konferentsiya-v-reabilitatsionnom-tsentre-sheredar',	
'717' => '23-oktyabrya-den-krasoty-dlya-mam',	
'718' => 'blagotvoritelnyj-kontsert-v-polzu-nashih-detej',	
'719' => 'proekt-prazdnik-mesyatsa-3',	
'716' => 'bolshoe-puteshestvie-sila-mechty',	
'715' => 'bank-renessans-prisoedinilsya-k-pomoshhi-detyam',	
'714' => 'srochno-ishhem-okaziyu-v-germaniyu',	
'713' => 'vspominaya-karaoke-turnir',	
'712' => 'ochen-nuzhny-detskie-zashhitnye-maski',	
'710' => 'priglashaem-na-blagotvoritelnyj-vecher-dobrye-serdtsa',	
'711' => 'diagnostika-i-lechenie-rol-mikroelementov-v-zhizni-cheloveka-3',	
'709' => 'proekt-podarok-pozdravlyaem',	
'708' => 'prosim-pomoshhi-3',	
'705' => 'kniga-v-pomoshh-dlya-roditelej-chi-deti-bolny-lejkozom-2',	
'706' => 'krasivye-shapochki-dlya-nashih-devochek',	
'707' => 'reabilitatsiya-priglashaem-v-gruppu-po-glinoterapii',	
'704' => 'proekt-moya-mama-samaya-krasivaya-sentyabr',	
'702' => 'v-chelyabinske-proshyol-pervyj-blagotvoritelnyj-karaoke-turnir',	
'703' => 'nuzhna-pomoshh-2',	
'701' => 'diagnostika-i-lechenie-rol-mikroelementov-v-zhizni-cheloveka-2',	
'699' => 'reabilitatsiya-klub-nashe-mesto-priglashaet-nashih-detej-na-interesnye-meropriyatiya',	
'700' => 'kazhdyj-vybiraet-po-sebe',	
'698' => 'proekt-solntse-dolzhno-svetit-vsem-bez-isklyucheniya',	
'696' => '18-sentyabrya-pervyj-blagotvoritelnyj-karaoke-turnir-speshi-prinyat-uchastie',	
'697' => 'proekt-reabilitatsiya-dobraya-loshadka-zhdyot-v-gosti',	
'695' => '18-sentyabrya-sostoitsya-pervyj-blagotvoritelnyj-karaoke-turnir',	
'694' => 'proekt-podarok-imeninniki-avgusta',	
'691' => 'prodolzhaetsya-proekt-ritmy-tvorchestva',	
'692' => 'itogi-festivalya-10-dobryh-del-2',	
'693' => 'proekt-korobka-hrabrosti-podderzhivaet-otp-bank',	
'690' => 'obshhestvenno-politicheskij-vernisazh-2014',	
'689' => 'diagnostika-i-lechenie-rol-mikroelementov-v-zhizni-cheloveka',	
'688' => 'privetstvuem-pervyh-uchastnikov-pervogo-blagotvoritelnogo-karaoke-turnira',	
'687' => 'den-znanij-v-otdelenii-2',	
'686' => 'vserossijskoe-roditelskoe-sobranie',	
'684' => 'tolko-smelym-pokoryayutsya-morya-i-dazhe-rechki',	
'685' => 'zakonchilas-letnyaya-reabilitatsiya-vseh-s-nachalom-uchebnogo-goda',	
'683' => 'gotovimsya-k-obshhestvenno-politicheskomu-vernisazhu',	
'681' => 'zavtra-vstrechaemsya-v-dobroj-loshadke',	
'682' => 'prosim-vseh-neravnodushnyh-chelyabintsev-i-zhitelej-oblasti-pomoch',	
'680' => '2-sentyabrya-den-znanij-v-otdelenii-pozdravlyaem-nashih-shkolnikov',	
'679' => 'splav-splav-splav-26-27-avgusta-po-reke-aj',	
'676' => 'festival-10-dobryh-del',	
'677' => 'vstrecha-s-ispolnyayushhim-obyazannosti-gubernatora-chelyabinskoj-oblasti-borisom-aleksandrovichem-dubrovskim',	
'678' => 'proekt-moya-mama-samaya-krasivaya-avgust',	
'674' => 'pomogite-nikite-burenkovu',	
'675' => '29-avgusta-den-rozhdeniya-majkla-dzheksona-priglashaem-na-flesh-mob',	
'673' => 'proekt-podarok-prodolzhaem-pozdravlyat',	
'672' => 'proekt-ritmy-tvorchestva-2',	
'671' => 'splav-splav-splav-est-predvaritelnaya-data-sledite-za-novostyami',	
'670' => 'pervyj-blagotvoritelnyj-karaoke-turnir-perepoj',	
'669' => 'prosim-pomoshhi-v-otdelenie',	
'667' => 'prazdnik-v-dobroj-loshadke',	
'668' => 'proekt-podarok',	
'666' => 'srochnyj-sbor-na-lechenie-sashi-tupaevoj',	
'663' => 'sos-prosim-pomoshhi-v-oplate-dorogostoyashhego-lekarstva',	
'664' => 'proekt-prazdnik-mesyatsa-2',	
'665' => 'kniga-v-pomoshh-dlya-roditelej-chi-deti-bolny-lejkozom',	
'659' => 'prishli-mne-chtenya-dobrogo',	
'658' => 'dobrye-serdtsa-vedut-zhorika-v-gosti-k-onkobolnym-detyam',	
'657' => 'blagotvoritelnyj-proekt-dobrye-serdtsa',	
'656' => 'proshyol-ocherednoj-den-krasoty-dlya-mam',	
'655' => 'spasibo-za-pomoshh-vsem-volontyoram-kotorye-pomogli-nam-na-pravoslavnoj-yarmarke',	
'654' => 'prosim-pomoch-otdeleniyu-moyushhimi-sredstvami-i-sredstvami-gigieny',	
'653' => 'prodolzhaetsya-proekt-korobka-hrabrosti',	
'652' => 'proekt-ritmy-tvorchestva',	
'651' => 'proekt-podarok-pozdravlyaem-imeninnikov-iyulya',	
'650' => '30-iyulya-ocherednoj-den-proekta-moya-mama-samaya-krasivaya',	
'647' => 'prosim-pomoshhi-v-izgotovlenii-bannera',	
'648' => 'sos-nuzhna-pomoshh',	
'649' => 'reshim-hozyajstvennye-problemy-vmeste',	
'644' => 'nepogoda-ne-meshaet-darit-radost-detyam',	
'645' => 'vstrechaem-detej-iz-lagerya',	
'646' => 'perenosim-splav-sledite-za-novostyami',	
'640' => 'priglashaem-predstavitelej-smi-v-nash-reabilitatsionnyj-lager',	
'641' => 'obuchenie-dlya-nko',	
'642' => 'spasibo-vsem-kto-podderzhivaet-nashih-detej',	
'643' => 'pogoda-raduet-novosti-po-splavu',	
'638' => 'informatsiya-o-zaezde-v-russkoe-pole-28-iyulya',	
'639' => 'prosim-pomoch-nashim-malysham-2',	
'636' => 'ritmy-tvorchestva-zvuchat-v-otdelenii',	
'637' => 'klinika-veladent-prisoedinilas-k-pomoshhi-nashim-detyam',	
'635' => 'priglashenie-nko-na-diskussionnuyu-ploshhadku',	
'631' => 'proekt-podarok-eshhyo-raz-pozdravlyaem-imeninnikov-iyunya',	
'632' => 'spasibo-za-pomoshh-vsem-nashim-zemlyakam',	
'633' => 'ochen-nuzhny-aktivnye-vesyolye-dobrye-lyudi',	
'634' => 'proekt-prazdnik-mesyatsa',	
'630' => 'splav-splav-splav-sobiraemsya-i-zapolnyaem-dokumenty',	
'628' => 'semejnyj-zaezd-v-reabilitatsionnyj-tsentr-russkoe-pole',	
'629' => 'spasibo-vsem-novym-i-postoyannym-donoram',	
'627' => 'sdelaem-lager-yarche',	
'625' => 'iskorka-nominiruetsya-na-uchastie-v-festivale-10-dobryh-del',	
'626' => 'priglashaem-vseh-na-donorskuyu-aktsiyu-4-iyulya',	
'624' => 'proekt-podarok-pozdravlyaem-imeninnikov-iyunya',	
'623' => 'v-otdelenii-proshyol-ocherednoj-den-krasoty-dlya-mam',	
'621' => 'priglashaem-na-subbotnik-30-iyunya',	
'622' => 'zavershilis-5-e-vsemirnye-igry-pobeditelej',	
'620' => 'informatsiya-po-otezdu-v-lager',	
'619' => 'blagodarnost-ot-semi-kolesnikovyh',	
'618' => 'sudi-oblastnogo-suda-pomogayut-detyam-pobedit-rak',	
'617' => 'srochnye-novosti-po-lageryu',	
'616' => 'zakonchilsya-nabor-v-lager-srochno-oformlyaem-dokumenty',	
'615' => 'obyavlyaem-srochnyj-sbor-sredstv-na-preparat-ne-zaregistrirovannyj-v-rossii',	
'614' => 'priglashaem-na-traditsionnyj-den-krasoty-dlya-mam',	
'612' => 'proekt-podarok-pozdravlyaem-nashih-majskih-imeninnikov',	
'613' => 'klub-nashe-mesto-priglashaet-nashih-detej-s-15-let-na-vstrechu-v-novom-formate',	
'610' => 'den-zashhity-detej-v-otdelenii',	
'611' => 'eho-nashih-igr-pobeditelej',	
'609' => 'pozdravlyaem-s-dnyom-rozhdeniya-elenu-valerevnu-basharovu',	
'607' => '31-maya-my-prinyali-uchastie-v-festivale-nedvizhimostii-i-ipoteki',	
'608' => 'nashi-pobediteli-otpravilis-v-kazan',	
'606' => 'priglashaem-v-fiestu-na-blagotvoritelnuyu-aktsiyu-dlya-nashih-detej',	
'605' => 'nachinaem-zapis-v-letnij-reabilitatsionnyj-lager-iskorka',	
'602' => 'dopolnitelnaya-informatsiya-dlya-pobeditelej-kotorye-edut-v-kazan',	
'603' => 'nas-priglashayut-na-den-zashhity-detej-zapisyvaemsya',	
'604' => 'blagodarnost-ot-semi-stepchenko',	
'601' => '31-maya-iskorka-uchastvuet-v-vystavke-vsyo-o-nedvizhimosti',	
'599' => 'informatsiya-dlya-pobeditelej-po-poezdke-v-kazan',	
'600' => 'zavershilis-vtorye-regionalnye-igry-pobeditelej',	
'597' => 'priglashaem-na-spektakl-belosnezhka-i-sem-gnomov',	
'598' => 'raspisanie-igr-pobeditelej',	
'596' => 'srochno-prosim-pomoshhi-v-otdelenie',	
'595' => 'do-otkrytiya-regionalnyh-igr-pobeditelej-ostalos-neskolko-dnej',	
'594' => 'ura-vot-i-leto-prishlo',	
'593' => 'proekt-podarok-pozdravlyaem-nashih-lyubimyh-detok',	
'591' => 'den-krasoty-den-semi-2',	
'592' => 'vesennij-subbotnik',	
'590' => 'kupi-bilet-na-igry-pobeditelej-pomogi-detyam-vernutsya-v-detstvo',	
'588' => 'priglashaem-vseh-na-subbotnik',	
'589' => 'spasibo-za-pomoshh-vsem-dobrym-lyudyam',	
'587' => 'pozdravlyaem-nashih-doktorov-s-dnyom-rozhdeniya',	
'585' => 'zdorove-naseleniya-promyshlennyh-monogorodov',	
'586' => '24-maya-regionalnye-igry-pobeditelej',	
'584' => 'den-krasoty-den-semi',	
'583' => 'prosto-mama',	
'581' => 'priglashaem-na-subbotnik-17-maya',	
'582' => 'proekt-podarok-pozdravlyaem-denisa-manyaeva',	
'579' => 'prodolzhaetsya-registratsiya-na-regionalnye-igry-pobeditelej',	
'580' => 'proekt-podarok-pozdravlyaem-polinku-burlakovu-anyutku-stepchenko-i-mishu-islamova',	
'575' => 'prosim-pomoch-nashim-malysham',	
'576' => 'znakomimsya-s-predvaritelnym-tajmingom-regionalnyh-igr-pobeditelej',	
'577' => 'argayashskij-rajon-provodit-aktsiyu-otzovites-dobrye-serdtsa',	
'578' => 'proekt-prazdnik-mesyatsa-net-povoda-ne-poveselitsya',	
'574' => 'argumenty-i-fakty-podveli-itogi-aktsii-v-podderzhku-ilnura-karimova',	
'573' => 'puteshestvie-v-novyj-mir',	
'572' => 'vstrechaem-pashu',	
'571' => 'aprelskij-den-krasoty-v-otdelenii',	
'570' => 'pora-snimatsya-v-kino',	
'569' => 'blagodarim-vseh-nashih-pomoshhnikov-volontyorov',	
'568' => 'semya-bekeevyh-i-merzagulovyh-blagodarit-volontyorov',	
'566' => 'izmenenie-informatsii-po-dnyu-krasoty-dlya-mam',	
'567' => 'prosim-pomoshhi-2',	
'562' => 'vnimanie-izmeneniya-v-sostave-uchastnikov-regionalnyh-igr-pobeditelej',	
'563' => 'proekt-podarok-pozdravlyaem-dimu-pospelova',	
'564' => 'gotovimsya-k-novoj-vystavke-risunkov',	
'565' => '16-aprelya-v-otdelenii-sostoitsya-ocherednoj-den-krasoty-dlya-mam',	
'561' => 'nachinaem-zapis-na-regionalnye-igry-pobeditelej',	
'560' => 'spasibo-1-oj-gimnazii',	
'559' => '24-maya-sostoyatsya-regionalnye-igry-pobeditelej',	
'558' => 'priglashaem-na-blagotvoritelnyj-rok-festival-nashe-mesto',	
'557' => 'proekt-podarok-pozdravlyaem-ksyushu-zamyshlyaevu',	
'556' => 'pozhertvovaniya-s-bilajna-mozhno-perevesti-na-nomer-7878',	
'554' => 'u-nas-palochka-ne-volshebnaya-a-estafetnaya',	
'555' => 'proekt-podarok-pozdravlyaem-anzhelu-gavrilovu',	
'553' => 'eto-prosto-bolezn',	
'552' => 'srochno-zapisyvaemsya-na-vsemirnye-igry-pobeditelej',	
'551' => 'prosim-okazat-pomoshh-otdeleniyu',	
'549' => 'proekt-podarok-pozdravlyaem-lenu-rakovu-i-seryozhu-shhyotkina',	
'550' => 'v-pomoshh-roditelyam',	
'548' => '8-marta-v-otdelenii',	
'547' => 'reabilitatsiya-glinoterapiya-v-nastoyashhej-masterskoj',	
'546' => 'priglashaem-na-blagotvoritelnyj-turnir-po-lazertagu-3',	
'545' => 'reabilitatsiya-iskorka-sportivnaya-rastut-chempiony',	
'544' => 'pozdravlyaem-s-prazdnikom-8-marta',	
'542' => 'nachinaem-registratsiyu-na-vsemirnye-igry-pobeditelej',	
'541' => 'reabilitatsiya-iskorka-sportivnaya-priglashaet-na-gornye-lyzhi-2',	
'540' => 'proshla-vesyolaya-maslenitsa',	
'539' => 'do-maslenitsy-ostalos-2-dnya',	
'538' => '25-fevralya-v-otdelenii-proshyol-ocherednoj-den-krasoty-moya-mama-samaya-krasivaya',	
'536' => 'priglashaem-na-blagotvoritelnyj-turnir-po-lazertagu-2',	
'537' => 's-dnyom-rozhdeniya-karine-benyaminovna',	
'534' => 'reabilitatsiya-iskorka-sportivnaya-priglashaet-na-gornye-lyzhi',	
'535' => 'proekt-podarok-pozdravlyaem-alinu-mustakimovu',	
'532' => 'zhdyom-vseh-na-maslenitsu',	
'533' => 'priglashaem-na-blagotvoritelnyj-turnir-po-lazertagu',	
'530' => 's-dnyom-rozhdeniya-german-yurevich',	
'531' => 'prodolzhaem-pozdravlyat-imeninnikov-fevralya',	
'529' => '25-fevralya-ocherednoj-den-krasoty-v-otdelenii',	
'528' => 'blagotvoritelnyj-bouling-spasibo-vsem',	
'526' => 'seminar-trening-strategiya-privlecheniya-resursov',	
'527' => 'proekt-podarok-pozdravlyaem-ilyushu-georgieva',	
'524' => '15-fevralya-mezhdunarodnyj-den-borby-s-detskim-rakom-2',	
'525' => 'reabilitatsiya-vesyolye-sportivnye-uspeshnye',	
'523' => 'zhdyom-na-blagotvoritelnyj-bouling-18-fevralya',	
'521' => 'priglashaem-na-provody-zimy',	
'522' => 'prosim-pomoshhi-v-priobretenii-materialov-dlya-zanyatij-s-detmi-art-terapiej',	
'519' => 'iskorka-uchastvovala-v-pravoslavnoj-vystavke-vo-slavu-bozhiyu',	
'520' => 'priglashaem-na-blagotvoritelnyj-turnir-po-boulingu-2',	
'518' => 'oglyadyvayas-na-novyj-god',	
'517' => 'priglashaem-na-blagotvoritelnyj-turnir-po-boulingu',	
'516' => 'pozdravlyaem-valeriyu-fridrihovnu',	
'515' => 'moya-mama-samaya-krasivaya-2',	
'514' => 'rok-bez-raka-spasibo-vsem',	
'512' => 'proekt-podarok-pozdravlyaem-nashih-yanvarskih-imeninnikov',	
'513' => 'gotovimsya-k-blagotvoritelnym-yarmarkam-priglashaem-k-sotrudnichestvu',	
'510' => 'prosim-pomoch-moyushhimi-sredstvami',	
'511' => 'nuzhem-melkozernistyj-plastilin',	
'507' => 'spasibo-sankt-peterburgskoj-komande-zenit',	
'508' => 'roditeli-roditelyam-pomogite-tem-kto-na-podderzhke-2',	
'509' => 'iskorka-uchastvuet-v-pravoslavnoj-vystavke-vo-slavu-bozhiyu',	
'505' => 'blagodarnost-ot-mamy-ani-aksyonovoj',	
'506' => 'u-proekta-podarok-poyavilsya-novyj-partnyor',	
'503' => 'priglashaem-na-blagotvoritelnyj-kontsert-rok-bez-raka',	
'504' => 'vizit-professora-gerajna-v-k-v-oblastnoj-onkogematologicheskij-tsentr-dlya-detej-i-podrostkov',	
'500' => 'blagodarnost-vracham-ot-semi-bekeevyh-i-merzagulovyh',	
'498' => 'voskresnaya-shkola-hrama-sergiya-radonezhskogo-pozdravila-nashih-detej',	
'499' => 'iz-starokamyshinska-s-lyubovyu-i-podderzhkoj',	
'495' => 'moya-mama-samaya-krasivaya',	
'496' => 'kesh-boksy-iskorki-v-magnitogorske',	
'497' => '4-yanvarya-vstrechaemsya-na-novogodnem-predstavlenii',	
'494' => 'spasibo-za-krasavitsu-yolku',	
'493' => 'reabilitatsiya-novyj-god-v-ankure',	
'492' => 'ochen-nuzhna-yolka',	
'489' => 'dekabrskih-imeninnikov-pozdravlyaem-ot-vsej-dushi',	
'490' => 'zapushhen-proekt-podarok',	
'491' => 'roditeli-roditelyam-pomogite-tem-kto-na-podderzhke',	
'486' => 'novyj-god-na-poroge',	
'487' => 'roditeli-roditelyam-my-vse-vmeste',	
'488' => 'zanyatiya-po-glinoterapii-chudesa-svoimi-rukami',	
'485' => 'kratkij-otchyot-o-rashodah-iskorki',	
'483' => 'u-zheni-tyazhkina-popolnenie-semejstva',	
'484' => 'prazdnik-priblizhaetsya',	
'479' => 'spasibo-za-pomoshh-vsem-vsem-vsem',	
'480' => 'nuzhno-privezti-sobachku',	
'481' => '20-dekabrya-v-18-chasov-pervoe-zanyatie-po-glinoterapii',	
'482' => 'uroki-tvorchestva-ot-yuli',	
'476' => 'iskorka-provodit-reabilitatsionnuyu-programmu-po-glinoterapii',	
'477' => 'nash-sajt-zanyal-3-mesto-v-uralskom-federalnom-okruge-v-konkurse-obshhestvennoe-priznanie',	
'478' => 'reabilitatsionnaya-programma-iskorka-sportivnaya-nachalas',	
'474' => 'rabota-blagotvoritelnogo-butika-v-zimnij-period-budet-osushhestvlyatsya-v-rezhime-yarmarok',	
'475' => 'pozdravlyaem-nashego-doktora-teplyh-elenu-vladimirovnu-s-dnyom-rozhdeniya',	
'472' => 'proshyol-novogodnij-parad-chudes',	
'473' => 'blagodarim-vseh-uchastnikov-blagotvoritelnogo-kontserta-v-starokamyshinske',	
'470' => 'srochno-prosim-pomoshhi-dlya-mustakimovoj-aliny',	
'471' => 'zhdyom-vseh-na-parade-chudes-15-dekabrya-v-11-chasov',	
'462' => 'pozdravlyaem-sajt-deti74-ru-s-dnyom-rozhdeniya',	
'456' => 'skazki-i-zagadki-ot-studentov-biofaka-chelgu',	
'457' => 'violetta-kataetsya-v-novoj-kolyaske',	
'458' => 'spasibo-vsem-uchastnikam-i-zritelyam-spektaklya-lyubov-i-golubi',	
'454' => 'pozdravlyaem-vseh-nashih-volontyorov-s-prazdnikom',	
'455' => 'buratino-speshit-k-rebyatam',	
'452' => 'proekt-korobka-hrabrosti-v-dejstvii-2',	
'453' => 'proshlo-ocherednoe-sobranie-soveta-dvizheniya-pomoshhi-onkobolnym-detyam-iskorka',	
'450' => 'malenkoj-violette-nuzhna-zimnyaya-kolyaska',	
'451' => 'srochno-trebuetsya-voditel-na-nashe-sotsialnoe-taksi',	
'449' => 'masha-i-medved-poradovali-detej-otdeleniya',	
'448' => 'blagodarnost-za-razvitie-donorstva',	
'446' => 'startovala-aktsiya-otprav-novogodnyuyu-otkrytku-rebyonku',	
'447' => 'proekt-korobka-hrabrosti-v-dejstvii',	
'445' => 'obmen-opytom-po-igram-pobeditelej',	
'444' => 'gotovimsya-k-godu-loshadi',	
'442' => 'priglashaem-vseh-na-parad-chudes',	
'443' => 'prosim-progolosovat-za-nashih-partnyorov-i-pomoshhnikov-v-provedenii-nashih-proektov',	
'441' => 'pozdravlyaem-nashih-detej-pobedivshih-rak',	
'440' => 'bolshoe-spasibo-vsem-volontyoram-kotorye-pomogayut-nashim-detyam',	
'435' => 'spasibo-za-pomoshh-3',	
'436' => 'ishhem-pomoshh-repetitora-dlya-podgotovke-k-ege',	
'437' => 'nam-pomogaet-sim-2',	
'438' => 'i-snova-spasibo-simu',	
'439' => 'ramki-dlya-rabot-pribyli-no-nado-eshhyo',	
'434' => 'blagodarim-blagotvoritelnyj-fond-konstantina-habenskogo',	
'432' => 'znakomtes-s-planom-raboty-na-noyabr-dekabr-2013-goda',	
'433' => 'sajt-iskorki-voshyol-v-chislo-pobeditelej-ii-otkrytogo-konkursa-uralskogo-federalnogo-okruga',	
'428' => 'prosim-pomoshhi-v-proekte-sotsialnoe-taksi',	
'429' => 'pomogite-nashim-tvorcheskim-detyam',	
'430' => 'spasibo-za-pomoshh-mishe',	
'431' => 'spasibo-starokamyshintsam-za-horoshee-nastroenie',	
'426' => 'srochno-nuzhny-pampersy-4-razmer',	
'427' => 'v-sime-ustanovili-tretij-yashhik-dlya-pozhertvovanij',	
'423' => 'spasibo-detskomu-sadu-8-goroda-sim',	
'424' => 'spasibo-studentam-i-prepodavatelyam-chelyabiskogo-promyshlenno-gumanitarnogo-tehnikuma',	
'425' => 'o-bf-dobrynya',	
'422' => 'ne-zabyvaem-pro-prazdnik-v-dobroj-loshadke',	
'419' => 'sotrudniki-portala-izyum-ekspert-pomogli-ksyushe',	
'420' => 'spasibo-za-detskie-maski',	
'421' => 'kuplena-eshhyo-odna-upakovka-lekarstva-dlya-maksima-krinzhina',	
'416' => 'pomogite-ksyushe-zamyshlyaevoj',	
'417' => 'nauchnaya-konferentsiya-i-konsultatsiya-professora-o-g-zheludkovoj',	
'418' => 'blagotvoritelnaya-aktsiya-na-ledovoj-arene-traktor',	
'414' => 'druzya-nuzhna-vasha-pomoshh',	
'415' => 'kak-ya-stala-volontyorom-iskorki',	
'413' => 'spasibo-akademii-razvlechenij-parad-iz-prazdnikov',	
'411' => 'spasibo-za-ingalyatory',	
'412' => 'prazdnik-ot-studentov-yuurgtk',	
'410' => 'nam-pomogaet-sim',	
'409' => 'nas-snova-priglashaet-konnyj-klub-dobraya-loshadka',	
'406' => 'dostavleny-postelnye-prinadlezhnosti-dlya-otdeleniya-onkogematologii-i-reanimatsii',	
'407' => 'zakonchilas-nedelya-donora',	
'408' => 'nuzhna-pomoshh-v-proekte-sotsialnoe-taksi-2',	
'405' => 'podarki-dlya-detej-otdeleniya-ot-magnitogortsev-pribyli',	
'404' => 'podderzhite-nash-proekt-detskij-vyezdnoj-lager',	
'403' => 'otdelenie-polnostyu-obespecheno-novymi-postelnymi-prinadlezhnostyami',	
'402' => 'na-sajte-bolshe-ne-budet-nomerov-telefonov-roditelej',	
'400' => 'gordimsya-nashimi-zemlyakami',	
'401' => 'prodolzhaetsya-sbor-sredstv-na-pokupku-lekarstva-dlya-krinzhina-maksima',	
'399' => 'seme-ahtemzyanovyh-nuzhna-pomoshh',	
'398' => 'nashi-partnyory-agrofirma-plant-priglashaet-lyubitelej-krasoty-za-tyulpanami',	
'397' => 'nuzhna-smes-dlya-igoninoj-kristiny',	
'395' => 'u-ani-aksyonovoj-vsyo-horosho',	
'396' => 'nuzhna-pomoshh-v-otdelenie-2',	
'394' => 'pomogite-vale',	
'392' => 'interesnaya-informatsiya-na-biznes-forume-uralskaya-zhemchuzhina',	
'391' => 'kuplen-kosmegen-dlya-6-detej-blagodarim-vseh-zhertvovatelej',	
'389' => 'srochno-prosim-pomoshhi-dlya-maksima',	
'390' => 'nuzhna-pomoshh-aline',	
'386' => 'den-znanij-v-otdelenii',	
'387' => 'v-otdelenie-trebuetsya-psiholog',	
'388' => 'priglashaem-na-obshhestvenno-politicheskij-vernisazh',	
'385' => 'nuzhny-odnospalnye-kresla-krovati-dlya-sterilnyh-boksov',	
'384' => 'proshla-blagotvoritelnaya-vechernika-den-rozhdeniya-majkla-dzheksona',	
'383' => 'spasibo-za-pomoshh-2',	
'382' => 'srochno-nuzhna-govoryashhaya-kukolka-i-zheleznye-roboty',	
'381' => 'spasibo-vsem-kto-pomogaet-detyam-vyzdorovet',	
'380' => 'spasibo-konnomu-klubu-dobraya-loshadka',	
'379' => 'prazdnik-v-konnom-klube-dobraya-loshadka',	
'375' => 'tolko-smelym-pokoryayutsya-morya-i-rechki',	
'376' => 'nuzhna-pomoshh-v-proekte-sotsialnoe-taksi',	
'377' => 'blagotvoritelnaya-vecherinka-den-rozhdeniya-majkla-dzheksona',	
'378' => 'nuzhna-pomoshh-v-otdelenie',	
'374' => 'spasibo-denisu-za-podarok-naste-timofeevoj',	
'373' => 'proshla-press-konferentsiya-po-itogam-festivalya-10-dobryh-del',	
'371' => 'spasyom-lager-sheredar',	
'372' => 'uchastie-iskorki-v-pravoslavnoj-vystavke-vo-slavu-bozhiyu',	
'370' => 'otezd-iz-chelyabinska-na-splav',	
'368' => 'ne-zabyvaem-pro-splav-13-14-avgusta',	
'369' => 'trening-dlya-mam-18-avgusta',	
'367' => 'v-chelyabinske-otkrylsya-blagotvoritelnyj-butik-iskorka',	
'366' => 'itogi-festivalya-10-dobryh-del',	
'364' => 'splav-splav-splav-zapolnyaem-ankety',	
'365' => 'trening-dlya-mam',	
'361' => 'splav-splav-splav-novosti',	
'362' => 'otkrytie-blagotvoritelnogo-butika-3-avgusta',	
'363' => 'nas-priglashaet-konnyj-klub-dobraya-loshadka',	
'358' => 'gotovimsya-k-otkrytiyu-blagotvoritelnogo-butika',	
'359' => 'pomogite-naste-timofeevoj',	
'360' => 'iskorka-prinyala-uchastie-v-festivale-10-dobryh-del',	
'356' => 'pomogite-dashe-2',	
'357' => 'splav-splav-splav',	
'354' => 'lager-zakonchilsya-leto-prodolzhaetsya',	
'355' => 'zhdyom-foto-ot-vseh-uchastnikov-lagernoj-smeny',	
'353' => 'vozvrashhaemsya-iz-lagerya',	
'352' => '1-den-iz-zhizni-lagerya-iskorka',	
'350' => 'pravoslavnaya-vystavka-vo-slavu-bozhiyu-2',	
'351' => 'vystavka-mihaila-loseva',	
'348' => 'prosim-podderzhki-v-realizatsii-napravleniya-pomoshh-seme',	
'349' => 'pomogite-olegu-srochnyj-sbor',	
'346' => 'sotsialnoe-taksi-pochti-ukomplektovano',	
'347' => 'zavershilsya-marafon-schaste-dlya-nasti',	
'345' => 'priglashaem-na-blagotvoritelnyj-kontsert-27-iyunya',	
'344' => 'edem-v-lager',	
'341' => 'obrashhaemsya-za-pomoshhyu',	
'342' => 'my-uchastvuem-v-proekte-10-dobryh-del',	
'343' => 'blagotvoritelnyj-vypusk-tok-shou-miting',	
'339' => 'vstrecha-s-sotrudnikami-otp-banka',	
'340' => 'spasibo-vsem-nashi-zhertvovatelyam-nikite-burenkovu-kupleno-lekarstvo',	
'338' => 'blagodarim-zhitelej-nashego-goroda',	
'336' => 'eshhyo-raz-novosti-pro-lager',	
'337' => 'vstrecha-uchastnikov-vsemirnyh-igr-pobeditelej-s-detmi-otdeleniya',	
'334' => 'vozvrashhenie-pobeditelej',	
'335' => 'pomogite-glebu',	
'331' => 'blagodarim-za-pomoshh-skb-borej',	
'332' => 'komanda-iskorki-nadezhdy-utrom-13-iyunya-vyezzhaet-na-vsemirnye-igry-pobeditelej',	
'329' => 'spasibo-za-pomoshh-lekarstvo-kupleno',	
'330' => 'reabilitatsionnyj-lager-2013-2',	
'326' => 'vesennij-dozor',	
'327' => 'nuzhdayushhiesya-ili-moshenniki-kto-i-kak-nazhivaetsya-na-chuzhom-gore-neobhodimost-lecheniya-za-granitsej',	
'328' => 'pomogite-artyomu',	
'325' => 'reabilitatsiya-sorevnovaniya-po-strelbe',	
'324' => 'pomogite-spasti-zhizn-nastenke',	
'323' => 'uspeshno-proshyol-kubok-po-tantsevalnomu-shou',	
'322' => 'programma-sotsialnoe-taksi-startovala',	
'321' => 'itogi-goda-podvedet-blagotvoritelnoe-dvizhenie-iskorka',	
'319' => 'v-chelyabinskoj-oblasti-otkrylsya-forum-v-podderzhku-semi-i-detstva',	
'320' => 'v-chelyabinske-proshel-gorodskoj-blagotvoritelnyj-zabeg-bolshoe-serdtse',	
'318' => 'reabilitatsionnyj-lager-2013',	
'315' => 'obrashhenie-k-roditelyam-2',	
'316' => 'gotovimsya-k-otkrytiyu-unikalnogo-blagotvoritelnogo-butika-v-pomoshh-detyam-yuzhnogo-urala-s-onkogematologicheskimi-zabolevaniyami',	
'317' => 'menyayushhie-mir',	
'314' => 'na-kubke-po-tantsevalnomu-shou-budet-provedyon-blagotvoritelnyj-auktsion',	
'311' => 'blagodarim-zhitelej-nashego-goroda-i-nadeemsya-na-dalnejshee-sotrudnichestvo',	
'312' => 'blagotvoritelnyj-zabeg',	
'313' => 'diskussionnyj-vopros',	
'310' => 'pervyj-publichnyj-otchyot-iskorki',	
'308' => 'blagodarim-vas',	
'309' => 'otkrytyj-mir-2013-strahovanie-v-ssha',	
'306' => 'pomogite-valere',	
'307' => 'talant-vsegda-talant',	
'305' => 'yuridicheskaya-informatsiya-dlya-roditelej',	
'296' => 'sorevnovaniya-po-strelbe-na-kubok-rektora-yuurgu',	
'301' => 'zavershilas-nedelya-donora',	
'302' => 'spasibo-posetitelyam-torgovogo-kompleksa-fiesta-za-pomoshh-detyam',	
'303' => 'vsemirnye-detskie-igry-pobeditelej-g-moskva',	
'300' => 'pozdravlyaem-menyayushhih-mir',	
'299' => 'volontyory-ustroyat-dlya-onkobolnyh-malyshej-pashalnyj-prazdnik',	
'295' => 'nalogovyj-vychet-s-pozhertvovanij',	
'297' => 'iskorka-v-nedele-donora',	
'298' => 'krossfit-i-pomoshh-detyam',	
'294' => 'otkrytyj-mir-2013-vstuplenie',	
'293' => 'vazhnaya-informatsiya-dlya-zhertvovatelej',	
'291' => 'prosim-pomoshhi',	
'292' => 'blagodarim-za-pomoshh',	
'289' => 'pomogite-marine',	
'290' => 'pravitelstvo-rassmatrivaet-voprosy-popechitelstva-v-sotsialnoj-sfere',	
'288' => 'pomogite-dashe',	
'287' => 'obyavlyaem-srochnyj-sbor-sredstv',	
'286' => 'pozdravlyaem-pobeditelej-regionalnogo-etapa-vsemirnyh-igr-pobeditelej-chelyabinskoj-oblasti',	
'283' => 'ustroim-kinozal',	
'284' => 'informatsiya-o-pozhertvovaniyah',	
'285' => 'vsemirnye-igry-pobeditelej-2013-2',	
'280' => 'igry-pobeditelej-perenosyatsya',	
'281' => 'trebuetsya-pomoshh',	
'282' => 'chudesa-prerogativa-konfessionalnaya-a-ne-professionalnaya-dazhe-esli-eto-luchshaya-klinika-i-luchshie-vrachi-iz-za-granitsy-zhukovskaya-e-v',	
'279' => 'ostalis-schitanye-dni-speshite',	
'278' => 'mozhno-pomoch-mnogim-esli-dejstvovat-soobshha',	
'276' => 'vspominaya-rozhdestvo',	
'277' => 'priglashaem-na-vstrechu-so-skazochnitsej-i-volshebnitsej',	
'259' => 'priglashaem-na-igry-pobeditelej',	
'275' => 'prichastie-dlya-malyshej',	
'273' => 'pravoslavnaya-yarmarka-vo-slavu-bozhiyu',	
'272' => 'priglashaem-v-nashu-uyutnuyu-igrovuyu',	
'271' => 'rozhdestvenskie-angelochki-ispolnyayut-zhelaniya',	
'270' => 'v-igrovuyu-nuzhen-kover-nuzhna-pomoshh',	
'269' => 'pravoslavnaya-vystavka-vo-slavu-bozhiyu',	
'274' => 'rozhdestvo-hristovo',	
'268' => 'vstretimsya-vse-vmeste-v-sleduyushhem-godu',	
'267' => 'vospominaniya-o-lete-reabilitatsionnyj-lager-2012-goda',	
'266' => 'vsemirnye-igry-pobeditelej-2013',	
'265' => 'kniga-v-pomoshh',	
'264' => 'pozdravlyaem-anechku-kostarevu',	
'263' => 'pozdravlyaem-s-novym-godom',	
'262' => 'itogi-goda',	
'260' => 'pozhertvovaniya-cherez-sms',	
'258' => 'blagotvoritelnost-norma-zhizni',	
'257' => 'blagodarnost-ot-anastasii-anchinoj',	
'256' => 'obrashhenie-k-roditelyam',	
'255' => 'srochno-nuzhny-donory',	
'253' => '15-dekabrya-v-chelyabinske-sostoitsya-gala-kontsert-i-final-blagotvoritelnogo-festivalya-teplye-slova',	
'254' => 'blagotvoritelnyj-regionalnyj-festival-po-ulichnoj-horeografii',	
'252' => 'igrushki-v-korobku-hrabrosti-ot-sotrudnikov-ekspert-rembyttehniki',	
'250' => 'kontsert-ekateriny-romanovoj',	
'251' => 'kruglyj-stol-i-seminary-v-chelyabinske',	
'249' => 'tvorit-dobrye-dela-legko-2',	
'248' => 'my-govorim-spasibo',	
'247' => 'interesnoe-sobytie-17-11-2012',	
'246' => 'priglashaem-na-regionalnye-igry-pobeditelej',	
'245' => 'vnimanie',	
'242' => 'blagodarnost-ot-larisy-fyodorovny',	
'243' => 'nastoyashhie-muzhchiny-zhivut-v-chelyabinske',	
'241' => 'trebuetsya-vasha-pomoshh-dlya-malenkogo-artyoma',	
'240' => 'vyrazhaem-blagodarnost-3',	
'239' => 'nuzhna-pomoshh',	
'238' => 'nuzhna-nyanya-devochke',	
'236' => 'pamyatka-v-lager',	
'235' => 'splav',	
'234' => 'pomoshh-v-otdelenie',	
'232' => '1-iyunya-den-zashhity-detej',	
'233' => 'o-vsemirnyh-igrah-pobeditelej-v-moskve',	
'230' => 'uvazhaemye-deti-i-roditeli',	
'231' => 'blagotvoritelnyj-kontsert-dlya-ilnura-2',	
'228' => 'srochno-trebuetsya-pomoshh-volontyora',	
'229' => 'prosim-pomoshhi-dlya-polinochki-burlakovoj',	
'227' => 'blagotvoritelnyj-kontsert-dlya-ilnura',	
'226' => 'blagodarnost-ot-olgi-fedorovny',	
'225' => 'karimov-ilnur',	
'224' => 'zachem-im-eto-nado',	
'221' => 'oblastnoj-sotsialnyj-forum-v-podderzhku-semi-i-detstva',	
'222' => 'programma-konferentsii',	
'219' => 'poisk-sredstv-na-poezdku-v-moskvu',	
'220' => 'podarim-kristine-mechtu',	
'217' => 'letnyaya-reabilitatsiya',	
'218' => 'otchet-o-postuplenii-denezhnyh-sredstv-s-1-po-24-aprelya',	
'214' => 'obrashhenie-k-zhertvovatelyam',	
'215' => 'priglashenie-na-konferentsiyu',	
'216' => 'vsemirnye-detskie-igry-pobeditelej-2012',	
'213' => 'blagodarnost-ot-mamy',	
'223' => 'pomoshh-ot-an-kompanon',	
'212' => 'blagodarnost-olge',	
'210' => 'nemetskij-zhurnal-detskogo-fonda-po-zabolevaniyu-rakom-my',	
'211' => 'otchet-o-postuplenii-denezhnyh-sredstv-s-13-marta-po-2-fevralya',	
'209' => 'blagodarnost-ot-semi-sachko',	
'203' => 'dobrye-dela-s-dobrym-serdtsem',	
'201' => 'blagodarnost-za-pomoshh',	
'202' => 'poyavilas-novaya-vozmozhnost-okazaniya-pomoshhi-detyam-cherez-otpravku-sms-soobshhenij-u-abonentov-seti-bilajn',	
'200' => 'otchet-o-postuplenii-denezhnyh-sredstv-s-25-fevralya-po-12-marta',	
'199' => 'spasibo-za-pomoshh',	
'198' => 'pro-svet-vystavka-rabot-podopechnyh-fonda-podari-zhizn',	
'196' => 'uvazhaemye-zhertvovateli-pomogite-s-podarkami-detyam-na-konkurs',	
'197' => 'otchet-o-postuplenii-denezhnyh-sredstv-s-20-po-24-fevralya',	
'195' => 'srochno-trebuyutsya-donory',	
'194' => 'blagodarnost-ot-roditelej',	
'193' => 'vnimanie-vazhnoe-soobshhenie',	
'192' => 'malchishki-i-devchonki-a-takzhe-ih-roditeli',	
'191' => 'blagodarnost-ot-eleny-konovalovoj',	
'190' => 'blagodarnost-11-litseyu-g-chelyabinska',	
'189' => 'blagodarnost-ot-popovoj-natali',	
'187' => 'v-rf-okolo-80-bolnyh-rakom-detej-mogut-vyzdorovet-schitayut-eksperty',	
'188' => 'tvorit-dobrye-dela-legko',	
'186' => '15-fevralya-mezhdunarodnyj-den-borby-s-detskim-rakom',	
'185' => 'otchet-o-postuplenii-denezhnyh-sredstv-s-6-po-10-fevralya',	
'182' => 'zlatoust-v-pomoshh-detyam-s-onkozabolevaniyami',	
'183' => 'pomozhem-vmeste',	
'181' => 'blagodarnost-vam-uvazhaemye-zhertvovateli',	
'180' => 'polnyj-otchet-za-2011-god',	
'179' => 'otchet-o-postuplenii-tselevyh-sredstv-s-30-yanvarya-po-3-fevralya-2012-goda',	
'178' => 'vyrazhaem-blagodarnost-2',	
'177' => 'pravoslavnaya-vystavka',	
'175' => 'otchet-o-postuplenii-tselevyh-sredstv-s-19-po-27-yanvarya-2012-goda',	
'176' => 'blagodarnost-ot-eleny-vladimirovny',	
'173' => 'blagotvoritelnaya-aktsiya-tvoj-vklad',	
'174' => 'sotsialno-volonterskaya-aktsiya-ot-otp-banka',	
'172' => 'priglashenie-roditelej-na-prazdnik',	
'170' => 'pomoshh-v-provedenii-vystavki',	
'171' => 'otchet-o-postuplenii-tselevyh-denezhnyh-sredstv',	
'169' => 'vyrazhaem-blagodarnost',	
'168' => 'rozhdestvenskaya-elka-ot-gubernatora',	
'167' => 'igry-pobeditelej-2012',	
'166' => 'nabor-v-detskij-lager',	
'165' => 's-2012-goda-lechenie-v-moskovskih-bolnitsah-budet-platnym',	
'164' => 'informatsiya-po-fondu-nastenka',	
'163' => 'dolgozhdannaya-premera-spektaklya-revizor',	
'162' => 's-dnem-volontera',	
'161' => 'informatsiya-dlya-roditelej',	
'160' => 'kulinarnyj-prazdnik',	
'159' => 'preparat-kosmegen-razreshen-v-rossii',	
'158' => 'seminar-granty-dlya-nekommercheskih-organizatsij',	
'156' => 'vazhnyj-film-v-rossii',	
'157' => 'blagotvoritelnaya-programma-pervyj-million-melochyu',	
'155' => 'zapusk-proekta-korobka-hrabrosti',	
'152' => 'pomoshh-kristine-dushevskoj',	
'153' => 'perenos-spektaklya-revizor',	
'154' => 'informatsiya-o-vane-vojtoviche',	
'151' => 'dorogie-druzya-nashi-postoyannye-pomoshhniki-nam-srochno-nemedlenno-bezotlagatelno-nuzhna-vasha-pomoshh',	
'150' => 'blagotvoritelnyj-spektakl-v-podderzhku-detyam-s-onkogematologicheskimi-zabolevaniyami',	
'149' => 'pomoshh-i-blagodarnost',	
'148' => 'sotsialno-orientirovannye-nekommercheskie-organizatsii',	
'147' => 'obshhestvenno-politicheskij-vernisazh-2011',	
'146' => 'marafon-dobryh-del',	
'144' => 'komanda-iskorki-urala-deviz-iskorki-vpered-idut-uspeh-za-ruku-vedut',	
'145' => 'invalidnost-rebenku-s-lejkozom-budet-ustanavlivatsya-na-5-let',	
'142' => 'vot-i-zakonchilsya-nash-grandioznyj-proekt-pod-nazvaniem-lager-sotsialno-psihologicheskoj-reabilitatsii-detej-perenesshih-onkogematologicheskie-zabolevaniya-iskorka',	
'143' => 'blagodarnost',	
'139' => 'prosveshhenie-cherez-knigu',	
'140' => 'mir-detstva',	
'141' => 'obrashhenie-k-gubernatoru-chelyabinskoj-oblasti',	
'138' => 'detskij-vyezdnoj-reabilitatsionnyj-lager-dlya-detej-perenesshih-onkogematologicheskie-zabolevaniya',	
'136' => 'zhivye-legendy-pomogli-onkobolnym-detyam',	
'137' => 'vlozheniya-v-detej-effektivnye-i-dolgosrochnye-perspektivy-perspektivy-v-budushhee',	
'135' => 'nauchno-prakticheskaya-konferentsiya-po-problemam-detskoj-onkologii-v-g-kiev',	
'134' => 'hochu-podelitsya-radostyu',	
'132' => 'zhivye-legendy',	
'133' => 'igry-pobeditelej-2011-dlya-sponsorov-komand',	
'131' => 'hochesh-ob-etom-pogovorit',	
'129' => 'blagotvoritelnyj-kontsert-olgi-sergeevoj',	
'130' => 'blagotvoritelnyj-kontsert-yuriya-rozuma-i-fonda-andryusha',	
'127' => 'proekt-s-miru-po-nitke',	
'128' => '8-marta-prazdnik-dlya-vseh',	
'126' => 'redkij-den-dlya-osobennyh-lyudej',	
'124' => 'sud-ostavil-bez-vnimaniya-narushenie-konstitutsionnyh-prav-rebenka-invalida',	
'125' => 'dorogie-druzya-zhertvovateli-vse-neravnodushnye-lyudi-nam-opyat-ne-obojtis-bez-vashej-podderzhki',	
'122' => 'vsemirnye-detskie-igry-pobeditelej',	
'123' => 'pravoslavnaya-yarmarka',	
'121' => 'blagotvoritelnyj-vecher-v-pomoshh-onkobolnym-detyam',	
'120' => 'nam-nuzhen-vash-talant',	
'118' => 'dobrye-serdtsa',	
'116' => 'novogodnij-podarok',	
'117' => 's-miru-po-nitke',	
'115' => 'shkolniki-organizovali-blagotvoritelnyj-kontsert',	
'114' => 's-novym-2011-godom',	
'113' => 'platsebo-istselenie',	
'112' => 'uvazhaemye-blagotvoriteli-i-volontery',	
'110' => 'lekarstva-dlya-tyazhelobolnyh-detej-zaderzhany-na-tamozhne-v-aeroportu-vnukovo',	
'111' => 'lekarstva-proshli-tamozhnyu-no-problemy-s-oformleniem-ih-vvoza-poka-ostayutsya',	
'107' => 'programma-autotreninga-platsebo',	
'108' => 'andreyu-neobhodima-vasha-pomoshh',	
'109' => 'u-nas-talanty',	
'106' => 'dobrye-dela-zdes-i-sejchas-2',	
'105' => 'srochno',	
'104' => 'dorogie-druzya',	
'101' => 'dobrye-dela-zdes-i-sejchas',	
'102' => 'podari-ulybku',	
'103' => 'kruglyj-stol-na-temu-prakticheskie-vozmozhnosti-okazaniya-pomoshhi-detyam-s-onkogematologicheskimi-zabolevaniyami',	
'100' => 'aktsiya-ya-hochu-zhit',	
'98' => 'sostoitsya-krugloglyj-stola-na-temu-prakticheskie-vozmozhnosti-okazaniya-pomoshhi-detyam-s-onkogematologicheskimi-zabolevaniyami',	
'99' => 'uvazhaemye-druzya',	
'97' => 'srochno-nuzhen-preparat-kardioksan-dlya-nurievoj-nastenki',	
'96' => 'v-obshhestvenno-politicheskij-vernisazh-priurochennyj-ko-dnyu-goroda',	
'95' => 'post-reliz-energiya-sily-i-zdorove',	
'94' => 'v-ramkah-prazdnovaniya-dnya-goroda-projdet-obshhestvenno-politicheskij-vernisazh',	
'93' => 'dobrye-dela-pod-devizom-zdes-i-sejchas-prodolzhayutsya',	
'91' => 'energiya-sily-lechit',	
'90' => 'dobrye-dela-pod-devizom-zdes-i-sejchas',	
'88' => 'nadezhnaya-reputatsiya',	
'89' => 'chelyabintsy-v-pomoshh-lere-gorbunovoj',	
'86' => 'blagotvoritelnyj-kontsert-dlya-lery-gorbunovoj',	
'84' => 'chudotvornaya-ikona-vsetsaritsa',	
'83' => 'otchet-o-provedenii-mezhregionalnoj-nauchno-prakticheskoj-konferentsii-s-mezhdunarodnym-uchastiem-mediko-sotsialnye-aspekty-okazaniya-pomoshhi-patsientam-s-onkogematologicheskoj-patologiej',	
'81' => 'hronika-igr-pobeditelej',	
'80' => 'sostoitsya-konferentsiya-21-22-iyunya',	
'79' => 'srochno-2',	
'78' => 'nuzhna-pomoshh-volonterov',	
'76' => 'igry-pobeditelej',	
'77' => 'po-tu-storonu-realinosti',	
'82' => 'muzykanty-the-spirit-of-pink-floyd-porazilis-muzhestvu-chelyabinskih-detej',	
'75' => 'zooterapiya-luchshee-lekarstvo-ot-stressa',	
'87' => 'i-rossijskaya-konferentsii-s-mezhdunarodnym-uchastiem-deyatelnost-protivorakovyh-nekommercheskih-organizatsij-v-detskoj-onkologii',	
'74' => 'shary-zhizni',	
'72' => 'uvazhaemye-roditeli-opekuny',	
'73' => 'dni-semi-mir-detstva',	
'71' => 'mediko-sotsialnye-aspekty-okazaniya-pomoshhi-patsientam-s-onkogematologicheskoj-patologiej',	
'69' => 'solnechnyj-malchik',	
'67' => 'rok-n-rol-v-pomoshh',	
'62' => 'obrashhenie-k-prezidentu',	
'63' => 'kliniki-turtsii',	
'64' => '21-22-iyunya-2010-goda-sostoitsya-mezhregionalnaya-nauchno-prakticheskaya-konferentsiya',	
'65' => '21-maya-2010-g-sostoitsya-i-rossijskaya-konferentsiya-s-mezhdunarodnym-uchastiem',	
'66' => 'shary-blagotvoritelnosti',	
'60' => 'shkolniki-otkryvayut-serdtsa',	
'61' => 'pochta-radosti',	
'58' => '20-21-marta-v-torgovom-komplekse-slava-g-kopejska-proshla-blagotvoritelnaya-aktsiya-ya-hochu-zhit',	
'59' => 'avtomobil-moej-mechty',	
'57' => 'rano-vyyavlennyj-rak-izlechim-v-95-protsentah-sluchaev',	
'56' => 's-8-marta',	
'55' => 'aktsiya-podari-sverstniku-radost',	
'54' => 'trebuetsya-uborshhik',	
'51' => 'podari-ulybku-2',	
'52' => 'k-mezhdunarodnomu-dnyu-borby-s-detskim-rakom-na-kirovke-zazhgli-trista-svechej-nadezhdy',	
'53' => 'v-chelyabinske-startuet-blagotvoritelnaya-programma-pervyj-million-melochyu',	
'50' => '15-fevralya-2010-goda-v-11-00-sostoitsya-press-konferentsiya',	
'49' => '15-fevralya-vo-vsem-mire-otmechaetsya-mezhdunarodnyj-den-borby-s-detskim-rakom',	
'47' => 'dobrye-dela',	
'48' => 'otvety-chinovnikov',	
'46' => 'moskovskie-spetsialisty-vysoko-otsenili-sistemu-onkologicheskoj-pomoshhi-na-yuzhnom-urale',	
'44' => 'zavershenie-vystavki-hristos-rozhdaetsya-slavite-2010g',	
'45' => 'finansovye-itogi-za-2009-god',	
'43' => 'iskorka-na-radio',	
'41' => 'hristos-rozhdaetsya-slavite-2010',	
'42' => 'dobrye-dela-prodolzhayutsya',	
'39' => 'speshite-delat-dobro',	
'40' => 'srochno-nuzhny-volontery-s-26-po-28-yanvarya',	
'38' => 'novogodnij-syurpriz',	
'37' => 's-novym-godom',	
'36' => 'podhodit-k-zaversheniyu-2009-god-vokrug-tsarit-predprazdnichnaya-sueta-no-est-lyudi-kotorye-nesmotrya-ni-na-chto-delayut-dobrye-dela',	
'33' => 'vnimanie-kontsert-otmenyaetsya',	
'17' => 'mozhno-provesti-osnovnoe-lechenie-ot-zlokachestvennyh-novoobrazovanij-i-poteryat-rebenka-potomu-chto-u-organizma-net-sil',	
'21' => 'smena-bankovskih-rekvizitov',	
'31' => 'fotografii-iz-kosmosa-v-podderzhku-detej-detskogo-onkogematologicheskogo-otdeleniya',	
'27' => 'krasota-spaset-detej',	
'28' => 'blagotvoritelnyj-fond-schastlivyj-mir',	
'30' => 'sos-srochno-neobhodimo-lekarstvo-meronem',	
'26' => 'sluzhba-krovi',	
'24' => 'vstat-na-nogi',	
'25' => 'dobrye-serdtsa-2',	
'23' => 'vkusnoe-sobytie',	
'22' => 'nalogovye-lgoty-blagotvoritelyam',	
'20' => 'uchimsya-stroit-schastlivoe-budushhee-nashih-detej',	
'35' => 'sotsiologicheskij-opros-2009g',	
'19' => 'obrazovatelnyj-proekt-dlya-vrachej-gematologov-onkologov-pediatrov-srednego-meditsinskogo-personala-g-chelyabinsk-26-27-oktyabrya-2009-goda',	
'18' => 'krasota-spasyot-detej',	
'16' => 'vane-v-pomoshh-nuzhen-dobryj-chelovek',	
'15' => 'lechenie-detej-v-onkogematologicheskom-otdelenii-prodolzhaetsya',	
'14' => 'uchene-svet-a-neuchene-tma',	
'13' => 'iskorka-prinyala-uchastie-v-gorodskom-vernisazhe',	
'12' => 'sozdadim-krugovuyu-poruku-dobra',	
'11' => '21-avgusta-2009g-sostoitsya-aktsiya-detskij-rak-izlechim',	
'8' => 'blagotvoritelnyj-kontsert-17-marta',	
'5' => 'ura-novyj-god',	
'7' => 'prazdnik-k-nam-prishel',	
'9' => 'prazdnik-nadezhda',		
	);
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$id = intval($_GET['id']);
		if(isset($ids[$id])){
			$redirect = home_url($ids[$id]);
		}
	}
/*	
'344' => 'children/smetanin-roman/	
'319' => 'children/bukreev-danil-1-god/	
'395' => 'children/shott-artyom/*/

	if(!empty($redirect)){
		wp_redirect($redirect, 301);
        exit();
	}
}