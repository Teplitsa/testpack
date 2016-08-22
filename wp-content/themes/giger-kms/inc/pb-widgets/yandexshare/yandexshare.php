<?php
/*
Widget Name: [TST] YandexShare
Description: Кнопки щаринга
*/

class TST_YandexShare_Widget extends SiteOrigin_Widget {
    
    public static function show_yandex_share() {
        if(function_exists ( 'tst_show_yandex_share' )) {
            tst_show_yandex_share();
        }
        else {
            echo "Implement tst_show_yandex_share!";
        }
    }
	
	function __construct() {
		
		parent::__construct(
			'tst-yandexshare',
			'[TST] YandexShare',
			array(
				'description' => ' Кнопки шаринга'
			),
			array(
			),
			false,
			plugin_dir_path(__FILE__)
		);
			
	}
	
	/* abstract method - we not going to use them */
	function get_template_name($instance) {
		return '';
	}
	
	function get_style_name($instance) {
		return '';
	}
	
	/** admin form **/
	function initialize_form(){

		return array(
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(			
		);
	}
	
	public function widget( $args, $instance ) {
	
		if( empty( $this->form_options ) ) {
			$this->form_options = $this->initialize_form();
		}

		$instance = $this->modify_instance($instance);

		// Filter the instance
		$instance = apply_filters( 'siteorigin_widgets_instance', $instance, $this );
		$instance = apply_filters( 'siteorigin_widgets_instance_' . $this->id_base, $instance, $this );

		$args = wp_parse_args( $args, array(
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		) );

		// Add any missing default values to the instance
		$instance = $this->add_defaults( $this->form_options, $instance );
		$template_vars = $this->get_template_variables($instance, $args);
		extract( $template_vars );
				
		$css_name = $this->generate_and_enqueue_instance_styles( $instance );
		
		echo $args['before_widget'];
		self::show_yandex_share();
		echo $args['after_widget'];
		
	}
	
} //class

//register
siteorigin_widget_register('tst-yandexshare', __FILE__, 'TST_YandexShare_Widget');

