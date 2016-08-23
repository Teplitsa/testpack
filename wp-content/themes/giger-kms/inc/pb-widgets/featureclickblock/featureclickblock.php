<?php
/*
Widget Name: [TST] 3 Кликабельных блока-опции
Description: 3 Стандартизированных кликабельных блока с заголовком текстом и кнопкой
*/

class TST_FeatureClickBlock_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-featureclickblock',
			'[TST] 3 Кликабельных блока',
			array(
				'description' => 'Ряд из 3 кликабельных блоков с заголовком, и текстом, кнопкой, выровненных по высоте'
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
	
		$fields = array(
			'title' => array(
				'type' => 'text',
				'label' => __('Title', 'tst')
			),
			'text' => array(
				'type' => 'textarea',
				'label' => __('Description', 'tst'),
				'rows' => 2
			),
			'button_url' => array(
				'type' => 'link',
				'label' => __('Link', 'tst'),
				'default' => ''
			),
			'button_label' => array(
				'type' => 'text',
				'label' => __('Button label', 'tst')				
			),
		    'is_block_clickable' => array(
		        'type' => 'checkbox',
		        'label' => __('Whole block is clickable', 'tst'),
		        'default' => false
		    )
		);
	
		return array(
			'col1' => array(
				'type' => 'section',
				'label' => __('Column 1', 'tst'),
				'hide' => false,
				'fields' => $fields
			),
			'col2' => array(
				'type' => 'section',
				'label' => __('Column 2', 'tst'),
				'hide' => true,
				'fields' => $fields
			),
			'col3' => array(
				'type' => 'section',
				'label' => __('Column 3', 'tst'),
				'hide' => true,
				'fields' => $fields
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		
		$sections = array('col1', 'col2', 'col3');
		$fields = array('title', 'text', 'button_url', 'button_label', 'is_block_clickable');
		$results = array();
		
		foreach($sections as $sec){
			foreach($fields as $field){
				$key = $sec.'_'.$field;
				$results[$key] = (isset($instance[$sec][$field])) ? $instance[$sec][$field] : '';
			}
		}
		
		return $results;
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
		
		
		$css_name = $this->generate_and_enqueue_instance_styles( $instance );
			
			echo $args['before_widget'];
			echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
			echo $this->feature_screen($template_vars);
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
	public function feature_screen($template_vars){
		
		$sections = array('col1', 'col2', 'col3');
				
		$out = "<div class='col3-section'>";
		
		foreach($sections as $sec){
		    if(!empty($template_vars[$sec.'_button_url']) && $template_vars[$sec.'_is_block_clickable']) {
		        $wrapper_tag_open = "<a href='".$template_vars[$sec.'_button_url']."' title='".$template_vars[$sec.'_button_label']."' class='col3'>";
		        $wrapper_tag_close = "</a>";
		    }
		    else {
		        $wrapper_tag_open = "<div class='col3'>";
		        $wrapper_tag_close = "</div>";
		    }
		    
			$out .= $wrapper_tag_open;
			$out .= "<div class='col3-content'>";
			
			if(!empty($template_vars[$sec.'_title'])){
				$out .= "<h4 class='col3-title'>".apply_filters('tst_the_title', $template_vars[$sec.'_title'])."</h4>";
			}
			if(!empty($template_vars[$sec.'_text'])){
				$out .= "<div class='col3-text'>".apply_filters('tst_the_content', $template_vars[$sec.'_text'])."</div>";
			}
			if(!empty($template_vars[$sec.'_button_url']) && !empty($template_vars[$sec.'_button_label'])){
				$target = (false !== strpos($template_vars[$sec.'_button_url'], home_url())) ? '' : ' target="_blank"'; //test this
				$out .= "<div class='col3-link'><a href='".$template_vars[$sec.'_button_url']."'{$target}>".$template_vars[$sec.'_button_label']."</a></div>";
			}
		
			$out .= "</div>";
	        $out .= $wrapper_tag_close;
		}
		
		$out .= "</div>";
		
		return $out;
	}
	
} //class

//register
siteorigin_widget_register('tst-featureclickblock', __FILE__, 'TST_FeatureClickBlock_Widget');

