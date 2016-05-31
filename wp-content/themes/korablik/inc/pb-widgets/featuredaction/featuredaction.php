<?php
/*
Widget Name: [TST] Элемент-заставка
Description: Стартовая заставка с кнопкой ведет на выбранный элемент
*/

class TST_FeaturedItem_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-featureditem',
			'[TST] Элемент-заставка',
			array(
				'description' => 'Стартовая заставка с кнопкой ведет на выбранный элемент'		
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
			'post_id' => array(
				'type' => 'text',
				'label' => 'ID элемента',
				'description' => 'Может быть запись, проект, страница, мероприятие'
			),
			'button_label' => array(
				'type' => 'text',
				'label' => 'Текст на кнопке',
				'description' => 'По умолчанию - просмотреть'
			),
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'post_id' 	=> (int)($instance['post_id']),
			'button_label' 	=> $instance['button_label']			
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
		
		$post = get_post($instance['post_id']);
        
        if($post) {		
			echo $args['before_widget'];
			echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		?>
			<div class="featured-action">
			<?php krbl_featured_action_card($post, $instance['button_label']);	?>
			</div>
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		}
	}
	
} //class

//register
siteorigin_widget_register('tst-featureditem', __FILE__, 'TST_FeaturedItem_Widget');

