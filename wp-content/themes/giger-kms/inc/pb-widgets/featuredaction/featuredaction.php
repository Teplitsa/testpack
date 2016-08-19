<?php
/*
Widget Name: [TST] Заставка
Description: Стартовая заставка с изображением, текстом и иконкой
*/

class TST_FeaturedItem_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-featureditem',
			'[TST] Заставка',
			array(
				'description' => ' Стартовая заставка с изображением, текстом и иконкой'		
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
				'label' => 'Фоновое изображение',
				'library' => 'image',
				'fallback' => true,
			),		

			'title' => array(
				'type' => 'text',
				'label' => 'Заголовок',
			),
			
			'subtitle' => array(
				'type' => 'textarea',
				'label' => 'Аннотация',
				'rows' => 4
			),
			
			'icon' => array(
				'type' => 'text',
				'label' => 'Класс иконки',
				'description' => 'Скопируйте класс иконки <a href="https://developer.wordpress.org/resource/dashicons/">на справочной странице</a>',
			),
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(			
			'title'		=> $instance['title'],
			'subtitle'	=> $instance['subtitle'],
			'icon'		=> $instance['icon'],
			'image'		=> (int)$instance['image']			
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
				
		$this->_print_markup($title, $subtitle, $icon, $image);
		
		echo '</div>';
		echo $args['after_widget'];
		
	}
	
	protected function _print_markup($title, $subtitle, $icon, $image){
	
	?>
	<div class="featured-section" style="background-image: url(<?php echo wp_get_attachment_url($image);?>)">
		<div class="fs-content-wrap"><div class="fs-content">
			<div class="fs-icon"><div class="dashicons <?php echo esc_attr($icon);?>"></div></div>
			<h1 class="fs-title"><?php echo apply_filters('tst_the_title',  $title);?></h1>
			<div class="fs-desc"><?php echo apply_filters('tst_the_content',  $subtitle);?></div>
		</div></div>
	</div>
	<?php
	}
	
} //class

//register
siteorigin_widget_register('tst-featureditem', __FILE__, 'TST_FeaturedItem_Widget');

