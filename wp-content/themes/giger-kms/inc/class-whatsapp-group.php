<?php

class TST_WhatsappGroup {
    
    private static $instance = NULL;
    private $filename = 'emails-VR4pD3sAYvf5r8GHE4bM6WbA.csv';
    
    private function __construct() {
        add_action( "wp_ajax_tst_join_whatsapp_group", array( $this, "join" ) );
        add_action( "wp_ajax_nopriv_tst_join_whatsapp_group", array( $this, "join" ) );
        add_shortcode( 'tst-join-whatsapp-group', array( $this, 'show_join_form' ) );
    }
    
    public static function get_instance(){
        if (NULL === self :: $instance) {
            self :: $instance = new self;
        }
        return self :: $instance;
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
            //    return true;
            return $test;
    }
    
    /** Store data in DB **/
    public function store_phone($phone) {
        
        $uploads_dir = wp_upload_dir();
        $uploads_dir = $uploads_dir['basedir'];
    
        $file = $uploads_dir . '/tst_data/'.$this->filename;
    
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
    
        if(empty($to_email)) {
            $to_email = get_bloginfo('admin_email');
        }
    
        //send email
        $headers = array();
        $from_name = get_bloginfo('name');

        //$from_email = get_bloginfo('admin_email');
        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
        if ( substr( $sitename, 0, 4 ) == 'www.' ) {
            $sitename = substr( $sitename, 4 );
        }

        $from_email = 'wordpress@'.$sitename;

        if(!empty($from_email)) {
            $headers[] = "From: {$from_name} <{$from_email}>";
        }

        $subject = 'Запрос на добавление в группу через сайт';
        $txt = apply_filters('the_content', '<p>Поступил запрос на добалвение в группу через сайт. Номер телефона: '.$phone.'</p>');
        $txt .= apply_filters('the_content', '<p>Дата и время запроса: '.date('d.m.Y H:i', strtotime('now')).'</p>');

        add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

        if(!wp_mail($to_email, wp_specialchars_decode($subject), $txt, $headers)) {
            return new WP_Error('no_email', 'Email notification was not send');
        }


        return true;
    }
    
    public function show_join_form() {
?>
    <div class="card__action">
    <?php
        if(!$this->is_mobile_user_agent()) {
    ?>
    <div class="form-area">
        <div id="form-response"></div>
        <form class="tstl-join-group" action="#" method="post">
            <p>Оставьте номер вашего мобильного телефона, мы добавим вас в группу</p>
            <div class="field">
                <input type="text" name="tstl_phone" id="tstl_phone" value="">
                <?php wp_nonce_field('tstl_join_group', '_tstl_nonce'); ?>
    
                <div class="field__error">Укажите, корректный номер телефона</div>
            </div>
            <div class="submit">
                <button type="submit" class="button card__button">
                    <span><svg class="svg-icon icon-whatsapp"><use xlink:href="#icon-whatsapp" /></svg>
                    Вступить в группу</span>
                </button>
            </div>
        </form>
    
        <noscript><p>Включите поддержку JS в браузере или зайдите с мобильного телефона, чтобы присоединиться к группе</p></noscript>
    </div>
    <?php
        }
        else {
            $url = $this->button_url();
    ?>
    <a href="<?php echo $url;?>" target="_blank" class="card__button">
        <span><svg class="svg-icon icon-whatsapp"><use xlink:href="#icon-whatsapp" /></svg>
        Вступить в группу</span>
    </a>
    <?php
        }
    ?>
    
    </div>
<?php
    }
    
    public function join() {
        
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
    
            $store = $this->store_phone($phone);
    
            if(!is_wp_error($store)){
                $store = $this->notify($phone);
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
    
}

$jwg = TST_WhatsappGroup::get_instance();
