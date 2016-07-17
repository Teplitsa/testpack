<?php
/*
Widget Name: [TST] Двойной блок
Description: Двойной стандартизированный блок с изображениями
*/

class TST_DoubleBlock_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-doubleblock',
			'[TST] Двойной блок',
			array(
				'description' => 'Двойной стандартизированный блок с изображениями'
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
			'image' => array(
				'type' => 'media',
				'label' => __('Image file', 'tst'),
				'library' => 'image',
				'fallback' => true,
			),	
		);
	
		return array(
			'sec1' => array(
				'type' => 'section',
				'label' => __('Section 1', 'tst'),
				'hide' => false,
				'fields' => $fields
			),
			'sec2' => array(
				'type' => 'section',
				'label' => __('Section 2', 'tst'),
				'hide' => true,
				'fields' => $fields
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		
		$sections = array('sec1', 'sec2');
		$fields = array('title', 'text', 'button_url', 'button_label', 'image');
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
			echo $this->block_screen($template_vars);
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
	public function block_screen($template_vars){
		
		$sections = array('sec1', 'sec2');
				
		$out = "<div class='doubleblock-section'>";
		
		foreach($sections as $i => $sec){
			$out .= "<div class='dbs-row'>";
			
			$picture = '';
			$content = '';
			
			if(!empty($template_vars[$sec.'_image'])){
				$img = wp_get_attachment_image_src((int)$template_vars[$sec.'_image'], 'medium-thumbnail');
				if(isset($img[0]) && !empty($img[0])) {
					$picture .= "<div class='dbs-cell picture'>";
					
					if(!empty($template_vars[$sec.'_button_url'])){
						$target = (false !== strpos($template_vars[$sec.'_button_url'], home_url())) ? '' : ' target="_blank"'; //test this
						$picture .= "<a class='picture-url' href='".esc_url($template_vars[$sec.'_button_url'])."'{$target}>";
					}
						
					$picture .= "<div class='tpl-pictured-bg' style='background-image: url(".$img[0].");' ></div>";
					
					if(!empty($template_vars[$sec.'_button_url'])){
						$picture .= "</a>";
					}
					
					$picture .= "</div>";
				}
			}
			
			$content .= "<div class='dbs-cell content'>";
			
			if(!empty($template_vars[$sec.'_title'])){
				$content .= "<h4 class='dbs-title'>".apply_filters('tst_the_title', $template_vars[$sec.'_title'])."</h4>";
			}
			if(!empty($template_vars[$sec.'_text'])){
				$content .= "<div class='dbs-text'>".apply_filters('tst_the_content', $template_vars[$sec.'_text'])."</div>";
			}
			if(!empty($template_vars[$sec.'_button_url']) && !empty($template_vars[$sec.'_button_label'])){
				$target = (false !== strpos($template_vars[$sec.'_button_url'], home_url())) ? '' : ' target="_blank"'; //test this
				$content .= "<div class='dbs-link'><a href='".esc_url($template_vars[$sec.'_button_url'])."'{$target}>".$template_vars[$sec.'_button_label']."</a></div>";
			}
		
			$content .= "</div>";
			
			if($i == 0)
				$out .= $picture.$content;
			else
				$out .= $content.$picture;
				
			$out .= "</div>";
		}
		
		$out .= "</div>";
		
		return $out;
	}
	
} //class

//register
siteorigin_widget_register('tst-doubleblock', __FILE__, 'TST_DoubleBlock_Widget');

