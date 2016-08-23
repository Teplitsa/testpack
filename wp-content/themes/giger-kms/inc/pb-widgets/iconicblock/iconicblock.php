<?php
/*
Widget Name: [TST] Блок c иконкой и текстом
Description: Вывод блока с иконкой, текстом, ссылкой
*/

class TST_IconicBlock_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-iconicblock',
			'[TST] Блок c иконкой и текстом',
			array(
				'description' => 'Вывод блока с иконкой, текстом, ссылкой'				
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
				'label' => 'Заголовок',
			),
			
			'content' => array(
				'type' => 'textarea',
				'label' => 'Текст',
				'rows' => 4
			),
			
			'block_url' => array(
				'type' => 'link',
				'label' => 'Ссылка блока',
				'description' => 'Оставьте пустым, если блок является простым текстом'
			),
			
			'icon' => array(
				'type' => 'select',
				'label' => 'Выберите иконку',
				'default' => 'pic-letter',
				'options' => array(
					'pic-donations' 	=> 'Пожертвования',
					'pic-letter' 		=> 'Письмо',
					'pic-letter-pro' 	=> 'Письмо PRO',
					'pic-petition' 		=> 'Петиция',
					'pic-volunteer' 	=> 'Волонтеры',
					'pic-online' 		=> 'Онлайн помощь',
					'pic-offline' 		=> 'Оффлайн помощь',
					'pic-butterfly' 	=> 'Знак на главной',
				)
			),
			
			'title_style' => array(
				'type' => 'radio',
				'label' => 'Стиль заголовка',
				'default' => 'regular',
				'options' => array(
					'regular' => 'Регулярный (черный)',
					'compact' => 'Компактный (красный)',
					'section' => 'Секция (белый)'
				)
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		return array(
			'title'			=> $instance['title'],
			'content'		=> $instance['content'],
			'block_url'		=> esc_url($instance['block_url']),
			'icon'			=> sanitize_text_field($instance['icon']),
			'title_style'	=> sanitize_text_field($instance['title_style'])
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
		
		$link_target = (!empty($block_url) && false === strpos($block_url, untrailingslashit(home_url()))) ? ' target="_blank"' : '';
		
			echo $args['before_widget'];
			echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
		?>
			<div class="iconic-block">
			<?php if(!empty($block_url)) { echo "<a href='{$block_url}' class='ib-url'{$link_target}>"; } ?>
				<div class="ib-icon"><?php tst_svg_icon($icon);?></div>
				<div class="ib-title <?php echo esc_attr($title_style);?>"><?php echo apply_filters('tst_the_title', $title);?></div>
				<div class="ib-content"><?php echo apply_filters('tst_the_content', $content);?></div>
			<?php if(!empty($block_url)) { echo "</a>"; } ?>
			</div>
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
} //class

//register
siteorigin_widget_register('tst-iconicblock', __FILE__, 'TST_IconicBlock_Widget');

