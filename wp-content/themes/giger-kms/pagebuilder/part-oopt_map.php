<?php
/**
 * Part Name: Встроенная карта ООПТ 
 * Description: Карта со встроенным слоем зеленых зон НН. 
 */

$prefix = "oopt_map_";


$layer_file_url = wds_page_builder_get_this_part_data($prefix.'layer_file_url');

echo do_shortcode('[tst_markers_map kml_layer_url="'.$layer_file_url.'" enable_scroll_wheel="0"]');?>
