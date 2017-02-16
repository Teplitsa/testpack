<?php
/**
 * Part Name: Двойной блок - Картинка-сотрудники
 * Description: Блок в 2 колонки - Картинка-сотрудники
 */

$prefix = "doubleblock_picturepeople_";

$picture_position = wds_page_builder_get_this_part_data($prefix.'picture_position'); //left right

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');

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
$grid_css = ($picture_position == 'right') ? ' row-reverse' : '';
?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-12 lg-6 people-list block-2col">
			<div class="flex-column-centered">
			<?php
				if(!empty($people)) { foreach($people as $p) {
					tst_person_card($p);
				}}
			?>
			</div>
		</div>
	</div>
</div>