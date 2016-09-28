<?php
/**
 * Widgets
 **/

 
add_action('widgets_init', 'rdc_custom_widgets', 20);
function rdc_custom_widgets(){

	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Search');
	unregister_widget('FrmListEntries');
	//unregister_widget('FrmShowForm');
	
	//Most of widgets do not perform well with MDL as for now
	unregister_widget('Leyka_Donations_List_Widget');
	unregister_widget('Leyka_Campaign_Card_Widget');
	unregister_widget('Leyka_Campaigns_List_Widget');
	
	//Some siteOrign stranges
	unregister_widget('SiteOrigin_Widget_Features_Widget');
	unregister_widget('SiteOrigin_Widget_PostCarousel_Widget');
	unregister_widget('SiteOrigin_Widget_Button_Widget');
	unregister_widget('SiteOrigin_Panels_Widgets_PostLoop');
	
	
	register_widget('RDC_Home_News');
	register_widget('RDC_Social_Links');
	
}

/* Remove some unused PB widget **/
add_filter( 'siteorigin_panels_widgets', 'rdc_bp_panels_widgets', 11);
function rdc_bp_panels_widgets( $widgets ){
	
	if(isset($widgets['SiteOrigin_Widget_Features_Widget']))
		unset($widgets['SiteOrigin_Widget_Features_Widget']);
		
	if(isset($widgets['SiteOrigin_Widget_PostCarousel_Widget']))
		unset($widgets['SiteOrigin_Widget_PostCarousel_Widget']);
		
	if(isset($widgets['SiteOrigin_Widget_Button_Widget']))
		unset($widgets['SiteOrigin_Widget_Button_Widget']);
		
	if(isset($widgets['SiteOrigin_Panels_Widgets_PostLoop']))
		unset($widgets['SiteOrigin_Panels_Widgets_PostLoop']);
	
	//var_dump($widgets);
	
	return $widgets;
}

/* PB Custom widget folder */
function rdc_pb_widgets_collection($folders){
    $folders[] = get_template_directory().'/inc/pb-widgets/';
	
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'rdc_pb_widgets_collection');


/** Test if widget registered **/
function rdc_is_widget_registered($widget_class){
	global $wp_widget_factory;
	
	if(!isset($wp_widget_factory->widgets[$widget_class]))
		return false;
	
	if(!($wp_widget_factory->widgets[$widget_class] instanceof WP_Widget))
		return false;
	
	return true;
}

/** Social Links Widget **/
class RDC_Social_Links extends SiteOrigin_Widget {
		
    function __construct() {
        WP_Widget::__construct('widget_socila_links', '[TST] Социальные кнопки', array(
            'classname' => 'widget_socila_links',
            'description' => 'Меню социальных кнопок',
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
		
        echo $before_widget;
       
		echo rdc_get_social_menu();
				
		echo $after_widget;
    }
	
	
	
    function form($instance, $form_type = 'widget') {
	?>
        <p><?php _e('Widget doesn\'t have any settings', 'rdc');?></p>
    <?php
    }

    
    function update($new_instance, $old_instance, $form_type = 'widget') {
        $instance = $new_instance;
		
		
        return $instance;
    }
	
} // class end

/** Home news **/
class RDC_Home_News extends WP_Widget {
		
    function __construct() {
        WP_Widget::__construct('widget_home_news', '[TST] Новости на главной', array(
            'classname' => 'widget_home_news',
            'description' => 'Секция новостей на главной странице',
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
		
		if(!is_front_page())
			return;
		
		$show_posts = $this->_get_posts($instance);
		if(empty($show_posts))
			return;
		
		$all_link = "<a href='".home_url('news')."'>".__('More news', 'rdc')."&nbsp;&rarr;</a>";		
		
        echo $before_widget;
		?>       
		<div class="related-cards-loop">
			<?php
				foreach($show_posts as $p){			
					rdc_related_post_card($p);
				}		
			?>
		</div>
		<!--<div class="related-all-link"><?php echo $all_link;?></div>-->
		<?php		
		echo $after_widget;
    }
	
	protected function _get_posts($instance){
		
		if(!is_front_page())
			return;
		
		$exclude = explode(',', trim($instance['exclude_ids']));
		if(!empty($exclude))
			$exclude = array_map('intval', $exclude);
			
		$events = new WP_Query(array(
				'post_type' => array('event'),
				'posts_per_page' => 1,
				'orderby'  => array('menu_order' => 'DESC', 'meta_value' => 'ASC'),					
				'meta_key' => 'event_date_start',
				'meta_query' => array(					
					array(
						'key' => 'event_date_end',
						'value' => strtotime('today midnight'),
						'compare' => '>=',
						'type' => 'numeric'
					)
				),
				'post__not_in' => $exclude));

		$news_per_page = ($events->have_posts()) ? 4 : 5;
		$news = new WP_Query(array('post_type' => array('post'), 'posts_per_page' => $news_per_page, 'post__not_in' => $exclude));
		
		return array_merge($events->posts, $news->posts);
	}
	
    function form($instance, $form_type = 'widget') {
		
		$instance['exclude_ids'] = $instance['exclude_ids'] ? explode(',', $instance['exclude_ids']) : '';
	?>
        <p>
            <label for="<?php echo $this->get_field_id('exclude_ids');?>">ID записей, которые нужно исключить (через запятую):</label>
            <input type="text" id="<?php echo $this->get_field_id('exclude_ids');?>" name="<?php echo $this->get_field_name('exclude_ids');?>" value="<?php echo $instance['exclude_ids'] ? implode(',', $instance['exclude_ids']) : '';?>" placeholder="">
        </p>
    <?php
    }

    
    function update($new_instance, $old_instance, $form_type = 'widget') {
       
		$instance = $old_instance;
		$instance['exclude_ids'] = sanitize_text_field($new_instance['exclude_ids']);
				
        return $instance;
    }
	
} // class end
