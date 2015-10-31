<?php
/**
 * Widgets
 **/

 
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
	unregister_widget('FrmShowForm');
	
	//Most of widgets do not perform well with MDL as for now
	unregister_widget('Leyka_Donations_List_Widget');
	unregister_widget('Leyka_Campaign_Card_Widget');
	unregister_widget('Leyka_Campaigns_List_Widget');
	unregister_widget('Su_Widget');
		
	register_widget('TST_Social_Links');
	//register_widget('TST_Related_Widget');
	
}


/** Social Links Widget **/
class TST_Social_Links extends WP_Widget {
		
    function __construct() {
        WP_Widget::__construct('widget_socila_links', __('Social Buttons', 'tst'), array(
            'classname' => 'widget_socila_links',
            'description' => __('Social links menu with optional text', 'tst'),
        ));
    }

    
    function widget($args, $instance) {
        extract($args); 
		
        echo $before_widget;
       
		tst_get_social_menu();
				
		echo $after_widget;
    }
	
	
	
    function form($instance) {
	?>
        <p>Виджет не имеет настроек</p>
    <?php
    }

    
    function update($new_instance, $old_instance) {
        $instance = $new_instance;
		
		
        return $instance;
    }
	
} //class end