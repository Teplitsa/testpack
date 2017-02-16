<?php
/**
 * Part Name: Одинарный блок - Картинка с лейбл-ссылкой
 * Description: Блок во всю ширину колонки и ссылка
 */

$prefix = "singleblock_picture_";


$label_order = wds_page_builder_get_this_part_data($prefix.'label_order'); //direct-revers

$el = wds_page_builder_get_this_part_data($prefix.'element_post');

?>
<div class="container">
	<div class="card card--linked block-full label-<?php echo $label_order;?>">
	<?php tst_card_linked((int)$el, array('size' => 'block-full')) ;?>
	</div>
</div>