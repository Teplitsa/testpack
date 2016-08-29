<?php
/*
Widget Name: [TST] Всплывающая форма
Description: Кнопка и форма во всплывающем окне
*/

class TST_FormPopup_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-formpopup',
			'[TST] Всплывающая форма',
			array(
				'description' => 'Кнопка и форма во всплывающем окне'				
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
			'button_label' => array(
				'type' => 'text',
				'label' => 'Текст на кнопке',
			),
			
			'form_id' => array(
				'type' => 'number',
				'label' => 'ID формы',				
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'button_label' 	=> $instance['button_label'],
			'form_id' 		=> (int)$instance['form_id']	
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
			
		//template variables
		$template_vars = $this->get_template_variables($instance, $args);
		extract( $template_vars );
		
		$css_name = $this->generate_and_enqueue_instance_styles( $instance );
		
		
		echo $args['before_widget'];
		echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		
		$instance_id = uniqid('tst-formpopup-');
		
		wp_enqueue_script('leyka-modal');
	?>	
	<div id="<?php echo esc_attr($instance_id);?>" class="tst-formpopup-block">	
		<div class="tst-formpopup-trigger"><?php echo apply_filters('tst_the_title', $button_label); ?></div>
		<div class="tst-formpopup-modal">
			<div class="tst-fpm-close"><?php tst_svg_icon('icon-close');?></div>
			<div class="tst-fpm-frame">
				<div class="tst-fpm-flow"><?php echo do_shortcode("[formidable id={$form_id}]");?></div>
			</div>
		</div>
	</div>
	<?php	
		echo '</div>';
		echo $args['after_widget'];
	}
	
} //class

//register
siteorigin_widget_register('tst-formpopup', __FILE__, 'TST_FormPopup_Widget');

