<?php
/**
 * Part Name: Тройной блок -  Сотрудники-плашка-плашка
 * Description: Тройной блок связанных элементов - сотрудники, плашка, карточка
 */

$prefix = "tripleblock_people2cards_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers
$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
if(empty($el1)) {
	$el1 = wds_page_builder_get_this_part_data($prefix.'element1_file_id');
}

$el2 = wds_page_builder_get_this_part_data($prefix.'element2_post');

$p_ids = wds_page_builder_get_this_part_data($prefix.'people_ids');
$people = array();

if(!empty($p_ids)) {
	$p_ids = array_map('intval', explode(',', $p_ids));

	$people = get_posts(array(
		'post_type' => 'person',
		'posts_per_page' => 3,
		'post__in' => $p_ids
	));
}





//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';

//colorschemes
//position of label for picture block

?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-6 people-list block-2col">
			<div class="flex-column-centered">
			<?php
				if(!empty($people)) { foreach($people as $p) {
					tst_person_card($p);
				}}
			?>
			</div>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
			<?php tst_card_colored((int)$el1) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--item">
			<?php tst_card_linked((int)$el2, array('size' => 'block-small')) ;?>
		</div>
	</div>
</div>