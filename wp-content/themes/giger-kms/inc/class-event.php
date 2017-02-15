<?php
/**
 * Event class
 **/

class TST_Event {

	public $post_object;

	public function __construct($post) {

		if(is_object($post)){
			$this->post_object = $post;
		}
		elseif((int)$post > 0) {
			$this->post_object = get_post((int)$post);
		}
	}

	public function __get($key) {

		if(property_exists($this->post_object , $key)) {
			return $this->post_object->$key;
        }

		switch($key) {
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

			case 'contact':
				return get_post_meta($this->post_object->ID, 'event_contact', true);
				break;

			case 'name':
				$name = get_post_meta($this->post_object->ID, 'event_name', true);
				if(empty($name))
					$name = $this->post_object->post_title;
				return $name;
				break;

//			case 'city':
//				return $this->get_city_mark();
//				break;
		}

        return false;
	}


	public function populate_end_date(){

		$start = get_post_meta($this->ID, 'event_date_start', true);
		$end = get_post_meta($this->ID, 'event_date_end', true);

		if(empty($end) && !empty($start)){
			update_post_meta($this->ID, 'event_date_end', (int)$start);
			//var_dump(get_post_meta($this->ID, 'event_date_end', true));
		}
	}

	public function populate_dates_from_submission() {
		//get data from user submitted values

		if(empty($this->date_start)) {

			$start = get_post_meta($this->ID, 'event_contact_datestart', true);
			if(!empty($start)){
				update_post_meta($this->ID, 'event_date_start', strtotime($start));
			}

			if(empty($this->date_end) && !empty($start)){
				$end = get_post_meta($this->ID, 'event_contact_dateend', true);
				if(!empty($end) && (strtotime($start) <= strtotime($end))) {
					update_post_meta($this->ID, 'event_date_end', strtotime($end));
				}
			}
		}

	}

	public function add_section(){

		if($this->is_expired() && !has_term('pastevents', 'section', $this->post_object)){
			$category = get_term_by('slug', 'pastevents', 'section');
			if($category) {
				wp_set_post_terms($this->ID, array($category->term_id), $category->taxonomy);
			}
		}
		elseif(!$this->is_expired() && !has_term('events', 'section', $this->post_object )){

			$category = get_term_by('slug', 'events', 'section');
			if($category) {
				wp_set_post_terms($this->ID, array($category->term_id), $category->taxonomy);
			}
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


	/** Dates **/
	public function get_start_date_mark($show_weekday = true){
		//сб, 08.10.2016, 10:00

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
			}
			else {
				$label =  sprintf('%s, %s', $weekday, date_i18n('d.m.Y', $start_date));
			}
		}
		else {
			if(!empty($start_time = $this->time_start)){
				$label = sprintf('%s, %s', date_i18n('d.m.Y', $start_date), date_i18n('H:i', strtotime($start_time)));
			}
			else {
				$label =  date_i18n('d.m.Y', $start_date);
			}
		}

		return $label;
	}

	public function get_full_date_mark($format = 'formal') {
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
			$date = date_i18n($weekday.', j '.$month, $stamp);

		return $date;
	}


	/** Address and Location **/
	public function get_city_term() {

		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
			return false;

		$reg = get_the_terms($this->ID, 'regions');
		if(empty($reg))
			return false;

		$city = '';
		foreach($reg as $r){
			if($r->parent > 0){
				$city = $r;
				break;
			}
		}

		return $city;
	}

//	public function get_city_mark() {
//		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
//			return __('Online', 'tst');
//
//
//		$city = $this->get_city_term();
//		if(!$city)
//			return '';
//
//		return $city->name;
//	}

	public function get_regions_meta() {

		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
			return '';


		return tst_single_regions_list($this->post_object);
	}

	public function get_city_logo_url() {

		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
			return ''; //to-do - make special picture for that case

		$reg = get_the_terms($this->ID, 'regions');
		if(empty($reg))
			return '';

		$city = $this->get_city_term();
		if(!$city)
			return '';

		return get_term_meta($city->term_id, 'region_image', true);
	}

	public function get_full_address($add_location = true) {

		$list = array();

		$addr = $this->address;
		$addr = preg_replace ('/г.(\s+)?,/' , '' , $addr); //fix for incorrect migration artefact

		if($addr) {
			$list[] = $addr;
		}

		if( !!$add_location ) {
			$list[] = $this->location;
        }

        $addr = implode(', ', array_filter($list));

		return $addr;

	}


	/** Name and format **/
	public function get_format_mark() {

		$formats = get_term_by('slug', 'evformat', 'event_cat');
		$terms = get_the_terms($this->ID, 'event_cat');

		if(empty($terms))
			return '';

		$mark = '';
		foreach($terms as $t){
			if($t->parent == $formats->term_id){
				$mark = $t->name;
				break;
			}
		}

		return $mark;
	}


	/** Metas **/
	public function get_format_meta() {

		$sep = tst_get_sep('&middot;');
		$meta = array();
//		$meta[] = tst_post_topics_list($this->post_object, 'all');

		$format = $this->get_format_mark();
		$meta[] = ($format) ? "<span class='format'>{$format}</span>" : '';

		$meta = array_filter($meta);

		return implode($sep, $meta);
	}

	public function has_marker() {
		$lat = get_post_meta($this->ID, 'event_marker_latitude', true);
		$lng = get_post_meta($this->ID, 'event_marker_longitude', true);

		if(empty($lat) || empty($lng))
			return false;

		return true;
	}

	public function get_regular_card_meta() {

		$meta = array();
		$meta[] = "<span class='event-date'>".$this->get_start_date_mark()."</span>";

//		if( !$this->is_expired() )
//			$meta[] = tst_add_to_calendar_link($this, 'tst-add-calendar', __('Add to calendar', 'tst'), false);

        $meta[] = "<span class='event-location'>".$this->get_full_address()."</span>";

		return $meta;
	}



	/* Build schema **/
	function schema_markup() {

		$ex = tst_get_post_excerpt($this->post_object, 30, true);

		$thumb = get_the_post_thumbnail_url($this->ID, 'full');
		if($thumb) {
			$thumb = esc_url(trim(str_replace(array('//', 'http:', 'https:'), '', $thumb)));
        }
	?>
		<script type="application/ld+json">
		{
			"@context": "http://schema.org",
			"@type": "Event",
			"name": "<?php echo esc_attr($this->name);?>",
			"startDate" : "<?php echo $this->get_start_date_schema_text();?>",
			"endDate" 	: "<?php echo $this->get_end_date_schema_text();?>",
			"url" : "<?php echo esc_url(get_permalink($this->ID));?>",
			"description" : "<?php echo esc_attr($ex);?>",
			<?php if(has_post_thumbnail($this->ID)) { ?>
			"image" : "<?php echo $thumb;?>",
			<?php  }?>
			"location" : {
				"@type" : "Place",
				"address" : "<?php echo esc_attr($this->get_address_schema_text());?>",
				"name" : "<?php echo esc_attr($this->get_location_schema_text());?>"
			}
		}
		</script>
	<?php
	}

	protected function get_start_date_schema_text() {

		$date = $this->date_start;
		$time = $this->time_start;
		$date_text = '';

		if(empty($time)){
			$date_text = date('Y-m-d', $date).'T12:00';
		}
		else {
			$date_text = date('Y-m-d', $date).'T'.date('H:i', strtotime($time));
		}

		return  $date_text;
	}

	protected function get_end_date_schema_text() {

		$date = $this->date_end;
		$time = $this->time_end;

		if(empty($date))
			$date = $this->date_start;

		if(empty($time) && $date == $this->date_start){
			$time = ($this->time_start) ? date('H.i', strtotime($this->time_start.' +2 hours')) : '14.00';
		}

		if(empty($time)){
			$date_text = date('Y-m-d', $date).'T12:00';
		}
		else {
			$date_text = date('Y-m-d', $date).'T'.date('H:i', strtotime($time));
		}

		return  $date_text;
	}

	protected function get_address_schema_text() {

		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
			return __('Online', 'tst');

		$addr = $this->get_full_address(false);

		if(empty($addr)) {
			$addr = __('Russia', 'tst');
        }

		return $addr;
	}

	protected function get_location_schema_text() {

		if(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID))
			return __('Online', 'tst');

		$location = $this->location;

		if(empty($location))
			$location = __('By address', 'tst');

		return $location;
	}


	/** Thumbnails **/
	function post_thumbnail($size = 'post-thumbnail') {

		$thumb = '';

		if(has_post_thumbnail($this->ID)) {
			if($size == 'flex-base'){
				do_action('tst_before_get_post_thumbnail', $this->ID, $size);
				$thumb = get_the_post_thumbnail($this->ID, $size);
			}
			else {
				$thumb_id = get_post_thumbnail_id($this->ID);
				if($thumb_id){
					do_action('tst_before_get_post_thumbnail', $this->ID, $size);

					$src = wp_get_attachment_image_src($thumb_id, $size);
					$thumb = "<div class='fixed-thumbnail {$size}' style='background-image: url(".$src[0].")'></div>";
				}
			}
		}
		elseif(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID)){

			$thumb = "<div class='logo-frame'><div class='logo-frame--position'>";
			$thumb .= tst_svg_icon('icon-online', false)."</div></div>";

		}
		else {
			//here may be the default url finally
			$logo_url = '';
			$thumb = "<div class='fixed-thumbnail {$size}' style='background-image: url(".$logo_url.")'></div>";
		}

		return $thumb;
	}

	function post_thumbnail_widget_markup() {

		$thumb = '';

		if(has_post_thumbnail($this->ID)) {
			do_action('tst_before_get_post_thumbnail', $this->ID, 'thumbnail');

			$thumb_id = get_post_thumbnail_id($this->ID);
			$src = wp_get_attachment_image_src($thumb_id, 'thumbnail');
			$thumb = "<div class='fixed-thumbnail thumbnail' style='background-image: url(".$src[0].")'></div>";
		}
		elseif(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID)){

			$thumb = "<div class='logo-frame'><div class='logo-frame--position'>";
			$thumb .= tst_svg_icon('icon-online', false)."</div></div>";

		}
		else {
			//here may be the default url finally
			$logo_url = '';
			$thumb = "<div class='fixed-thumbnail {$size}' style='background-image: url(".$logo_url.")'></div>";
		}

		return $thumb;
	}

	function post_thumbnail_card_markup() {

		if(has_post_thumbnail($this->ID)) {

			$thumb_args = array(
				'placement_type'	=> 'medium-mini-small-small-small',
				'aspect_ratio' 		=> 'video',
				'crop' 				=> 'fixed'
			);

			$thumb = tst_get_post_thumbnail_picture($this->post_object, $thumb_args);
		}
		elseif(has_term('eventonline', 'event_cat', $this->ID) || has_term('vebinar', 'event_cat', $this->ID)){

			$thumb = "<div class='logo-frame'><div class='logo-frame--position'>";
			$thumb .= tst_svg_icon('icon-online', false)."</div></div>";

		}
		else{
			$thumb = "<div class='tst-thumbnail__nopicture'></div>";
		}

		return $thumb;
	}


} //class

/** Customize events archive query */
add_action('pre_get_posts', function(WP_Query $query) {

    if($query->is_post_type_archive('event')) {

        $query->set('meta_key', 'event_date_start');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'DESC');

    }

});

/** Display map **/
function tst_post_map($post_id, $echo = true) {

    //marker coords
    $lat = get_post_meta($post_id, 'event_marker_latitude', true);
    $lng = get_post_meta($post_id, 'event_marker_longitude', true);

    if(empty($lat) || empty($lng)) {
        //todo- make geocode request by api

        return;
    }

    $map_id = uniqid( 'tst_map_' );

    $markers_json[] = array(
        'title' => esc_attr(get_the_title($post_id)),
        //'descr' => $descr,
        'lat' => $lat,
        'lng' => $lng,
        'class' => 'myicon'
    );

    ob_start();
    ?>
    <div class="event-map-block">
        <div class="pw_map-wrap">
            <div class="pw_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height:350px; width:100%"></div>
        </div>

        <script type="text/javascript">
            if (typeof mapFunc == "undefined") {
                var mapFunc = [];
            }

            mapFunc.push(function (){

                var map = L.map('<?php echo $map_id ; ?>', {
                    zoomControl: true,
                    scrollWheelZoom: false,
                    center: [<?php echo $lat;?>, <?php echo $lng;?>],
                    zoom: 15
                });

                //https://b.tile.openstreetmap.org/16/39617/20480.png
                //http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Карта &copy; <a href="http://osm.org/copyright">Участники OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                    maxZoom: 24,
                    minZoom: 3
                }).addTo(map);

                var points = <?php echo json_encode($markers_json);?>;
                for(var i=0; i<points.length; i++) {

                    var marker = L.marker([points[i].lat, points[i].lng], {
                        title: points[i].title,
                        alt: points[i].title,
                        icon: L.divIcon({
//                            className: 'mymap-icon '+points[i].class,
                            iconSize: [32, 32],
                            iconAnchor: [16, 32]
                        })
                    })
                        .addTo(map);
                }
            });

        </script>
    </div>
    <?php
    $out = ob_get_contents();
    ob_get_clean();

    if($echo)
        echo $out;
    else
        return $out;
}