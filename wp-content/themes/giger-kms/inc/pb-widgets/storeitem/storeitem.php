<?php
/*
Widget Name: [TST] Товар в магазине
Description: Вывод карточки товара
*/

class TST_StoreItem_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-storeitem',
			'[TST] Товар в магазине',
			array(
				'description' => 'Вывод карточки товара'				
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
			
			'description' => array(
				'type' => 'textarea',
				'label' => __('Description', 'rdc'),
				'rows' => 4
			),
			
			'price' => array(
				'type' => 'text',
				'label' => __('Price (in RUR)', 'rdc'),
			),
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'title'			=> $instance['title'],
			'description'	=> $instance['description'],			
			'image'			=> (int)$instance['image'],
			'price'			=> (int)$instance['price']
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
			
			$src = wp_get_attachment_image_src($image, 'post-thumbnail');
			$src = ($src) ? $src[0] : '';
		?>
			<article class="tpl-storeitem">
				<div class="entry-preview"><div class="tpl-pictured-bg" style="background-image: url(<?php echo $src;?>);" ></div></div>
				<div class="entry-data">
					<div class="entry-price"><?php printf('%s руб.', $price);?></div>
					<h4 class="entry-title"><?php echo apply_filters('rdc_the_title', $title);?></h4>
					<div class="entry-summary"><?php echo apply_filters('rdc_the_content', $description);?></div>		
				</div>
			</article>
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
} //class

//register
siteorigin_widget_register('tst-storeitem', __FILE__, 'TST_StoreItem_Widget');

