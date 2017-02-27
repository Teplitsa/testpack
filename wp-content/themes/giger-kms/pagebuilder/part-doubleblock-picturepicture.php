<?php
/**
 * Part Name: Двойной блок - Картинка-картинка
 * Description: Блок в 2 колонки - Картинка-картинка
 */

$prefix = "doubleblock_picturepicture_";


$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$label_order1 = wds_page_builder_get_this_part_data($prefix.'label1_order');

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
$label_order2 = wds_page_builder_get_this_part_data($prefix.'label2_order');


//corrections for grid class
$grid_css = '';
?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col label-<?php echo $label_order1;?>">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col label-<?php echo $label_order2;?>">
			<?php tst_card_linked((int)$el2, array('size' => 'block-2col')) ;?>
		</div>
	</div>
</div>