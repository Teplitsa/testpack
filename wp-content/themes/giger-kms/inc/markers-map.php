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

            'lat_center'		=> '51.7675153',
            'lng_center' 		=> '55.0953063',

            'show_legend'		=> false
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
            ),

            'show_legend' => array(
                'type' => 'checkbox',
                'label' => 'Отображать легенду (для нескольких групп маркеров)',
                'default' => false
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
            'show_legend'=> ($instance['show_legend']) ? (bool)($instance['show_legend']) : $defaults['show_legend'],
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

        $map_id = uniqid( 'rdc_map_' );
        $zoomControl = ($disablecontrols == 'false') ? 'true' : 'false';

        $css_name = $this->generate_and_enqueue_instance_styles( $instance );

        //markerquery
        $params = array(
            'post_type' => 'marker',
            'posts_per_page' => -1
        );

        if(!empty($marker_ids)) {
            $params['post__in'] = array_map('intval', explode(',', $marker_ids));
            $show_legend = false; //no legend for single marker  - never
        }
        elseif(!empty($layers_ids)){
            $layers_ids = array_map('intval', explode(',', $layers_ids));
            $params['tax_query'] = array(
                array(
                    'taxonomy' => 'marker_cat',
                    'field' => 'term_id',
                    'terms' => $layers_ids
                )
            );

            if(count($layers_ids) > 1 && $show_legend)
                $show_legend = true;
            else
                $show_legend = false;
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
            $popup = rdc_get_marker_popup($marker, $layers_ids);

            $markers_json[] = array(
                'title' => esc_attr($marker->post_title),
                //'descr' => $descr,
                'lat' => $lat,
                'lng' => $lng,
                'popup_text' => $popup,
                'class' => rdc_get_marker_icon_class($marker, $layers_ids) ,
            );
        }

//		wp_enqueue_style( 'dashicons' ); // Caused leaflet map CSS bugs, so was transferred to the main CSS enqueue blocks
        echo $args['before_widget'];
        echo '<div class="so-widget-'.$this->id_base.' so-widget-'.$css_name.'">';
        ?>
        <div class="pw_map-wrap">
            <div class="pw_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr($height); ?>px; width:100%"></div>
            <?php if($show_legend) { ?>
                <div class="pw_map_legend"><?php echo rdc_get_legend($layers_ids);?></div>
            <?php } ?>
        </div>
        <script type="text/javascript">
            if (typeof mapFunc == "undefined") {
                var mapFunc = new Array();
            }

            mapFunc.push(function (){

                var mbAttr = 'Карта &copy; <a href="http://osm.org/copyright">Участники OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>';

                var carto_light = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {id: 'carto_light', attribution: mbAttr, maxZoom: 24, minZoom: 3 }),
                    carto_dark = L.tileLayer('http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {id: 'carto_dark', attribution: mbAttr, maxZoom: 24, minZoom: 3 }),
                    kosmo_light = L.tileLayer('http://{s}.tile.osm.kosmosnimki.ru/kosmo/{z}/{x}/{y}.png', {id: 'kosmo_light', attribution: mbAttr, maxZoom: 24, minZoom: 3 }),
                    kosmo_dark = L.tileLayer('http://{s}.tile.osm.kosmosnimki.ru/night/{z}/{x}/{y}.png', {id: 'kosmo_dark', attribution: mbAttr, maxZoom: 24, minZoom: 3 });

                var baseMaps = {
                    "Kosmo Dark": kosmo_dark,
                    "Kosmo Light": kosmo_light,
                    "Carto Light": carto_light,
                    "Carto Dark": carto_dark
                };

                var map = L.map('<?php echo $map_id ; ?>', {
                    zoomControl: <?php echo $zoomControl;?>,
                    scrollWheelZoom: false,
                    center: [<?php echo $lat_center;?>, <?php echo $lng_center;?>],
                    zoom: <?php echo $zoom;?>,
                    layers: [kosmo_dark]
                });

                //https://b.tile.openstreetmap.org/16/39617/20480.png
                //http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png
                //https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png
                //http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png
                //http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png
                //http://{s}.tile.osm.kosmosnimki.ru/kosmo/{z}/{x}/{y}.png
                //http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg

                //http://{s}.tile.osm.kosmosnimki.ru/night/{z}/{x}/{y}.png
                //http://b.tile.osm.kosmosnimki.ru/kosmo/16/39617/20480.png

                /*
                 L.tileLayer('http://{s}.tile.osm.kosmosnimki.ru/night/{z}/{x}/{y}.png', {
                 attribution: mbAttr,
                 maxZoom: 24,
                 minZoom: 3
                 }).addTo(map);*/

                L.control.layers(baseMaps).addTo(map);

                var points = <?php echo json_encode($markers_json);?>;
                for(var i=0; i<points.length; i++) {


                    var marker = L.marker([points[i].lat, points[i].lng], {
                        title: points[i].title,
                        alt: points[i].title,
                        icon: L.divIcon({
                            className: 'mymap-icon dashicons '+points[i].class,
                            iconSize: [32, 32],
                            iconAnchor: [16, 32]
                        })
                    })
                        .addTo(map)
                        .bindPopup(
                            L.popup({
                                autoPan : true,
                                autoPanPaddingTopLeft : [20,20]
                            })
                                .setContent(points[i].popup_text)
                        );

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


/** Helpers to print marker markup and classes **/
function rdc_get_marker_popup($marker, $layers_id = array()){

    $popup = '';
    $css = '';

    $name = get_the_title($marker);
    $addr = get_post_meta($marker->ID, 'marker_address', true);
    $content = apply_filters('rdc_the_content', $marker->post_content);
    $thumbnail = get_the_post_thumbnail($marker->ID, 'small-thumbnail');

    //get info from connected campaign if any
    $rel_campaign = get_post_meta($marker->ID, 'marker_related_campaign', true);
    if($rel_campaign) {
        $campaign_data = rdc_get_data_from_connected_campaign($marker, $rel_campaign);
        if(empty($thumbnail))
            $thumbnail = $campaign_data['thumbnail'];

        if(empty($content))
            $content = apply_filters('rdc_the_content', $campaign_data['content']);
        $content .= "<p class='c-btn'>".$campaign_data['button']."</p>";
    }

    if(!empty($layers_id)){
        $layer = rdc_get_marker_layer_match($marker, $layers_id);
        if($layer && !empty($layer->description)){
            $content .= apply_filters('rdc_the_content', $layer->description);
        }
    }
    else {
        $css = 'normal';
    }

    //markup
    $popup = "<div class='marker-content ".$css."'><div class='mc-title'>".$name."</div>";

    if(!empty($thumbnail))
        $popup .=  "<div class='mc-thumb'>".$thumbnail."</div>";

    $popup .= "<div class='mc-address'>".$addr."</div>";

    if(!empty($content))
        $popup .= "<div class='mc-content'>".$content."</div>";

    $popup .= "</div>";

    return $popup;
}

function rdc_get_data_from_connected_campaign($marker, $rel_campaign) {
    $data = array('thumbnail' => '', 'content' => '', 'button' => '');
    if(!class_exists('Leyka_Campaign'))
        return $data;

    $camp = new Leyka_Campaign($rel_campaign);
    $data['thumbnail'] = get_the_post_thumbnail($camp->ID, 'small-thumbnail');

    $label = ($camp->is_closed) ? 'Подробности' : 'Поддержать';
    $css = ($camp->is_closed) ? 'button' : 'button-red';
    $data['button'] = "<a href='".get_permalink($camp->ID)."' class='{$css}'>{$label}</a>";

    if(rdc_is_children_campaign($camp->ID)){

        $m = array();
        $m[]  = get_post_meta($camp->ID, 'campaign_child_age', true);
        $m[] = get_post_meta($camp->ID, 'campaign_child_diagnosis', true);
        array_filter($m);

        $data['content'] = implode(', ', $m);
    }

    return $data;
}

function rdc_get_marker_icon_class($marker, $layers_id = array()){

    $class = 'dashicons-sos navi';
    if(!empty($layers_id)){
        $layer = rdc_get_marker_layer_match($marker, $layers_id);

        if($layer){
            $color = get_term_meta($layer->term_id, 'layer_marker_colors', true);
            $type = get_term_meta($layer->term_id, 'layer_marker_icon', true);

            $color = ($color) ? $color : 'navi';
            $type = ($type) ? $type : 'dashicons-sos';
            $class = $type.' '.$color;
        }

    }

    return $class;
}

function rdc_get_marker_layer_match($marker, $layers_id) {

    $terms = get_the_terms($marker->ID, 'marker_cat');
    if(empty($terms) || empty($layers_id))
        return false;

    $res = false;
    // var_dump($layers_id);
    // if (!empty($layers_id)) {
    // 	$layers_id = str_split($layers_id);
    // }
    foreach($terms as $t){
        if(in_array($t->term_id, $layers_id)){
            $res = $t;
            break;
        }
    }

    return $t;
}

function rdc_get_legend($layers_id) {

    $list = array();
    foreach($layers_id as $l) {
        $layer = get_term($l, 'marker_cat');
        $name = apply_filters('rdc_the_title', $layer->name);
        $list[] = "<li>".rdc_get_layer_icon($l).$name."</li>";
    }

    return "<ul>".implode('', $list)."</ul>";
}

function rdc_get_layer_icon($layer_id) {

    $color = get_term_meta($layer_id, 'layer_marker_colors', true);
    $type = get_term_meta($layer_id, 'layer_marker_icon', true);

    $color = ($color) ? $color : 'navi';
    $type = ($type) ? $type : 'dashicons-sos';
    $class = $type.' '.$color;

    return "<span class='mymap-icon dashicons $class'></span>";
}