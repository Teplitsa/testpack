<?php
/**
 * Part Name: Тройной блок - Картинка-плашка-карточка
 * Description: Тройной блок связанных элементов - картинка, плашка, карточка
 */

$prefix = "tripleblock_picture_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers
$color_scheme = wds_page_builder_get_this_part_data($prefix.'color_scheme');

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$el3 = wds_page_builder_get_this_part_data($prefix.'element3_post');

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
if(empty($el2)) {
	$el2 = wds_page_builder_get_this_part_data($prefix.'element2_file_id'); //correct field id
}



//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';
$grid_css .= ' '.tst_get_colors_for_section();


?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?> colors-<?php echo $color_scheme;?>">
		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
		<?php if(in_array(get_post_type((int)$el2), array('post', 'event'))) { ?>
			<?php tst_news_card((int)$el2, 'colored'); ?>
		<?php } else { ?>
			<?php tst_card_colored((int)$el2) ;?>
		<?php } ?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--item">
			<?php tst_card_linked((int)$el3, array('size' => 'block-1col')); ?>
		</div>
	</div>
</div>