<?php
/**
 * Part Name: Двойной блок - Элемент-книжка: картинка/текст
 * Description: Блок во всю ширину колонки и Текст и ссылка
 */

$prefix = "doubleblock_element_";

$picture_position = wds_page_builder_get_this_part_data($prefix.'picture_position'); //left right

$args = array();

$args['title'] = wds_page_builder_get_this_part_data($prefix.'title');
$args['subtitle'] = wds_page_builder_get_this_part_data($prefix.'subtitle');
$args['summary'] = wds_page_builder_get_this_part_data($prefix.'summary');
$args['action_text'] = wds_page_builder_get_this_part_data($prefix.'link_text');

$el = wds_page_builder_get_this_part_data($prefix.'element_post');

$args['action_url'] = get_permalink($el);


//corrections for grid class
$grid_css = ($picture_position == 'right') ? ' row-reverse' : '';

?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-6 img-only block-2col">
			<?php echo tst_get_the_post_thumbnail((int)$el, 'block-2col'); ?>
		</div>
		<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
			<div class="flex-column-centered"><?php tst_card_text_markup($args); ?></div>
		</div>
	</div>
</div>