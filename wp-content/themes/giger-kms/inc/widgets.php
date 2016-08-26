<?php
/**
 * Widgets
 **/

global $wp_embed;
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'widget_text', 'do_shortcode');

add_action('widgets_init', 'tst_custom_widgets', 20);
function tst_custom_widgets(){

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
	
	
	register_widget('TST_Home_News');
	register_widget('TST_Social_Links');
	
}

/* Remove some unused PB widget **/
add_filter( 'siteorigin_panels_widgets', 'tst_bp_panels_widgets', 11);
function tst_bp_panels_widgets( $widgets ){
	
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
function tst_pb_widgets_collection($folders){
    $folders[] = get_template_directory().'/inc/pb-widgets/';
	
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'tst_pb_widgets_collection');


/** Test if widget registered **/
function tst_is_widget_registered($widget_class){
	global $wp_widget_factory;
	
	if(!isset($wp_widget_factory->widgets[$widget_class]))
		return false;
	
	if(!($wp_widget_factory->widgets[$widget_class] instanceof WP_Widget))
		return false;
	
	return true;
}

/** Social Links Widget **/
class TST_Social_Links extends SiteOrigin_Widget {
		
    function __construct() {
        WP_Widget::__construct('widget_socila_links', '[TST] Социальные кнопки', array(
            'classname' => 'widget_socila_links',
            'description' => 'Меню социальных кнопок',
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
		
        echo $before_widget;
       
		echo tst_get_social_menu();
				
		echo $after_widget;
    }
	
	
	
    function form($instance) {
	?>
        <p><?php _e('Widget doesn\'t have any settings', 'tst');?></p>
    <?php
    }

    
    function update($new_instance, $old_instance) {
        $instance = $new_instance;
		
		
        return $instance;
    }
	
} // class end

/** Home news **/
class TST_Home_News extends WP_Widget {
		
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
		
		$all_link = "<span class='linked'><a href='".home_url('news')."'>".__('All news', 'tst')."</a>&nbsp;&gt;</span>";		
		
		//intro post
		$intro = array_slice($show_posts, 0, 1); // Number one as intro
		$intro = reset($intro);
		
		array_splice($show_posts, 0, 1);
				
		$intro_link = get_permalink($intro);
		
        echo $before_widget;
		
		$src = tst_post_thumbnail_src($intro->ID, 'post-thumbnail');
		$src = ($src) ? ' style="background-image: url('.$src.')"' : '';
		?>       
		<div class="news-block">
			<div class="frame">
				<div class="bit md-4">
					<a href="<?php echo $intro_link; ?>" class="thumbnail-link entry-preview">
					<div class="tpl-pictured-bg" <?php echo $src;?>></div>
					<div class='vvc-logo'><?php tst_svg_icon('pic-vvc');?></div>
					</a>
				</div>
				<div class="bit md-8">
					<?php tst_intro_post_card($intro) ;?>
					<?php if(!empty($show_posts)) { ?>
					<div class="news-block-list">
					<?php 
						foreach($show_posts as $p) {
							tst_news_title_card($p);
						}
					?>
					</div>
					<?php }?>
					<div class="all-link"><?php echo $all_link;?></div>
				</div>
			</div>
		</div>
		
		<?php		
		echo $after_widget;
    }
	
	protected function _get_posts($instance){
		
		if(!is_front_page())
			return;
		
		$exclude = explode(',', trim($instance['exclude_ids']));
		if(!empty($exclude))
			$exclude = array_map('intval', $exclude);
			

		$news_per_page = 4; //make this options ?
		$news = new WP_Query(array('post_type' => array('post'), 'posts_per_page' => $news_per_page, 'post__not_in' => $exclude));
		
		return $news->posts;
	}
	
    function form($instance) {
		
		$instance['exclude_ids'] = isset($instance['exclude_ids']) && $instance['exclude_ids'] ? explode(',', $instance['exclude_ids']) : '';
	?>
        <p>
            <label for="<?php echo $this->get_field_id('exclude_ids');?>">ID записей, которые нужно исключить (через запятую):</label>
            <input type="text" id="<?php echo $this->get_field_id('exclude_ids');?>" name="<?php echo $this->get_field_name('exclude_ids');?>" value="<?php echo $instance['exclude_ids'] ? implode(',', $instance['exclude_ids']) : '';?>" placeholder="">
        </p>
    <?php
    }

    
    function update($new_instance, $old_instance) {
       
		$instance = $old_instance;
		$instance['exclude_ids'] = sanitize_text_field($new_instance['exclude_ids']);
				
        return $instance;
    }
	
} // class end
