<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;

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
			var mapFunc = new Array();
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
						className: 'mymap-icon '+points[i].class,
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