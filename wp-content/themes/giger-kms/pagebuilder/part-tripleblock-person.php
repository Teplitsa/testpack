<?php
/**
 * Part Name: Тройной блок - Плашка-картинка-сотрудник
 * Description: Тройной блок связанных элементов - плашка-картинка-сотрудник
 */

$prefix = "tripleblock_person_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$person = wds_page_builder_get_this_part_data($prefix.'person_post');

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
if(empty($el2)) {
	$el2 = wds_page_builder_get_this_part_data($prefix.'element2_file_id'); //correct field id
}



//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';
$grid_css .= ' '.tst_get_colors_for_section();


?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-3 card card--colored">
			<?php tst_card_colored((int)$el2) ;?>
		</div>

		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>

		<div class="flex-cell--stacked sm-12 lg-3 card card--person">
			<div class="flex-column-centered"><?php tst_person_card((int)$person);?></div>
		</div>
	</div>
</div>