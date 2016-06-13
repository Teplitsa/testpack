<?php
/*
Widget Name: [TST] Заставка страницы
Description: Заставка для страниц с изображением и заголовком
*/

class IST_PB_PageHeader_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'ist-pageheader',
			'[TST] Заставка страницы',
			array(
				'description' => 'Заставка для страниц с изображением и заголовком'				
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
			'image' => array(
				'type' => 'media',
				'label' => __('Image file', 'rdc'),
				'library' => 'image',
				'fallback' => true,
			),		

			'title' => array(
				'type' => 'text',
				'label' => __('Title text', 'rdc'),
			),
			
			'subtitle' => array(
				'type' => 'textarea',
				'label' => __('Sub title text', 'rdc'),
				'rows' => 4
			),
			
			'extend_width' => array(
				'type' => 'checkbox',
				'default' => false,
				'label' => __('Extend Width', 'rdc'),
				'description' => 'Макет шире основной колонки',
			),
			
						
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'title'    => $instance['title'],
			'subtitle' => $instance['subtitle'],			
			'image'    => (int)$instance['image'],			
			'extend_width' => $instance['extend_width'],
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
		echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		
		//card
		rdc_intro_card_markup($title, $subtitle, $image, '', '', true, $extend_width);
	
		echo '</div>';
		echo $args['after_widget'];
	}
	
} //class

//register
siteorigin_widget_register('ist-pageheader', __FILE__, 'IST_PB_PageHeader_Widget');

