<?php
/*
 Widget Name: [TST] Карусель цитат
 Description: Карусель цитат из определенной категории
 */

class TST_QuoteSlider_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-quoteslider',
			'[TST] Карусель цитат',
			array(
				'description' => 'Карусель цитат из определенной категории'				
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
			'exclude_ids' => array(
				'type' => 'text',
				'label' => 'ID записей - исключить',
				'description' => 'Список IDs, разделенных запятыми',
			),						
			'tax_terms' => array(
				'type' => 'text',
				'label' => 'Slug категорий цитат (через запятую)'
			),			
			'posts_per_page' => array(
				'type' => 'text',
				'label' => 'Количество (по умолчанию - все)'
			)	
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'exclude_ids' 	=> sanitize_text_field($instance['exclude_ids']),			
			'tax_terms'  	=> sanitize_text_field($instance['tax_terms']),			
			'posts_per_page'=> (int)$instance['posts_per_page']
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
		
		$params = array(
            'post_type' => 'quote',
            'post__not_in' => $instance['exclude_ids'] ? explode(',', $instance['exclude_ids']) : array(),            
            'posts_per_page' => ($instance['posts_per_page'] && (int)$instance['posts_per_page'] > 0) ? (int)$instance['posts_per_page'] : -1
        );
		
        if($instance['tax_terms']) {
            $params['tax_query'] = array(
                array(
                    'taxonomy' => 'quote_cat',
                    'field'    => 'slug',
                    'terms'    => explode(',', $instance['tax_terms']),
                    'operator' => 'IN',
                ),
            );
        }

        $quotes = get_posts($params);
		
		if(empty($quotes))
			return; 
			
			echo $args['before_widget'];
			echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		?>
			<div class="tst-quote-slider"><div class="quote-slider">
			<?php foreach($quotes as $quote) {?>
				<div class="quote-slide"><?php tst_quote_card($quote); ?></div>
			<?php }?>
			</div>			
			</div>
		
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
} //class

//register
siteorigin_widget_register('tst-quoteslider', __FILE__, 'TST_QuoteSlider_Widget');
