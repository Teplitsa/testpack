<?php
/*
Widget Name: [TST] Ссылка
Description: Обычная ссылка
*/

class TST_ActionLink_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-actionlink',
			'[TST] Ссылка',
			array(
				'description' => ' Обычная ссылка'		
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
			'title' => array(
				'type' => 'text',
				'label' => 'Текст',
			),
			
			'url' => array(
				'type' => 'text',
				'label' => 'Ссылка',
				'rows' => 4
			),
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(			
			'title'		=> $instance['title'],
			'url'	=> $instance['url'],
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
		echo '<a class="link-'.$css_name.'" href="'.$url.'">' . $title . '</a>';
		echo $args['after_widget'];
		
	}
	
} //class

//register
siteorigin_widget_register('tst-actionlink', __FILE__, 'TST_ActionLink_Widget');

