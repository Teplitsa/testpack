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
        'width' => '',
        'height' => 460,

        'enable_scroll_wheel' => false,
        'zoom' => 10,
        'disable_controls' => false,

        'lat_center' => '56.296504',
        'lng_center' => '43.936059',

        'show_legend' => true,
        'legend_is_filter' => true,

        'css_classes' => '',

    ), $atts));

    /** @var $groups_excluded_ids string */
    /** @var $groups_ids string */
    /** @var $markers_ids string */
    /** @var $width integer */
    /** @var $height integer */
    /** @var $enable_scroll_wheel bool */
    /** @var $zoom integer */
    /** @var $disable_controls bool */
    /** @var $lat_center float */
    /** @var $lng_center float */
    /** @var $show_legend bool */
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
            $groups['parents'][$group->term_id] = $group;
        }

        $child_groups = get_terms(array(
            'taxonomy' => 'marker_cat',
            'hide_empty' => false,
            'exclude' => $groups_excluded_ids,
            'childless' => true,
            'orderby' => 'parent,name',
        ));
        foreach($child_groups as $group) {

            $groups['children'][$group->term_id] = $group;
            $groups_ids[] = $group->term_id;

        }

    } else {

        $child_groups = get_terms(array(
            'taxonomy' => 'marker_cat',
            'hide_empty' => false,
            'include' => $groups_ids,
        ));
        foreach($child_groups as $group) {
            $groups['children'][$group->term_id] = $group;
        }

    }

    $markers_ids = $markers_ids ? array_map('intval', explode(',', $markers_ids)) : array();

    $width = $width ? trim($width) : '100%';
    $height = $height ? intval($height) : 200;

    $enable_scroll_wheel = empty($enable_scroll_wheel) || $enable_scroll_wheel == 'mobile_only' ?
        'mobile_only' : !!$enable_scroll_wheel;
    $zoom = intval($zoom);
    $disable_controls = $disable_controls ? true : false;
    $show_legend = !!$show_legend;
    $legend_is_filter = !!$legend_is_filter;

    $lat_center = floatval($lat_center);
    $lng_center = floatval($lng_center);

    $css_classes = trim($css_classes);

    ob_start();

    $map_id = uniqid('rdc_map_');

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
    foreach(get_posts($params) as $marker) {

        $lat = get_post_meta($marker->ID, 'marker_location_latitude', true);
        $lng = get_post_meta($marker->ID, 'marker_location_longitude', true);

        if( !$lat || !$lng ) {
            continue;
        }

        $popup_text = rdc_get_marker_popup($marker, $groups_ids);

        foreach(get_the_terms($marker, 'marker_cat') as $term) {

            $color = get_term_meta($term->term_id, 'layer_marker_colors', true);
            $type = get_term_meta($term->term_id, 'layer_marker_icon', true);

            $markers_json[$term->term_id][] = array(
                'title' => esc_attr($marker->post_title),
                //'descr' => $descr,
                'lat' => $lat,
                'lng' => $lng,
                'popup_text' => $popup_text,
                'class' => ($type ? $type : 'dashicons-sos').' '.($color ? $color : 'navi'),
            );
        }

    }?>

    <div class="<?php echo $css_classes;?>">
    <div class="pw_map-wrap" style="height: <?php echo esc_attr($height);?>px; width: <?php echo esc_attr($width);?>">
        <div class="pw_map_canvas" id="<?php echo esc_attr($map_id);?>"></div>
        <?php if($show_legend) {?>
        <div class="pw_map_legend"><?php echo rdc_get_legend($groups, $legend_is_filter); ?></div>
        <?php }?>
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

        jQuery(document).ready(function($){

            if(typeof tst_fill_group_layer == 'undefined') {
                function tst_fill_group_layer(group_markers) {

                    var group_layer = L.layerGroup();

                    $.each(group_markers, function(key, marker_data){

                        var marker = new L.marker([marker_data.lat, marker_data.lng], {
                            title: marker_data.title,
                            alt: marker_data.title,
                            icon: L.divIcon({
                                className: 'mymap-icon dashicons ' + marker_data.class,
                                iconSize: [25, 25] //,
//                                iconAnchor: [16, 32],
//                                popupAnchor: [-5, -26]
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

                var kosmo_light = L.tileLayer('http://{s}.tile.osm.kosmosnimki.ru/kosmo/{z}/{x}/{y}.png', {
                        id: 'kosmo_light',
                        attribution: 'Карта &copy; <a href="http://osm.org/copyright">Участники OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                        maxZoom: 24,
                        minZoom: 3
                    }),
                    map_id = '<?php echo $map_id;?>';

                var enable_scroll_zoom = '<?php echo strval($enable_scroll_wheel);?>';
                if(enable_scroll_zoom == 'mobile_only') {
                    enable_scroll_zoom = $(window).width() > 767;
                }

                maps[map_id] = L.map(map_id, {
                    zoomControl: <?php echo $disable_controls ? 'false' : 'true';?>,
                    scrollWheelZoom: enable_scroll_zoom,
                    center: [<?php echo $lat_center;?>, <?php echo $lng_center;?>],
                    zoom: <?php echo $zoom;?>,
                    layers: [kosmo_light]
                });

                $.each(points[map_id], function(group_id, group_markers){ // loop through all marker groups

                    marker_group_layers[map_id][group_id] = tst_fill_group_layer(group_markers);
                    marker_group_layers[map_id][group_id].addTo(maps[map_id]);

                });

            });

            $('ul.markers-map-legend.is-filter').on('click', 'li.legend-child', function(e){

                e.preventDefault();

                var $this = $(this);

                $this.toggleClass('marker-group-active');

                $.each(marker_group_layers[map_id], function(group_id, group_layer){
                    if(typeof group_layer != 'undefined') {
                        group_layer.clearLayers();
                    }
                });

                var $active_groups_lines = $this.parents('ul:first').find('li.marker-group-active');
                if($active_groups_lines.length) {

                    $active_groups_lines.each(function(index, element){

                        var $element = $(this),
                            group_id = $element.data('group-id');

                        marker_group_layers[map_id][group_id] = tst_fill_group_layer(points[map_id][group_id]);
                        marker_group_layers[map_id][group_id].addTo(maps[map_id]);

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
            }

        });
    </script>

<?php }, 100);


/** Helpers to print marker markup and classes **/
function rdc_get_marker_popup($marker, $layers_id = array()){

    $popup = '';
    $css = '';

    $name = get_the_title($marker);
    $addr = get_post_meta($marker->ID, 'marker_address', true);
    $content = trim(apply_filters('rdc_the_content', $marker->post_excerpt)); // $marker->post_content

    $thumbnail = get_the_post_thumbnail($marker->ID, 'small-thumbnail');

    // get info from connected campaign if any
    $rel_campaign = get_post_meta($marker->ID, 'marker_related_campaign', true);
    if($rel_campaign) {

        $campaign_data = rdc_get_data_from_connected_campaign($marker, $rel_campaign);
        if(empty($thumbnail)) {
            $thumbnail = $campaign_data['thumbnail'];
        }

        if(empty($content)) {
            $content = apply_filters('rdc_the_content', $campaign_data['content']);
        }

        $content .= "<p class='c-btn'>".$campaign_data['button']."</p>";

    }

    if($layers_id) {

        $layer = rdc_get_marker_layer_match($marker, $layers_id);
        if($layer && !empty($layer->description)){
            $content .= apply_filters('rdc_the_content', $layer->description);
        }

    } else {
        $css = 'normal';
    }

    $popup = "<div class='marker-content ".$css."'><div class='mc-title'>".$name."</div>";

    if($thumbnail) {
        $popup .=  "<div class='mc-thumb'>".$thumbnail."</div>";
    }

    $name_filtered = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($name, ENT_COMPAT, 'UTF-8')));
    $addr_filtered = trim(str_replace(array('"', "'", '«', '»'), array(''), html_entity_decode($addr, ENT_COMPAT, 'UTF-8')));
    $content_filtered = trim(str_replace(array('"', "'", '«', '»'), array(), html_entity_decode($content, ENT_COMPAT, 'UTF-8')));

    if($addr_filtered && $addr_filtered != $name_filtered) {
        $popup .= "<div class='mc-address'>$addr</div>";
    }

    if($content_filtered && $content_filtered != $name_filtered) {
        $popup .= "<div class='mc-content'>".$content."</div>";
    }

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

    return $data;
}

function rdc_get_marker_icon_class($marker, $layers_id = array()){

    $class = 'dashicons-sos navi';
    if( !$layers_id ) {
        $layer = rdc_get_marker_layer_match($marker, $layers_id);

        if($layer) {
            $color = get_term_meta($layer->term_id, 'layer_marker_colors', true);
            $type = get_term_meta($layer->term_id, 'layer_marker_icon', true);

            $color = $color ? $color : 'navi';
            $type = $type ? $type : 'dashicons-sos';
            $class = $type.' '.$color;
        }

    }

    return $class;

}

function rdc_get_marker_layer_match($marker, $layers_id) {

    $terms = get_the_terms($marker->ID, 'marker_cat');
    if( !$terms || !$layers_id ) {
        return false;
    }

    $res = false;
    foreach($terms as $term) {
        if(in_array($term->term_id, $layers_id)) {

            $res = $term;
            break;

        }
    }

    return $res; //$t;
}

function rdc_get_legend(array $groups, $legend_is_filter = true) {

    if( !$groups ) {
        return '';
    }
    $legend_is_filter = !!$legend_is_filter;

    $list = array();
    if( !empty($groups['parents']) ) {

        foreach($groups['parents'] as $parent) {

            $list_item =
            "<li class='legend-parent marker-group group-{$parent->term_id}' data-group-id='{$parent->term_id}'>"
                .rdc_get_layer_icon($parent->term_id).apply_filters('rdc_the_title', $parent->name);

            $children = array();
            foreach($groups['children'] as $child) {
                if($child->parent == $parent->term_id) {
                    $children[] = "<li class='legend-child marker-group group-{$child->term_id}' data-group-id='{$child->term_id}'>"
                        .rdc_get_layer_icon($child->term_id).apply_filters('rdc_the_title', $child->name)
                        ."</li>";
                }
            }
            if($children) {
                $list_item .= "<ul class='legend-children'>".implode('', $children)."</ul>";
            }

            $list[] = $list_item."</li>";

        }

    }

    return "<ul class='markers-map-legend ".($legend_is_filter ? 'is-filter' : '')."'>".implode('', $list)."</ul>";

}

function rdc_get_layer_icon($layer_id) {

    $color = get_term_meta($layer_id, 'layer_marker_colors', true);
    $type = get_term_meta($layer_id, 'layer_marker_icon', true);

    $color = ($color) ? $color : 'navi';
    $type = ($type) ? $type : 'dashicons-sos';
    $class = $type.' '.$color;

    return "<span class='mymap-icon dashicons $class'></span>";
}