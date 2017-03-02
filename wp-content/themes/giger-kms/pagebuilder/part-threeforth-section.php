<?php
/**
 * Part Name: Двойной блок - Картинка 3/4-раздел
 * Description: Двойной блок связанных элементов - картинка 3/4 колонки, ссылка на раздел сайта
 */

$prefix = "threeforth_section_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$label_order1 = wds_page_builder_get_this_part_data($prefix.'label1_order');

$section = wds_page_builder_get_this_part_data($prefix.'section');
$icon_id = '';
if(!empty($section)){
	$section = get_term_by('slug', 'departments', 'section');
	if($section){
		$icon_id = 'icon-item-'.$section->slug;
	}
}



//corrections for grid class
$grid_css = ($block_order == 'revers') ? ' row-reverse' : '';

//colorschemes
$grid_css .=  ' '.tst_get_colors_for_section();

?>
<div class="container">
	<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<div class="flex-cell--stacked sm-6 md-8 lg-9 card card--linked block-3col label-<?php echo $label_order1;?>">
			<?php tst_card_linked((int)$el1, array('size' => 'block-2col')) ;?>
		</div>
		<div class="flex-cell--stacked sm-6 lg-3 card card--section">
		<?php if($section) { ?>
			<div class="flex-column-centered">
				<a href="<?php echo get_term_link($section, $section->taxonomy);?>" class="card-link">
					<div class="card__icon">
						<?php tst_svg_icon($icon_id); ?>
					</div>
					<div class="card__title">
						<h4><?php echo apply_filters('tst_the_title', $section->name);?></h4>
					</div>
				</a>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
