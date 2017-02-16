<?php
/**
 * Part Name: Одинарный блок - Текст со ссылкой
 * Description: Блок во всю ширину колонки и Текст и ссылка
 */

$prefix = "singleblock_text_";

$args = array();

$args['title'] = wds_page_builder_get_this_part_data($prefix.'title');
$args['subtitle'] = wds_page_builder_get_this_part_data($prefix.'subtitle');
$args['summary'] = wds_page_builder_get_this_part_data($prefix.'summary');
$args['action_text'] = wds_page_builder_get_this_part_data($prefix.'link_text');

$el = wds_page_builder_get_this_part_data($prefix.'element_post');

$args['action_url'] = get_permalink($el);
?>
<div class="container">
	<div class="card card--text block-full">
		<?php tst_card_text_markup($args); ?>
	</div>
</div>