<?php
/*
Widget Name: [TST] Заголовок
Description: Заголовок секций на странице
*/

class IST_PB_SectionHeader_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'ist-sectionheader',
			'[TST] Заголовок',
			array(
				'description' => 'Заголовок секций на странице'				
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
				'label' => __('Title text', 'rdc'),
			),
			
			'subtitle' => array(
				'type' => 'text',
				'label' => __('Sub title text', 'rdc'),				
			),
			
			'align' => array(
				'type' => 'select',
				'label' => __( 'Align', 'rdc' ),
				'default' => 'left',
				'options' => array(
					'center' => __( 'Center', 'rdc' ),
					'left'   => __( 'Left', 'rdc' ),
					'right'  => __( 'Right', 'rdc' )					
				)
			),
			'page_header' => array(
				'type' => 'checkbox',
				'label' => __( 'Style as page title', 'rdc' ),
				'default' => false
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'title'    => $instance['title'],
			'subtitle' => $instance['subtitle'],			
			'align'    => $instance['align']			
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
		$css_title = 'align-'.$instance['align'];
		if((bool)$instance['page_header'])
			$css_title .= ' page-header';
		
		
		echo $args['before_widget'];
		echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
	?>	
	<div class="pb-section-title <?php echo esc_attr($css_title); ?>">	
		<h3><?php echo apply_filters('rdc_the_title', $title);?></h3>
		<?php if($subtitle) { ?>
			<h4><?php echo apply_filters('rdc_the_title', $subtitle); ?></h4>
		<?php } ?>		
	</div>
	<?php	
		echo '</div>';
		echo $args['after_widget'];
	}
	
} //class

//register
siteorigin_widget_register('ist-sectionheader', __FILE__, 'IST_PB_SectionHeader_Widget');

