<?php
if(!defined('ABSPATH')) die; // Die if accessed directly

class TSTL_Core {

	private static $instance = NULL; //instance store
	private $manifest = null;
	private $filename = 'emails-VR4pD3sAYvf5r8GHE4bM6WbA.csv';


	private function __construct() {

		add_action('customize_register', array($this, 'customize_register'), 15);

		add_filter( 'template_include', array($this, 'tst_custom_template'));

		add_action('tstl_head',	array($this, 'load_styles'));
		add_action('tstl_head', array($this, 'load_scripts'),30);
		add_action('tstl_footer', 'wp_print_footer_scripts', 20);

	}


	/** instance */
    public static function get_instance(){

        if (NULL === self :: $instance)
			self :: $instance = new self;

		return self :: $instance;
    }

	static function on_activation() {
		update_option('blogdescription', 'Автономная некоммерческая организация');
		update_option('blogname', 'Новая жизнь');
	}


	/** Option for text *-*/
	public function customize_register(WP_Customize_Manager $wp_customize) {

		$wp_customize->add_setting('landing_text', array(
			'default'   => '',
			'transport' => 'postMessage',
			'type' 		=> 'option'
		));

		$wp_customize->add_control('landing_text', array(
			'type'     => 'textarea',
			'label'    => 'Текст лендинга',
			'section'  => 'title_tagline',
			'settings' => 'landing_text',
			'priority' => 28
		));

		$wp_customize->add_setting('landing_url', array(
			'default'   => '',
			'transport' => 'postMessage',
			'type' 		=> 'option'
		));

		$wp_customize->add_control('landing_url', array(
			'type'     => 'text',
			'label'    => 'URL группы',
			'section'  => 'title_tagline',
			'settings' => 'landing_url',
			'priority' => 29
		));

		$wp_customize->add_setting('landing_to_email', array(
			'default'   => '',
			'transport' => 'postMessage',
			'type' 		=> 'option'
		));

		$wp_customize->add_control('landing_to_email', array(
			'type'     => 'text',
			'label'    => 'Адреса для уведомлений',
			'section'  => 'title_tagline',
			'settings' => 'landing_to_email',
			'priority' => 29
		));
	}

	/** Add custom template for statis home **/
	public function tst_custom_template($template){

		if(is_front_page()){
			$template = TSTL_PLUGIN_DIR.'/template/landing.php';

		}
		elseif(is_home()) {
			$home = get_option('show_on_front');
			if($home == 'posts') {
				$template = TSTL_PLUGIN_DIR.'/template/landing.php';
			}
		}

		return $template;
	}




	/** Load css **/
	private function get_manifest() {

		if(null === $this->manifest) {
			$manifest_path = TSTL_PLUGIN_DIR.'/assets/rev/rev-manifest.json';

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

	public function load_styles() {

		$style_file = TSTL_PLUGIN_DIR.'/assets/rev/'.$this->get_rev_filename('bundle.css');
		if(file_exists($style_file)) {
			echo "<style>";
			echo file_get_contents($style_file);
			echo "</style>";
		}

	}

	/** Load JS **/
	public function load_scripts() {


		// jQuery
		$script_dependencies[] = 'jquery'; //adjust gulp if we want it in footer


		// front
		wp_enqueue_script(
			'tst-front',
			TSTL_PLUGIN_BASE_URL.'/assets/rev/'.$this->get_rev_filename('bundle.js'),
			$script_dependencies,
			null,
			true
		);


		wp_localize_script('tst-front', 'frontend', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));

	}

	public function button_url(){

		$url = '#';
		if($this->is_mobile_user_agent()) {

			$url = get_option('landing_url');
		}

		return $url;
	}

	public function is_mobile_user_agent(){
		//may be need some more sophisticated testing
		$test = false;

		if(!isset($_SERVER['HTTP_USER_AGENT']))
			return $test;

		if(stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
			$test = true;
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
			$test = true;
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
			$test = true;
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
			$test = true;
		}
	//	return true;
		return $test;
	}

	/** Store data in DB **/
	public function store_phone($phone) {

		$file = TSTL_PLUGIN_DIR.'assets/data/'.$this->filename;

		if(!is_writable(dirname($file))){
			return new WP_Error('no_dir', 'Directory for log file is not writable');
		}

		if(file_exists($file)){
			$csv_handler = fopen($file,'a');
		}
		else {
			$csv_handler = fopen($file,'w');
			if($csv_handler){
				fputcsv($csv_handler, array('Date', 'Phone'), ",");
			}
		}

		$r = array();
		$r[] = date('d.m.Y H:i', strtotime('now'));
		$r[] = $phone;


		fputcsv($csv_handler, $r, ",");
		fclose($csv_handler);

		return true;
	}


	/** send email **/
	public function notify($phone) {

		$to_email = get_option('landing_to_email');

		if(empty($to_email))
			$to_email = get_bloginfo('admin_email');

		//send email
		$headers = array();
		$from_name = get_bloginfo('name');

		//$from_email = get_bloginfo('admin_email');
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@'.$sitename;

		if(!empty($from_email))
			$headers[] = "From: {$from_name} <{$from_email}>";

		$subject = 'Запрос на добавление в группу через сайт';
		$txt = apply_filters('the_content', '<p>Поступил запрос на добалвение в группу через сайт. Номер телефона: '.$phone.'</p>');
		$txt .= apply_filters('the_content', '<p>Дата и время запроса: '.date('d.m.Y H:i', strtotime('now')).'</p>');

		add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

		if(!wp_mail($to_email, wp_specialchars_decode($subject), $txt, $headers)) {
			return new WP_Error('no_email', 'Email notification was not send');
		}


		return true;
	}

} // class


add_action("wp_ajax_tst_submit_form", "tst_submit_form_screen");
add_action("wp_ajax_nopriv_tst_submit_form", "tst_submit_form_screen");

function tst_submit_form_screen() {

	$result = array('type' => 'ok', 'msg' => '');

	if(!wp_verify_nonce($_REQUEST['nonce'], "tstl_join_group")) {
		die('nonce error');
	}

	$phone = (isset($_REQUEST['phone'])) ? trim($_REQUEST['phone']) : '';
	preg_match('/^\+?7?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', $phone, $m);

	if(empty($m)){
		$result['type'] = 'error';
		$result['msg'] = '<div class="form-message form-message--error">Вы указали некорректный номер телефона</div>';
	}
	else {

		$core = TSTL_Core::get_instance();
		$store = $core->store_phone($phone);

		if(!is_wp_error($store)){
			$store = $core->notify($phone);
		}

		if(is_wp_error($store)){
			$result['type'] = 'error';
			$result['msg'] = '<div class="form-message form-message--error">Во время отправки формы произошла ошибка. Пожалуйста, попробуйте еще раз.';
			if(defined('TST_DEVMODE')){
				$result['msg'] .= $store->get_error_message();
			}

			$result['msg'] .= '</div>';
		}
		else{
			$result['msg'] = '<div class="form-message form-message--ok">Спасибо, ваш номер отправлен администратору и будет добавлен к&nbsp;группе.</div>';
		}
	}


	echo json_encode($result);
	die();
}
