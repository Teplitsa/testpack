<?php
/*
Widget Name: [TST] Секция новостей на главной
Description: Вывод секции новостей на главной страницы
*/

class TST_Newsection_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-newsection',
			'[TST] Секция новостей на главной',
			array(
				'description' => 'Вывод секции новостей на главной страницы'				
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
			'posts_per_page' => array(
				'type' => 'text',
				'label' => 'Количество (по умолчанию - 6)'
			)					
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(			
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
            'post_type' => 'post',                     
            'posts_per_page' => ($instance['posts_per_page'] && (int)$instance['posts_per_page'] > 0) ? (int)$instance['posts_per_page'] : 6
        );
		       

        $posts = get_posts($params);
		$featured = array_slice($posts, 0, 2); 
		array_splice($posts, 0, 2);		

        if($featured && $posts) {		
			echo $args['before_widget'];
			echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		?>
		<div class="frame home-news-section">
			<div class="bit md-8 bit-no-margin">
				<div class="cards-loop sm-cols-2">
				<?php
					foreach($featured as $fp) {
						tst_post_card($fp);
					}
				?>
				</div>
			</div>
			<div class="bit md-4">
			<?php
				foreach($posts as $p) {
					tst_post_compact_card($p);
				}
			?>
			</div>
		</div>			
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		}
	}
	
} //class

//register
siteorigin_widget_register('tst-newsection', __FILE__, 'TST_Newsection_Widget');

