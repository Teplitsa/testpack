<?php
/*
 Widget Name: [TST] Карусель цитат
 Description: Карусель цитат
 */

class RDC_QuoteSlider extends WP_Widget {

    function __construct() {
        WP_Widget::__construct('widget_home_news', '[TST] Карусель цитат', array(
        'classname' => 'widget_home_news',
        'description' => 'Карусель цитат на главной странице',
        ));
    }


    function widget($args, $instance) {
        extract($args);

        if(!is_front_page())
            return;

        $show_posts = $this->_get_posts($instance);
        if(empty($show_posts))
            return;

        echo $before_widget;
		foreach($show_posts as $p){
	        tst_quote_card($p);
	        break;
		}
		echo $after_widget;
    }
	
	protected function _get_posts($instance){
		
		if(!is_front_page())
			return;
		
		$exclude = explode(',', trim($instance['exclude_ids']));
		if(!empty($exclude))
			$exclude = array_map('intval', $exclude);
			
		$news_per_page = 5;
		$news = new WP_Query(array('post_type' => array('quote'), 'posts_per_page' => $news_per_page, 'post__not_in' => $exclude));
		
		return array_merge($news->posts);
	}
	
    function form($instance) {
		$instance['exclude_ids'] = $instance['exclude_ids'] ? explode(',', $instance['exclude_ids']) : '';
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

siteorigin_widget_register('tst-quoteslider', __FILE__, 'RDC_QuoteSlider');