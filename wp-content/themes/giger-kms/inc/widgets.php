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


}


/** Test if widget registered **/
function tst_is_widget_registered($widget_class){
	global $wp_widget_factory;

	if(!isset($wp_widget_factory->widgets[$widget_class]))
		return false;

	if(!($wp_widget_factory->widgets[$widget_class] instanceof WP_Widget))
		return false;

	return true;
}