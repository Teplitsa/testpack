<?php
/**
 * Part Name: Встроенная карта ООПТ
 * Description: Карта со встроенным слоем зеленых зон НН.
 */

$prefix = 'oopt_map_';


$title = wds_page_builder_get_this_part_data($prefix.'title');
$subtitle = wds_page_builder_get_this_part_data($prefix.'subtitle');
$summary = wds_page_builder_get_this_part_data($prefix.'summary');?>

<div class="container">
    <div class="card card--text block-full">
		<?php if($title || $subtitle) { ?>
		<div class="oopt_map_desc">
			<?php if($title) { ?>
			<div class="card__title card__title--text">
				<h4><?php echo apply_filters('tst_the_title', $title);?></h4>
			</div>
			<?php }?>

			<?php if($subtitle) { ?>
			<div class="card__subtitle"><?php echo apply_filters('tst_the_title', $subtitle);?></div>
			<?php }?>
		</div>
		<?php } ?>

        <div class="resp-wrapper ratio-21x9">
            <iframe src="https://www.google.com/maps/d/embed?mid=1stzOTm5y9rlznPGqQN3HQXwmSuo&hl=ru" class="rate-16x9" allowfullscreen></iframe>
        </div>
    </div>
</div>

<?php //echo do_shortcode('[tst_markers_map kml_layer_url="'.$layer_file_url.'" enable_scroll_wheel="0"]');
