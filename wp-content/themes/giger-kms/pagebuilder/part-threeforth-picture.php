<?php
/**
 * Part Name: Двойной блок - Картинка 3/4-плашка
 * Description: Двойной блок связанных элементов - картинка 3/4 колонки, карточка
 */

$prefix = "threeforth_picture_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
if(empty($el2)) {
	$el2 = wds_page_builder_get_this_part_data($prefix.'element2_file_id'); //correct field id
}



//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';
$grid_css .= ' '.tst_get_colors_for_section();

//colorschemes
?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-6 md-8 lg-9 card card--linked block-3col">
			<?php tst_card_linked((int)$el1, array('size' => 'block-3col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 md-4 lg-3 card card--colored">
			<?php tst_card_colored((int)$el2) ;?>
		</div>
	</div>
</div>