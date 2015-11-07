<?php
/**
 * Class for CSSJS handling
 **/

class TST_CssJs {
	
	private static $_instance = null;	
	private $manifest = null;
	
	private function __construct() {
		
		
		add_action('wp_enqueue_scripts', array($this, 'load_styles'), 30);
		add_action('wp_enqueue_scripts', array($this, 'load_scripts'), 30);
		add_action('init', array($this, 'disable_wp_emojicons'));
		
		add_action('admin_enqueue_scripts',  array($this, 'load_admin_scripts'), 30);
		add_action('login_enqueue_scripts',  array($this, 'load_login_scripts'), 30);
		
		add_action('template_redirect',  array($this, 'leyka_fix'), 80);
		
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }
		
        return self::$_instance;
    }
	
	/** revisions **/
	private function get_manifest() {
		
		if(null === $this->manifest) {
			$manifest_path = get_template_directory().'/assets/rev/rev-manifest.json';

			if (file_exists($manifest_path)) {
				$this->manifest = json_decode(file_get_contents($manifest_path), TRUE);
			} else {
				$this->manifest = array();
			}
		}
		
		return $this->manifest;
	}
	
	
	public function get_rev_filename($filename) {
		
		$manifest = $this->get_manifest();
		if (array_key_exists($filename, $manifest)) {
			return $manifest[$filename];
		}
	
		return $filename;
	}
	
	/* load css */
	function load_styles() {
		
		$url = get_template_directory_uri();
		$style_dependencies = array();
				
		// fonts
		wp_enqueue_style(
			'tst-roboto',
			'//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic&subset=latin,cyrillic',
			$style_dependencies,
			null
		);
		$style_dependencies[] = 'tst-roboto';
		
		// icons
		wp_enqueue_style(
			'tst-material-icons',
			'//fonts.googleapis.com/icon?family=Material+Icons',
			$style_dependencies,
			null
		);	
		$style_dependencies[] = 'tst-material-icons';
	
		// design
		wp_enqueue_style(
			'tst-design',
			$url.'/assets/rev/'.$this->get_rev_filename('bundle.css'),
			$style_dependencies,
			null
		);
		
		//remove leyka default styles
		if(!is_admin()){
			wp_dequeue_style('leyka-plugin-styles');					
		}
	}
	
	
	/* front */
	public function load_scripts() {
		
		$url = get_template_directory_uri();
				
		// jQuery
		$script_dependencies[] = 'jquery'; //adjust gulp if we want it in footer	
		
		// front
		wp_enqueue_script(
			'tst-front',
			$url.'/assets/rev/'.$this->get_rev_filename('bundle.js'),
			$script_dependencies,
			null,
			true
		);
		
		wp_localize_script('tst-front', 'frontend', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}
	
	/** disable emojji **/
	public function disable_wp_emojicons() {
	
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	
	}
	
	/* admin styles - moved to news system also */
	public function load_admin_scripts() {
		
		$url = get_template_directory_uri();
			
		wp_enqueue_style('tst-admin', $url.'/assets/rev/'.$this->get_rev_filename('admin.css'), array(), null);		
				
	}
	
	/* login style - make it inline ? */
	public function load_login_scripts(){
	
	?>
		<style>
			#login h1 {display: none !important;}
			#nav {display: none !important;}
		</style>
	<?php
	}
	
	public function leyka_fix() {
		
		if(!class_exists('Leyka'))
			return;
		
		$leyka = leyka();
		
		if(!is_singular('leyka_campaign')){
			remove_action('wp_enqueue_scripts', array($leyka, 'enqueue_styles'));
			remove_action('wp_enqueue_scripts', array($leyka, 'enqueue_scripts'));
		}
		
	}
	
} //class

TST_CssJs::get_instance();
