<?php
/** Social buttons **/
class TST_SocialButtons {

	private static $_instance = null;
	protected $config = array();

	private function __construct() {

		//create config
		$this->config = array(
			'facebook' 	=> __('Facebook', 'tst'),
			//'twitter' 	=> __('Twitter', 'tst'),
			//'youtube' 	=> __('YouTube', 'tst'),
			'instagram' => __('Instagram', 'tst'),
			'vk' 		=> __('VKontakte', 'tst'),
			//'ok' 		=> __('Odnoklassniki', 'tst'),
			//'telegram' 	=> __('Telegram', 'tst')
		);

		add_action('customize_register', array($this, 'customize_register'), 20);
	}

	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


	/* options in customizer */
	public function customize_register(WP_Customize_Manager $wp_customize) {

		$wp_customize->add_section( 'tst_social_options', array(
			'title' 		=> 'Социальные ссылки',
			'priority' 		=> 65,
			'capability' 	=> 'edit_theme_options',
		));

		foreach($this->config as $key => $label) {
			$wp_customize->add_setting('tst_social_'.$key, array(
				'default'   => '',
				'transport' => 'postMessage',
				'type' 		=> 'option'
			));

			$wp_customize->add_control('tst_social_'.$key, array(
				'type'     => 'text',
				'label'    => $label,
				'section'  => 'tst_social_options',
				'settings' => 'tst_social_'.$key,
				'priority' => $key*2+1,
			));
		}
	}

	/** Getters **/
	public function get_social_button_link($key = 'facebook') {

		if(empty($key) || !in_array($key, array_keys($this->config))){
			return new WP_Error('wrong_key', 'Unsupported key - please change your config');
		}

		return get_option('tst_social_'.$key);
	}

	public function get_social_button($key = 'facebook') {

		if(empty($key) || !in_array($key, array_keys($this->config))){
			return new WP_Error('wrong_key', 'Unsupported key - please change your config');
		}

		$link = get_option('tst_social_'.$key);
		if(!$link)
			return '';

		return "<a href='{$link}' target='_blank' class='social-buttons__link social-buttons__link--{$key}'>".tst_svg_icon('icon-'.$key, false)."</a>";
	}

	public function get_rss_link() {

		$link= home_url('feed');
		return "<a href='{$link}' target='_blank' class='social-buttons__link social-buttons__link--rss'>".tst_svg_icon('icon-rss', false)."</a>";
	}

	public function get_social_buttons_list($keys = array(), $show_feed = false){

		if(empty($keys)){
			$keys = array_keys($this->config);
		}

		$out = "<ul class='social-buttons'>";
		$list = array();
		foreach($keys as $key) {
			$btn = $this->get_social_button($key);
			if(!is_wp_error($btn) && !empty($btn)){
				$list[] = "<li class='social-buttons__item social-buttons__item--{$key}'>{$btn}</li>";
			}
		}

		if($show_feed) {
			$rss = $this->get_rss_link();
			$list[] = "<li class='social-buttons__item social-buttons__item--rss'>{$rss}</li>";
		}

		$out .= implode($list)."</ul>";

		return $out;
	}

} //class

TST_SocialButtons::get_instance();


/** wrappers **/
function tst_get_social_button_link($key = 'facebook') {

	$sb = TST_SocialButtons::get_instance();
	return $sb->get_social_button_link($key);
}

function tst_get_social_button($key = 'facebook') {

	$sb = TST_SocialButtons::get_instance();
	return $sb->get_social_button($key);
}

function tst_get_social_buttons_list($keys = array(), $show_feed = false) {

	$sb = TST_SocialButtons::get_instance();
	return $sb->get_social_buttons_list($keys, $show_feed);
}


/** == Sharing == **/

/** Sharing on mobile and small screens **/
function tst_social_share(WP_Post $cpost) {

	$title = get_the_title($cpost);
	$link = get_permalink($cpost);
	$text = $title.' '.$link;

	$data = array(
		'whatsapp' => array(
			'url' => 'whatsapp://send?text='.$text,
			'icon' => 'icon-whatsapp',
			'show_desktop' => false
		),
		'viber' => array(
			'url' => 'viber://forward?text='.$text,
			'icon' => 'icon-viber'
		),
		'telegram' => array(
			'url' => 'tg://msg?text='.$text,
			'icon' => 'icon-telegram'
		),
		'facebook' => array(
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$link,
			'icon' => 'icon-facebook'
		),
		'twitter' => array(
			'url' => 'https://twitter.com/intent/tweet?url='.$link.'&text='.$title,
			'icon' => 'icon-twitter'
		),
		'vk' => array(
			'url' => 'https://vk.com/share.php?url='.$link.'&title='.$title,
			'icon' => 'icon-vk'
		),
		'ok' => array(
			'url' => 'http://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
			'icon' => 'icon-ok'
		),
		'mail' => array(
			'url' => "mailto:?subject=Хорошая статья&body=$title $link",
			'icon' => 'icon-mail'
		)
	);


	$css = (tst_is_mobile_user_agent()) ? 'mobile-agent' : 'regular';
?>
	<ul class="sharing-list <?php echo esc_attr($css);?>">
	<?php foreach ($data as $id => $item){ ?>
		<li class="<?php echo $id;?>"><a href="<?php echo $item['url'];?>" target="_blank"><?php tst_svg_icon($item['icon']);?></a></li>
	<?php } ?>
	</ul>
<?php
}


function tst_is_mobile_user_agent(){
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

	return $test;
}


function tst_subscribe_form(){

	echo do_shortcode('[formidable key=subscribe]');
}