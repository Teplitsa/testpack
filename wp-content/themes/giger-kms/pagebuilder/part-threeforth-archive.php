<?php
/**
 * Part Name: Двойной блок - Картинка 3/4-ссылка на архив
 * Description: Двойной блок связанных элементов - картинка 3/4 колонки, ссылка на архив
 */

$prefix = "threeforth_archive_";

$block_order = wds_page_builder_get_this_part_data($prefix.'block_order'); //direct-revers

$el1 = wds_page_builder_get_this_part_data($prefix.'element1_post');
$label_order1 = wds_page_builder_get_this_part_data($prefix.'label1_order');

$link_title = wds_page_builder_get_this_part_data($prefix.'link_title');
$link_url = wds_page_builder_get_this_part_data($prefix.'link_url');
if( $link_url && !preg_match( '/^(http[s]?:)\/\//', $link_url ) ) {
    $link_url = home_url( $link_url );
}
$icon_id = 'icon-item-dront-publications';



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
		<div class="flex-cell--stacked sm-6  md-4 lg-3 card card--section">
			<a href="<?php echo $link_url;?>" class="card-link">
				<div class="card__icon">
					<?php tst_svg_icon($icon_id); ?>
				</div>
				<div class="card__title">
					<h4><?php echo apply_filters('tst_the_title', $link_title);?></h4>
				</div>
			</a>
		</div>
	</div>
</div>
