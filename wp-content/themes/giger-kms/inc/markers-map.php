<?php
/*
Widget Name: [TST] Карта с маркерами
Description: Вывод карты с маркером или группой маркеров
*/

add_shortcode('tst_markers_map', 'tst_markers_map_output');
function tst_markers_map_output($atts){

    extract(shortcode_atts(array(

        'groups_excluded_ids' => '',
        'groups_ids' => '',
        'markers_ids' => '',
        'kml_layer_url' => '',
        'width' => '',
        'height' => 460,

        'enable_scroll_wheel' => false,
        'min_zoom' => '',
        'max_zoom' => '',
        'zoom' => 12,
        'disable_controls' => false,

        'lat_center' => '56.3156606',
        'lng_center' => '44.0003199',

        'show_legend' => true,
        'legend_title' => '',
        'legend_subtitle' => '',
        'legend_is_filter' => true,

        'css_classes' => '',

    ), $atts));

    /** @var $groups_excluded_ids string */
    /** @var $groups_ids string */
    /** @var $markers_ids string */
    /** @var $kml_layer_url string */
    /** @var $width integer */
    /** @var $height integer */
    /** @var $enable_scroll_wheel bool */
    /** @var $zoom integer */
    /** @var $min_zoom integer */
    /** @var $max_zoom integer */
    /** @var $disable_controls bool */
    /** @var $lat_center float */
    /** @var $lng_center float */
    /** @var $show_legend bool */
    /** @var $legend_title string */
    /** @var $legend_subtitle string */
    /** @var $legend_is_filter bool */
    /** @var $css_classes string */

    $groups_excluded_ids = $groups_excluded_ids ? array_map('intval', explode(',', $groups_excluded_ids)) : array();
    $groups_ids = $groups_ids ? array_map('intval', explode(',', $groups_ids)) : array();
    $groups = array();
    if( !$groups_ids ) {

        $parent_groups = get_terms(array(
            'taxonomy' => 'marker_cat',
            'hide_empty' => false,
            'exclude' => $groups_excluded_ids,
            'parent' => 0,
        ));

        foreach($parent_groups as $group) {

            $groups['parents'][$group->term_id] = array(
                'term_id' => $group->term_id,
                'name' => $group->name,
                'slug' => $group->slug,
            );

            $child_groups = get_terms(array(
                'taxonomy' => 'marker_cat',
                'hide_empty' => false,
                'exclude' => $groups_excluded_ids,
                'parent' => $group->term_id,
                'orderby' => 'name',
            ));
            foreach($child_groups as $child_group) {
                $groups['children'][$child_group->term_id] = array(
                    'term_id' => $child_group->term_id,
                    'name' => $child_group->name,
                    'slug' => $child_group->slug,
                );
            }
        }

    } else {

        $groups_selected = get_terms(array(
            'taxonomy' => 'marker_cat',
            'include' => $groups_ids,
            'exclude' => $groups_excluded_ids,
//            'parent' => 0,
            'hide_empty' => false,
        ));

        foreach($groups_selected as $group) {

            $group_children = get_terms(array(
                'taxonomy' => 'marker_cat',
                'parent' => $group->term_id,
                'exclude' => $groups_excluded_ids,
                'orderby' => 'name',
//                'hide_empty' => false, /** @todo Make it true (or just remove) when debug is over */
            ));

            if($group_children) {

                $groups['parents'][$group->term_id] = array(
                    'term_id' => $group->term_id,
                    'name' => $group->name,
                    'slug' => $group->slug,
                );

                foreach($group_children as $child_group) {
                    $groups['parents'][$group->term_id]['children'][$child_group->term_id] = array(
                        'term_id' => $child_group->term_id,
                        'name' => $child_group->name,
                        'slug' => $child_group->slug,
                        'parent' => $group->term_id,
                    );
                }

            } else {
                $groups['parents'][$group->term_id] = array(
                    'term_id' => $group->term_id,
                    'name' => $group->name,
                    'slug' => $group->slug,
                );
            }
        }

        if(isset($groups['parents']) && count($groups['parents']) == 1) {

            $groups['parents'] = reset($groups['parents']);
            $groups['parents'] = $groups['parents']['children'];

        }

    }

    $markers_ids = $markers_ids ? array_map('intval', explode(',', $markers_ids)) : array();

    $kml_layer_url = $kml_layer_url ? (string)$kml_layer_url : '';

    $width = $width ? trim($width) : '100%';
    $height = $height ? intval($height) : 200;

    $enable_scroll_wheel = $enable_scroll_wheel == 'mobile_only' ? 'mobile_only' : !!$enable_scroll_wheel;
    $zoom = intval($zoom);
    $min_zoom = intval($min_zoom) > 0 ? intval($min_zoom) : 7;
    $max_zoom = intval($max_zoom) > 0 ? intval($max_zoom) : 24;
    $disable_controls = $disable_controls ? true : false;
    $show_legend = !!$show_legend;
    $legend_title = $legend_title ? trim($legend_title) : '';
    $legend_subtitle = $legend_subtitle ? trim($legend_subtitle) : '';
    $legend_is_filter = !!$legend_is_filter;

    $lat_center = floatval($lat_center);
    $lng_center = floatval($lng_center);

    $css_classes = trim($css_classes);

    ob_start();

    $map_id = uniqid('tst_map_');

    $params = array(
        'post_type' => 'marker',
        'posts_per_page' => -1
    );

    if ($markers_ids) {

        $params['post__in'] = $markers_ids;
        $show_legend = false; // we won't show a legend for a single marker

    } elseif($groups_ids || $groups_excluded_ids) {

        $params['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'marker_cat',
                'field' => 'term_id',
                'terms' => $groups_ids
            ),
            array(
                'taxonomy' => 'marker_cat',
                'field' => 'term_id',
                'terms' => $groups_excluded_ids,
                'operator' => 'NOT IN'
            )
        );

        $show_legend = true;

    }

    $markers_json = array();
    $markers = count($params) == 2 ? tst_get_default_map_markers() : get_posts($params);
    $markers_groups_meta = array();
    foreach($markers as $marker) {

        $marker_data = tst_get_map_marker_data($marker->ID);

        if(empty($marker_data['lat']) || empty($marker_data['lng'])) {
            continue;
        }

        $popup_text = tst_get_marker_popup($marker, $marker_data);

        foreach($marker_data['groups'] as $term) {

            if(empty($markers_groups_meta[$term->term_id])) {
                $markers_groups_meta[$term->term_id] = array(
                    'color' => get_term_meta($term->term_id, 'layer_marker_colors', true),
                    'type' => get_term_meta($term->term_id, 'layer_marker_icon', true),
                );
            }

            $markers_json[$term->term_id][] = array(
                'title' => esc_attr($marker->post_title),
                'descr' => esc_attr($marker->post_content),
                'lat' => $marker_data['lat'],
                'lng' => $marker_data['lng'],
                'popup_text' => $popup_text,
                'class' => $markers_groups_meta[$term->term_id]['color'],
                'icon' => $markers_groups_meta[$term->term_id]['type'],
            );
        }

    }?>

    <div class="<?php echo $css_classes;?>">
        <div class="pw_map-wrap">
            <div class="pw_map_canvas" id="<?php echo esc_attr($map_id);?>" style="height: <?php echo esc_attr($height);?>px; width: <?php echo esc_attr($width);?>"></div>
            <?php if($show_legend) {

                $legend = tst_get_legend($groups, $legend_title, $legend_subtitle, $legend_is_filter);

                if($legend) {?>
                    <div class="pw_map_legend"><?php echo $legend;?></div>
                <?php }
            }?>
        </div>
        <script type="text/javascript">
            if(typeof mapFunc == "undefined") {
                var mapFunc = [];
            }

            if(typeof points == 'undefined') {
                var points = [];
            }
            if(typeof points['<?php echo $map_id;?>'] == 'undefined') {
                points['<?php echo $map_id;?>'] = <?php echo json_encode($markers_json);?>;
            }
            if(typeof marker_group_layers == 'undefined') {
                marker_group_layers = [];
            }

            if(typeof maps == 'undefined') {
                maps = [];
            }

            if(typeof marker_clusters == 'undefined') {
                var marker_clusters = [];
            }
            if(typeof marker_clusters['<?php echo $map_id;?>'] == 'undefined') {
                marker_clusters['<?php echo $map_id;?>'] = [];
            }

            jQuery(document).ready(function($){

                if(typeof tst_fill_group_layer == 'undefined') {
                    function tst_fill_group_layer(group_markers) {

                        var group_layer = L.layerGroup();

                        $.each(group_markers, function(key, marker_data){

                            var marker = new L.marker([marker_data.lat, marker_data.lng], {
                                title: marker_data.title,
                                alt: marker_data.title,
                                icon: L.divIcon({
                                    className: 'mymap-icon material-icons ' + marker_data.class,
                                    html: marker_data.icon,
                                    iconSize: [25, 25]
                                })
                            }).bindPopup(
                                L.popup({
                                    autoPan: true,
                                    autoPanPaddingTopLeft: [10, 10],
                                    autoPanPaddingBottomRight: [parseInt($('.pw_map_legend:first-child').width()) + 10, 50]
                                }).setContent(marker_data.popup_text)
                            );

                            group_layer.addLayer(marker);

                        });

                        return group_layer;

                    }
                }

                var map_id = '<?php echo $map_id;?>';

                if(typeof marker_group_layers[map_id] == 'undefined') {
                    marker_group_layers[map_id] = [];
                }
                if(typeof maps[map_id] == 'undefined') {
                    maps[map_id] = [];
                }

                mapFunc.push(function(){

                    var base_layer = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_nolabels/{z}/{x}/{y}.png', {
                        id: 'carto-light_nolabels',
                        maxZoom: <?php echo $max_zoom;?>,
                        minZoom: <?php echo $min_zoom;?>,
                        attribution: 'Карта &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy;<a href="https://carto.com/attribution">CARTO</a>'
                    });

                    var map_id = '<?php echo $map_id;?>';

                    var enable_scroll_zoom = '<?php echo strval($enable_scroll_wheel);?>';
                    if(enable_scroll_zoom == 'mobile_only') {
                        enable_scroll_zoom = $(window).width() > 767;
                    }

                    var bounds = L.latLngBounds(L.latLng(54.576765, 41.717001), L.latLng(58.218216, 47.814413));
                    maps[map_id] = L.map(map_id, {
                        zoomControl: <?php echo $disable_controls ? 'false' : 'true';?>,
                        scrollWheelZoom: enable_scroll_zoom,
                        minZoom: <?php echo $min_zoom;?>,
                        maxZoom: <?php echo $max_zoom;?>,
                        center: [<?php echo $lat_center;?>, <?php echo $lng_center;?>],
                        zoom: <?php echo $zoom;?>,
                        maxBounds: bounds,
                        layers: [base_layer]
                    });

                    var kml_layer_url = '<?php echo $kml_layer_url;?>';

                    if(kml_layer_url) {

                        var kmlLayer = new L.KML(kml_layer_url, {async: true});

//                        kmlLayer.on("loaded", function(e) {
//                            maps[map_id].fitBounds(e.target.getBounds());
//                        });

                        maps[map_id].addLayer(kmlLayer);

                    }

                    marker_clusters[map_id] = L.markerClusterGroup({maxClusterRadius: 30});
                    $.each(points[map_id], function(group_id, group_markers){ // loop through all marker groups

                        marker_group_layers[map_id][group_id] = tst_fill_group_layer(group_markers);

                        $.each(marker_group_layers[map_id][group_id].getLayers(), function(index, marker){
                            marker_clusters[map_id].addLayer(marker);
                        });

                    });
                    maps[map_id].addLayer(marker_clusters[map_id]);

                });

                $('ul.markers-map-legend.is-filter').on('click', 'li.interactive', function(e){

                    e.preventDefault();

                    var $this = $(this);

                    $this.toggleClass('marker-group-active');

                    $.each(marker_group_layers[map_id], function(group_id, group_layer){
                        if(typeof group_layer != 'undefined') {
                            group_layer.clearLayers();
                        }
                    });
                    marker_clusters[map_id].clearLayers();

                    var $active_groups_lines = $this.parents('ul:first').find('li.marker-group-active');
                    if($active_groups_lines.length) {

                        $active_groups_lines.each(function(index, element){

                            var $element = $(this),
                                group_id = $element.data('group-id');

                            marker_group_layers[map_id][group_id] = tst_fill_group_layer(points[map_id][group_id]);
                            $.each(marker_group_layers[map_id][group_id].getLayers(), function(index, marker){
                                marker_clusters[map_id].addLayer(marker);
                            });

                        });

                    } else { // None selected - show all markers groups

                        $.each(marker_group_layers[map_id], function(group_id, group_layer){
                            if(typeof group_layer != 'undefined') {

                                marker_group_layers[map_id][group_id] = tst_fill_group_layer(points[map_id][group_id]);
                                $.each(marker_group_layers[map_id][group_id].getLayers(), function(index, marker){
                                    marker_clusters[map_id].addLayer(marker);
                                });

                            }
                        });

                    }
                });
                
            });
            
        </script>
    </div>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;

}

add_action('wp_footer', function(){

    $base = get_template_directory_uri().'/assets/img/';?>

    <script type="text/javascript">
        L.Icon.Default.imagePath = '<?php echo $base;?>';

        jQuery(document).ready(function($){

            if(typeof mapFunc != 'undefined') {
                $.each(mapFunc, function(index, map_function){
                    map_function();
                });
                $('.event-template-default .leaflet-marker-pane .leaflet-marker-icon').addClass(' mymap-icon material-icons wetsoil for-events-marker').text('room').removeClass('leaflet-div-icon');
            }

        });
    </script>

<?php }, 100);

add_shortcode('tst_markers_list', 'tst_markers_list_output');
function tst_markers_list_output($atts){

    /** @var $show_list_title bool */
    /** @var $list_title string */

    $show_list_title = !empty($atts['show_list_title']);
    $list_title = empty($atts['show_list_title']) ? '' : esc_attr($atts['list_title']);

    $markers = tst_get_default_map_markers();

    if( !$markers ) {
        return;
    }

    if($show_list_title) {?>
        <h2 class="markers-list-title"><?php echo $list_title;?></h2>
    <?php }

    $markers_by_city = array('г. Оренбург' => array(),); // So we had a first city in a list
    $markers_data = array();
    foreach($markers as $marker) {

        $markers_data[$marker->ID] = tst_get_map_marker_data($marker->ID);
        $city = $markers_data[$marker->ID]['city'];
        if(in_array(mb_substr($city, 0, 1), array('г', 'с', 'п'))) {

            $city = str_replace(array(
                'г. ', 'с. ', 'п. ', 'г.', 'с.', 'п.'
            ), array(
                'г.', 'с.', 'п.', 'г. ', 'с. ', 'п. '
            ), $city);

        }

        if(empty($markers_by_city[$city])) {
            $markers_by_city[$city] = array($marker);
        } else {
            $markers_by_city[$city][] = $marker;
        }

    }?>

    <div class="markers-list">
        <?php foreach($markers_by_city as $city => $markers) {?>

            <?php if($markers) {?>
                <div class="markers-city">
                    <div class="city-name"><?php echo esc_attr($city);?></div>
                    <div class="city-markers">
                        <?php foreach($markers as $marker) { /** @marker WP_Post */ ?>
                            <div class="marker-data"><?php echo tst_get_markers_list_entry($marker, $markers_data[$marker->ID]);?></div>
                        <?php }?>
                    </div>
                </div>
            <?php }?>

        <?php }?>
    </div>
<?php }

/**
 * Helper to print marker list entry markup and classes
 * @var WP_Post $marker
 * @var array $marker_meta
 * @return string List markup text
 */
function tst_get_markers_list_entry(WP_Post $marker, array $marker_data) {

    $name = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode(get_the_title($marker), ENT_COMPAT, 'UTF-8')));
    $content = trim(str_replace(array('"', "'", '«', '»'), array(), html_entity_decode(trim(apply_filters('tst_the_content', $marker->post_excerpt)), ENT_COMPAT, 'UTF-8')));
    $addr = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($marker_data['address'], ENT_COMPAT, 'UTF-8')));
    $phones = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($marker_data['phones'], ENT_COMPAT, 'UTF-8')));

    $marker_markup = "<div class='mc-title'>".$name."</div>";

    if($addr) {
        $marker_markup .= "<div class='mc-address'><i class='material-icons'>place</i>$addr</div>";
    }
    if($content) {
        $marker_markup .= "<div class='mc-content'>".$content."</div>";
    }
    if($phones) {

        $phones = explode("\n", $phones);
        $marker_markup .= "<div class='mc-phones'><div class='phone'><i class='material-icons'>phone</i>"
            .implode("</div><div class='phone'><i class='material-icons'>phone</i>", $phones)
            ."</div></div>";

    }

    return $marker_markup;

}


/**
 * Helper to print marker markup and classes
 * @var WP_Post $marker
 * @var array $marker_meta
 * @return string Popup content text
 */
function tst_get_marker_popup(WP_Post $marker, array $marker_meta) {

    $name = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode(get_the_title($marker), ENT_COMPAT, 'UTF-8')));
    $content = trim(str_replace(array('"', "'", '«', '»'), array(), html_entity_decode(trim(apply_filters('tst_the_content', $marker->post_excerpt)), ENT_COMPAT, 'UTF-8')));

    $popup = "<div class='marker-content normal'><div class='mc-title'>".$name."</div>";

    $thumbnail = get_the_post_thumbnail($marker, 'small-square');
    if($thumbnail) {
        $popup .= "<div class='mc-thumbnail'>".$thumbnail."</div>";
    }

    if(isset($marker_meta['address'])) {

        $addr = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($marker_meta['address'], ENT_COMPAT, 'UTF-8')));
        if($addr != $name) {
            $popup .= "<div class='mc-address'><i class='material-icons'>place</i>$addr</div>";
        }

    }
    if(isset($marker_meta['contacts'])) {
        $contacts = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($marker_meta['contacts'], ENT_COMPAT, 'UTF-8')));
    }

    if($content) {
        $popup .= "<div class='mc-content'>".$content."</div>";
    }
    if( !empty($contacts) ) {

        $contacts = explode("\n", $contacts);
        $popup .= "<div class='mc-phones'><div class='phone'><i class='material-icons'>phone</i>"
            .implode("</div><div class='phone'><i class='material-icons'>phone</i>", $contacts)
            ."</div></div>";

    }

    $popup .= "</div>";

    return $popup;

}

function tst_get_legend(array $groups, $title = '', $subtitle = '', $legend_is_filter = true) {

    if( !$groups ) {
        return '';
    }

    $title = $title ? trim($title) : '';
    $subtitle = $subtitle ? trim($subtitle) : '';
    $legend_is_filter = !!$legend_is_filter;

    $list = array();
    if( !empty($groups['parents']) ) {

        foreach($groups['parents'] as $parent) {

            if(empty($parent['children'])) {
                continue;
            }

            $parent_li = "<li class='legend-parent marker-group group-{$parent['term_id']}' data-group-id='{$parent['term_id']}'>"
//                .tst_get_layer_icon($parent['term_id'])
                .'<div class="group-title parent '.(get_term_meta($parent['term_id'], 'layer_marker_colors', true)).'">'
                .apply_filters('tst_the_title', $parent['name'])
                .'</div>';

//            if( !empty($parent['children']) ) {

                $child_list = array();
                foreach($parent['children'] as $child) {
                    $child_list[] = "<li class='legend-child interactive marker-group group-{$child['term_id']}' data-group-id='{$child['term_id']}'>"
                        .tst_get_layer_icon($child['term_id'])
                        .'<div class="group-title child">'.apply_filters('tst_the_title', $child['name']).'</div>'
                        ."</li>";
                }

                $parent_li .= "<ul class='children-groups-list'>".implode('', $child_list)."</ul>";

//            }

            $list[] = $parent_li.'</li>';

        }

    }

    $title_part = ($title || $subtitle ? "<div class='legend-header'>"
        .($title ? "<div class='legend-title'>$title</div>" : "")
        .($subtitle ? "<div class='legend-subtitle'>$subtitle</div>" : "")
        ."</div>" : "");
    $list_part = empty($list) ?
        '' : "<ul class='markers-map-legend ".($legend_is_filter ? 'is-filter' : '')."'>".implode('', $list)."</ul>";

    return $list_part ? $title_part.$list_part : '';

}

function tst_get_layer_icon($layer_id) {

    $color = get_term_meta($layer_id, 'layer_marker_colors', true);
    $type = get_term_meta($layer_id, 'layer_marker_icon', true);

    $color = $color ? $color : 'yellow';
    $type = $type ? $type : 'add_circle';

    return "<i class='mymap-icon material-icons $color'>$type</i>";

}