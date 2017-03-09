<?php
/**
 * Part Name: Двойной блок - Картинка-текстовый элемент
 * Description: Блок в 2 колонки - Картинка-текстовый элемент
 */

$prefix = "doubleblock_picture_";

$picture_position = wds_page_builder_get_this_part_data($prefix.'picture_position'); //left right

//picture element
$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');

//text element
$args = array();

$args['title'] = wds_page_builder_get_this_part_data($prefix.'title');
$args['subtitle'] = wds_page_builder_get_this_part_data($prefix.'subtitle');
$args['summary'] = wds_page_builder_get_this_part_data($prefix.'summary');
$args['action_text'] = wds_page_builder_get_this_part_data($prefix.'link_text');

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');
$args['action_url'] = get_permalink($el2);

if(empty($args['title']))
	$args['title'] = get_the_title((int)$el2);

if(empty($args['subtitle']))
	$args['subtitle'] =  tst_get_post_meta((int)$el2);

if(empty($args['summary']))
	$args['summary'] =  tst_get_post_excerpt((int)$el2, 15);

if(empty($args['action_text']))
	$args['action_text'] = __('Details', 'tst');

//corrections for grid class
$grid_css = ($picture_position == 'right') ? ' row-reverse' : '';
$grid_css .= ' '.tst_get_colors_for_section();

?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>

		<div class="flex-cell--stacked sm-12 lg-6 people-list block-2col">
			<div class="flex-column-centered"><?php tst_card_text_markup($args); ?></div>
		</div>
	</div>
</div>
