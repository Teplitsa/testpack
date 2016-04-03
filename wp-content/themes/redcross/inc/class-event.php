<?php
/**
 * Event class
 * 
 **/

class TST_Event {
	
	public $post_object;
	public $type_taxonomy = 'event_type';
	
	private $filters = null;
	private $related_post_id = null;
	
	
	public function __construct($post) {
		
		if(is_object($post)){
			$this->post_object = $post;
		}
		elseif((int)$post > 0) {
			$this->post_object = get_post((int)$post);
		}		
	}
	
	public function __get( $key ) {
		
		if(property_exists($this->post_object , $key))
			return $this->post_object->$key;
		
		
		switch($key){
			case 'date_start':				
				return get_post_meta($this->post_object->ID, 'event_date_start', true);
				break;
			
			case 'date_end':
				return get_post_meta($this->post_object->ID, 'event_date_end', true);
				break;
			
			case 'time_start':
				return get_post_meta($this->post_object->ID, 'event_time_start', true);
				break;
			
			case 'time_end':
				return get_post_meta($this->post_object->ID, 'event_time_end', true);
				break;
			
			case 'location':
				return get_post_meta($this->post_object->ID, 'event_location', true);
				break;
			
			case 'address':
				return get_post_meta($this->post_object->ID, 'event_address', true);
				break;
			
			case 'participants':
				return get_post_meta($this->post_object->ID, 'event_participants', true);
				break;
			
			case 'orgs':
				return get_post_meta($this->post_object->ID, 'event_orgs', true);
				break;
		}
	}
		
	public function populate_end_date(){
		
		$start = get_post_meta($this->ID, 'event_date_start', true);
		$end = get_post_meta($this->ID, 'event_date_end', true);
		
		if(empty($end)&& !empty($start)){
			update_post_meta($this->ID, 'event_date_end', (int)$start);
			//var_dump(get_post_meta($this->ID, 'event_date_end', true));
		}
	}
	
	
	// expiration
	public function is_expired() {
		
		$today_stamp = strtotime('today midnight');
		$test = $this->date_end;
		if(!$test)
			$test = $this->date_start;
			
		
		if($test < $today_stamp)
			return true;
		
		return false;
	}	
	
	//microformats
	public function get_event_schema_prop(){
		
		return 'itemscope itemtype="http://schema.org/Event"';
	}
	
	public function get_event_name_prop(){
		
		return 'itemprop="name"';
	}
	
	public function get_event_desc_prop(){
		
		return 'itemprop="description"';
	}
	
	public function get_event_thumb_prop(){
		
		return 'itemprop="image"';
	}
	
	public function get_event_url_prop(){
		
		return 'itemprop="url"';
	}
	
	public function get_event_offer_field(){
		
		$out = '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="hidden">';
		$out .= '<span itemprop="price">0</span>';
		$out .= '<span itemprop="url">'.get_permalink($this->post_object).'</span>';
		$out .= '<span itemprop="priceCurrency ">руб.</span></span>';
		
		return $out;
	}
	
	/** == Dates == **/
	public function get_start_date_mark($show_weekday = true){
		
		$start_date = $this->date_start;
		if(empty($start_date))
			return '';
		
		$label = '';
		
		if($show_weekday) {
			//week mark
			$weekday = date('w', $start_date);		
			$week = array('вс', 'пн', 'вт', 'ср', 'чт', 'пт','сб');
			$weekday = (isset($week[(int)$weekday])) ? $week[(int)$weekday] : '';
			
			if(!empty($start_time = $this->time_start)){
				$label = sprintf('%s, %s, %s', $weekday, date_i18n('d.m.Y', $start_date), date_i18n('H:i', strtotime($start_time)));
				$date_text = date('Y-m-d', $start_date).'T'.date('H:i', strtotime($start_time));
			}
			else {
				$label =  sprintf('%s, %s', $weekday, date_i18n('d.m.Y', $start_date));
				$date_text = date('Y-m-d', $start_date).'T12:00';
			}
		}
		else {
			if(!empty($start_time = $this->time_start)){
				$label = sprintf('%s, %s', date_i18n('d.m.Y', $start_date), date_i18n('H:i', strtotime($start_time)));
				$date_text = date('Y-m-d', $start_date).'T'.date('H:i', strtotime($start_time));
			}
			else {
				$label =  date_i18n('d.m.Y', $start_date);
				$date_text = date('Y-m-d', $start_date).'T12:00';
			}
		}
		
		$label = "<span itemprop='startDate' content='{$date_text}'>{$label}</span>";
		return $label;
	}
	
	//formal or human - formats for dates
	public function get_date_mark($format = 'formal'){
		
		$mark = '';
		
		$s_date = $this->date_start;
		if(empty($s_date))
			return '';
		
		$e_date = $this->date_end;
		
		$s_time = ($this->time_start) ? date_i18n('H:i', strtotime($this->time_start)) : '';
		$e_time = ($this->time_end) ? date_i18n('H:i', strtotime($this->time_end)) : '';
		
		if(empty($e_date) || $e_date == $s_date){
			//one day 
			$mark = ($format == 'human') ? $this->_get_human_date($s_date) : date_i18n('d.m.Y', $s_date);
			
			$time = (!empty($s_time)) ? $s_time : '';
			$time .= (!empty($e_time) && $e_time != $s_time) ? ' - '.$e_time : '';
			
			$mark .= (!empty($time)) ? ', '.$time : '';
		}
		else {
			// many days
			$mark = ($format == 'human') ? $this->_get_human_date($s_date) : date_i18n('d.m.Y', $s_date);
			$mark .= (!empty($s_time)) ? ', '.$s_time : '';
			$mark .= ' - ';
			$mark .= ($format == 'human') ? $this->_get_human_date($e_date) : date_i18n('d.m.Y', $e_date);
			$mark .= (!empty($e_time)) ? ', '.$e_time : '';
		}
		
		return $mark;
	}
	
	protected function _get_human_date($stamp){
		
		$date = '';
		$weekday = date('w', $stamp);
		$month = date('n', $stamp);
		
		//week mark
		$week = array('вс', 'пн', 'вт', 'ср', 'чт', 'пт','сб');
		$weekday = (isset($week[(int)$weekday])) ? $week[(int)$weekday] : '';
		
		//month mark
		$months = array('ноль', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		$month = (isset($months[(int)$month])) ? $months[(int)$month] : '';
		
		if(empty($weekday) || empty($month))
			$date = date_i18n('d.m.Y', $stamp); //incorrect stamp
		else
			$date = date_i18n($weekday.' j '.$month, $stamp);
			
		return $date;
	}
	
	
	
	/** == Other metas == **/
	protected function _get_filters(){
		
		if(null === $this->filters){
			$this->filters = get_the_terms($this->post_object, $this->type_taxonomy);
		}
		
		return $this->filters;
	}
	
	protected function _get_city_term(){
		
		$terms = $this->_get_filters();
		if(empty($terms))
			return '';
		
		$city = get_term_by('slug', 'city', $this->type_taxonomy);
		$city_term = null;
		
		foreach($terms as $t){ 
			if($t->parent == $city->term_id){				
				$city_term = $t;
				break;
			}
		}
		
		return $city_term;
	}
	
	protected function _get_format_term(){
		
		$terms = $this->_get_filters();
		if(empty($terms))
			return '';
		
		$format = get_term_by('slug', 'format', $this->type_taxonomy);
		$format_term = null;
		
		foreach($terms as $t){ 
			if($t->parent == $format->term_id){				
				$format_term = $t;
				break;
			}
		}
		
		return $format_term;
	}
	
	/** metas text */
	public function get_participants_mark(){
		
		$out = '';
		
		if($part = $this->participants){
			$out .= '<span itemprop="performer" itemscope itemtype="http://schema.org/Person">';
			$out .= '<span itemprop="name">'.$part.'</span></span>';
		}
		
		if($orgs = $this->orgs){
			$out .= (!empty($out)) ? ', ' : '';
			$out .= '<span itemprop="performer" itemscope itemtype="http://schema.org/Organization">';
			$out .= '<span itemprop="name">'.$orgs.'</span></span>';
		}
		
		return $out;
	}
	
	public function get_format_mark($alt_name = false, $empty_others = false){
		
		$format = $this->_get_format_term();
		$f_name = '';
		
		if($alt_name)
			$f_name = get_term_meta($format->term_id, 'alt_name', true);
		
		if(empty($f_name))
			$f_name = $format->name;
		
		if($empty_others && $format->slug == 'others')
			$f_name = '';
		
		$f_name = sanitize_term_field('name', $f_name, $format->term_id, $format->taxonomy, 'display');
		
		return $f_name;
	}
		
	public function get_city_mark(){
		
		$mark = '';
		$term = $this->_get_city_term();
		
		if(empty($term))
			return $mark;
		
		if($term->slug != 'all-cities'){
			$mark = $term->name;
		}
		elseif(has_term('online', $this->type_taxonomy, $this->post_object)) {
			$mark = "Онлайн";
		}
		
		return $mark;
	}
	
	
	/** prebuild metas **/
	public function get_what_where_mark(){
		
		$city = $this->get_city_mark();
		
		if($city == 'Онлайн'){			
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="name">'.$city.'</span></span>';
		}
		else {
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';		
			$out .= '<span itemprop="addressCountry" class="hidden">Россия</span>'; //option for country?
			
			if($city)
				$out .= '<span itemprop="addressLocality">'.$city.'</span>';
			
			if($this->address)
				$out .= '<span itemprop="streetAddress" class="hidden">'.$this->address.'</span>';
				
			$out .= '</span>';
			
			if($this->location)
				$out .= '<span itemprop="name">'.$this->location.'</span>';
			
			$out .= "</span></span>";
		}
		
		
		return $out;
	}
	
	public function get_where_city_mark(){
		
		$city = $this->get_city_mark();
		
		if($city == 'Онлайн'){			
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="name">'.$city.'</span></span>';
		}
		else {
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';		
			$out .= '<span itemprop="addressCountry" class="hidden">Россия</span>'; //option for country?
			
			if($city)
				$out .= '<span itemprop="addressLocality">'.$city.'</span>';
			
			if($this->address)
				$out .= '<span itemprop="streetAddress" class="hidden">'.$this->address.'</span>';
				
			$out .= '</span>';
			
			if($this->location)
				$out .= '<span itemprop="name" class="hidden">'.$this->location.'</span>';
			
			$out .= "</span></span>";
		}
		
		
		return $out;
	}
	
	public function get_full_address_mark(){

		$city = $this->get_city_mark();
		if($city == 'Онлайн'){
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="name">'.$city.'</span></span>';
		}
		else {
			$out  = '<span itemprop="location" itemscope itemtype="http://schema.org/Place">';
			$out .= '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';		
			$out .= '<span itemprop="addressCountry" class="hidden">Россия</span>'; //option for country?
			
			if($city)
				$out .= '<span itemprop="addressLocality">'.$city.'</span>';
				
			if($this->address)
				$out .= '<span itemprop="streetAddress">'.$this->address.'</span>';
			
			$out .= '</span>';
			
			if($this->location)
				$out .= '<span itemprop="name">'.$this->location.'</span>';
			
			$out .= "</span></span>";
		}
			
		return $out;
	}
	
	
	
	
	/** Related report **/
	public function get_related_post_id(){
		global $wpdb;
		
		if($this->related_post_id === null){
			
			$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_value = '{$this->ID}' AND meta_key = 'related_event'");			
			if(!empty($results)){ foreach($results as $r) {
				
				$this->related_post_id[] = (int)$r->post_id;
			}}
		}
		
		return $this->related_post_id;
	}
	
	
	
	/** == Common templates == **/
	
	//used in cards
	public function posted_on_card(){
		
		$meta = array();
		$meta[] = "<span class='date'>".$this->date_mark_for_context('card')."</span>";
		$meta[] = "<span class='category'>".__('Events', 'rdc')."</span>";	
		
		return implode(rdc_get_sep('&middot;'), $meta);
		
	}
		
	//used on single top
	public function posted_on_single(){
		
		$meta = array();
		
		if($this->is_expired()){
			$meta[] = "<span class='date'>".$this->date_mark_for_context('single_top')."</span>";
		}
		else {
			$meta[] = rdc_add_to_calendar_link($this, false, 'date tst-add-calendar', $this->date_mark_for_context('single_top'));
		}
				
		$f_name = $this->get_format_mark();		
		$meta[] = "<span class='format'>".$f_name."</span>";
		$meta[] = "<span class='city'>".$this->get_city_mark()."</span>";
		
		$meta = array_filter($meta);
		
		return implode('', $meta);
	}
	
	//wrapper for story board content
	public function date_mark_for_context($context = 'card'){
		
		$date = '';
		switch($context){
			
			case 'storyboard':
				$date = $this->get_start_date_mark();
				break;
			
			case 'widget':
				$date = $this->get_start_date_mark();
				break;
			
			case 'single_top':
				$date = $this->get_start_date_mark(); // $this->get_date_mark('formal');
				break;
			
			case 'single_details':
				$date = $this->get_date_mark('human');
				break;
			
			default:
				$date = $this->get_start_date_mark();		
		}
		return $date;
	}
	
	
	//used in cards
	public function get_icons(){
		
		return "<span class='r-icon'>".rdc_svg_icon('icon-events', false)."</span>";
	}
	
} //class

