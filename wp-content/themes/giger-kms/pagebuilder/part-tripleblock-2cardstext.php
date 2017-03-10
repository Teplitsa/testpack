<?php
/**
 * Part Name: Тройной блок - Картинка-плашка-плашка
 * Description: Тройной блок связанных элементов - картинка, плашка, плашка
 */

$prefix = "tripleblock_2cardstext_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

//text element
$args = array();

$args['title'] = wds_page_builder_get_this_part_data($prefix.'title');
$args['subtitle'] = wds_page_builder_get_this_part_data($prefix.'subtitle');
$args['summary'] = wds_page_builder_get_this_part_data($prefix.'summary');
$args['action_text'] = wds_page_builder_get_this_part_data($prefix.'link_text');

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$args['action_url'] = get_permalink($el1);


//cards
$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
if(empty($el2)) {
	$el2 = wds_page_builder_get_this_part_data($prefix.'element2_file_id');
}

$el3 = wds_page_builder_get_this_part_data($prefix.'element3_post');
if(empty($el3)) {
	$el3 = wds_page_builder_get_this_part_data($prefix.'element3_file_id');
}


//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';
$grid_css .= ' '.tst_get_colors_for_section();


?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">

		<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
			<?php tst_card_colored((int)$el2) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
			<?php tst_card_colored((int)$el3) ;?>
		</div>
		<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
			<div class="flex-column-centered"><?php tst_card_text_markup($args); ?></div>
		</div>
	</div>
</div>