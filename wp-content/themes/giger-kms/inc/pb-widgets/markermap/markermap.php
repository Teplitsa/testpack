<?php
/*
Widget Name: [TST] Карта с маркерами
Description: Вывод карты с маркером или группой маркеров
*/

class TST_Markermap_Widget extends SiteOrigin_Widget {
	
	function __construct() {
		
		parent::__construct(
			'tst-markermap',
			'[TST] Карта с маркерами',
			array(
				'description' => 'Вывод карты с маркером или группой маркеров'				
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
	
	protected function get_defaults(){
		//this could be adjust for project needs
		return array(			
			'marker_ids'        => '',
			'layers_ids'        => '',
			'height'            => 460,
			'enablescrollwheel' => 'false',
			'zoom'              => 15,
			'disablecontrols'   => 'false',
			'lat_center'		=> '55.7257532',
			'lng_center' 		=> '37.6156754'
		);
	}
	
	/** admin form **/
	function initialize_form(){

		return array(			
			
			'marker_ids' => array(
				'type' => 'text',
				'label' => 'ID маркеров для вывода',
				'description' => 'Список IDs, разделенных запятыми',
			),
						
			'layers_ids' => array(
				'type' => 'text',
				'label' => 'ID групп маркеров для вывода',
				'description' => 'Список IDs, разделенных запятыми',
			),
						
			'height' => array(
				'type' => 'text',
				'label' => 'Высота карты в рх - по умолчанию 460'
			),
			
			'zoom' => array(
				'type' => 'text',
				'label' => 'Начальный уровень zoom - по умолчанию 15'
			),
			
			'lat_center' => array(
				'type' => 'text',
				'label' => 'Координаты центра - широта'
			),
			
			'lng_center' => array(
				'type' => 'text',
				'label' => 'Координаты центра - долгота'
			)
		);
	}
	
	/** prepare data for template **/	
	public function get_template_variables( $instance, $args ) {
		
		$defaults = $this->get_defaults();
		
		return array(
			'marker_ids' => sanitize_text_field($instance['marker_ids']),
			'layers_ids' => sanitize_text_field($instance['layers_ids']),
			'height'   	 => ($instance['height']) ? (int)($instance['height']) : $defaults['height'],
			'zoom'   	 => ($instance['zoom']) ? (int)($instance['zoom']) : $defaults['zoom'],
			'lat_center' => ($instance['lat_center']) ? sanitize_text_field($instance['lat_center']) : $defaults['lat_center'], 
			'lng_center' => ($instance['lng_center']) ? sanitize_text_field($instance['lng_center']) : $defaults['lng_center'], 
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
		$template_vars = wp_parse_args($template_vars, $this->get_defaults());
		
		extract( $template_vars );
		
		$map_id = uniqid( 'tst_map_' );
		$zoomControl = ($disablecontrols == 'false') ? 'true' : 'false';
		
		$css_name = $this->generate_and_enqueue_instance_styles( $instance );
		
		//markerquery
		$params = array(
			'post_type' => 'marker',
			'posts_per_page' => -1
		);
		
		if(!empty($marker_ids)) {
			$params['post__in'] = array_map('intval', explode(',', $marker_ids));	
		}
		elseif(!empty($layers_ids)){			
			$params['tax_query'] = array(
				array(
					'taxonomy' => 'marker_cat',
					'field' => 'term_id',
					'terms' => array_map('intval', explode(',', $layers_ids))
				)
			);
		}

        $markers = get_posts($params);		
		$markers_json = array();
		foreach($markers as $marker) {
	 
			$lat = get_post_meta($marker->ID, 'marker_location_latitude', true);
			$lng = get_post_meta($marker->ID, 'marker_location_longitude', true);
	 
			if(empty($lat) || empty($lng)) {
				continue;
			}
			 
			 
			//popap
			$name = get_the_title($marker);
			$addr = get_post_meta($marker->ID, 'marker_address', true);
			$content = apply_filters('tst_the_content', $marker->post_content);
					 
			$popup = '<div class="marker-content"><h4>'.$name.'</h4>';
			$popup .= "<div class='address'>".$addr."</div>";
			if(!empty($content))
				$popup .= "<div class='content'>".$addr."</div>";
				
			$popup .= "</div>";
			
			$type = get_the_terms($marker->ID, 'marker_cat');
			 
			$markers_json[] = array(
				'title' => esc_attr($marker->post_title),
				//'descr' => $descr,
				'lat' => $lat,
				'lng' => $lng,
				'popup_text' => $popup,
				'type' => (isset($type[0])) ? esc_attr($type[0]->slug) : '' ,
			);
		}
				
		echo $args['before_widget'];
		echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
	?>
	<div class="pw_map-wrap">
	<div class="pw_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr($height); ?>px; width:100%"></div>
	</div>
	<script type="text/javascript">
		if (typeof mapFunc == "undefined") {
			var mapFunc = new Array();
		}	
		
		mapFunc.push(function (){
			
			var map = L.map('<?php echo $map_id ; ?>', {
				zoomControl: <?php echo $zoomControl;?>,
				scrollWheelZoom: <?php echo $enablescrollwheel;?>,
				center: [<?php echo $lat_center;?>, <?php echo $lng_center;?>],
				zoom: <?php echo $zoom;?>
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

				L.marker([points[i].lat, points[i].lng], {
					title: points[i].title,
					alt: points[i].title,
					icon: L.divIcon({className: 'mymap-icon icon-'+points[i].type, iconSize: [32, 32]})
				}).addTo(map).bindPopup(points[i].popup_text);
			}
		});
			
	</script>
		<?php	
			echo '</div>';
			echo $args['after_widget'];
		
	}
	
	
} //class

add_action('wp_footer', function(){
	
	$base = get_template_directory_uri().'/assets/img/';	
?>
<script type="text/javascript">
	L.Icon.Default.imagePath = '<?php echo $base; ?>';
	
	if (typeof mapFunc != "undefined") {
		for (var i = 0; i < mapFunc.length; i++) {
			mapFunc[i]();
		}
	}	
</script>
<?php
}, 100);

//register
siteorigin_widget_register('tst-markermap', __FILE__, 'TST_Markermap_Widget');

