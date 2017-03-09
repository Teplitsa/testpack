<?php
/**
 * Part Name: Двойной блок - Картинка 3/4-сотрудник
 * Description: Двойной блок связанных элементов - картинка 3/4 колонки, сотрудник
 */

$prefix = "threeforth_person_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$label_order1 = wds_page_builder_get_this_part_data($prefix.'label1_order');
$person = wds_page_builder_get_this_part_data($prefix.'person_post');




//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';

//colorschemes
$grid_css .=  ' '.tst_get_colors_for_section();

?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-6 md-8 lg-9 card card--linked block-3col label-<?php echo $label_order1;?>">
			<?php tst_card_linked((int)$el1, array('size' => 'block-3col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--person">
			<div class="flex-column-centered"><?php tst_person_card((int)$person);?></div>
		</div>
	</div>
</div>
