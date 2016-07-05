<?php
/*
Widget Name: [TST] Цитата
Description: Цитата с указанием источника и фото (опционально)
*/

class TST_Quote_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-quote',
			'[TST] Цитата',
			array(
				'description' => 'Цитата с указанием источника и фото (опционально)'		
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
			'quote_text' => array(
				'type' => 'textarea',
				'label' => 'Цитата',
				'rows' => 6
			),
			
			'quote_source' => array(
				'type' => 'text',
				'label' => 'Источник',
			),
			
			'quote_source_desc' => array(
				'type' => 'text',
				'label' => 'Описание источника',
			),
			
			'quote_image' => array(
				'type' => 'media',
				'label' => 'Изображение',
				'library' => 'image',
				'fallback' => true,
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(			
			'quote_text'	=> $instance['quote_text'],
			'quote_source'	=> $instance['quote_source'],
			'quote_source_desc'	=> $instance['quote_source_desc'],		
			'quote_image'   => (int)$instance['quote_image']			
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
		?>	
			<div class="tst-quote">			
				<div class="tq-content">
					<?php echo apply_filters('tst_the_content', $quote_text);?>	
				</div>
				
				<div class="tq-source">
					<?php if($quote_image) {?>
					<div class="tq-image"><?php echo wp_get_attachment_image($quote_image, 'thumbnail');?></div>
				<?php } ?>
					<div class="tq-source-label">
						<cite><?php echo apply_filters('tst_the_title', $quote_source);?></cite>
						<i><?php echo apply_filters('tst_the_title', $quote_source_desc);?></i>
					</div>
				</div>
			</div>	
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
	
	
} //class

//register
siteorigin_widget_register('tst-quote', __FILE__, 'TST_Quote_Widget');

