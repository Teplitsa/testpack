<?php
/**
 * Part Name: Помочь нам
 * Description: Тройной блок с опциями поддержки
 */

$prefix = "help_";


$img1 = wds_page_builder_get_this_part_data($prefix.'img1_file_id'); 
$img2 = wds_page_builder_get_this_part_data($prefix.'img2_file_id');

//get real IDs of pages
$support = get_page_by_path('donate', 'OBJECT', 'leyka_campaign');
$volunteer = get_page_by_path('volunteer');
$corporate = get_page_by_path('corporate');


//corrections for grid class
$grid_css = '';

function tst_colored_help_card($help_id) {

	$pl = get_permalink($help_id); //volunteer url here
?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__title">
		<h4><?php _e('Join our actions', 'tst');?></h4>
	</div>

	<div class="card__button">
		<?php _e('Become volunteer', 'tst');?>
	</div>
</a>
<?php
}


function tst_linked_help_card($help, $img_id = 0, $args = array()){

	$defaults = array(
		'size' => 'block-2col',
		'title' => __('Support our programms', 'tst'),
		'button' => __('Donate', 'tst'),
	);

	$args = wp_parse_args($args, $defaults);

	$pl = get_permalink($help);
	$thumbnail = '';

	if($img_id > 0){
		$thumbnail = wp_get_attachment_image($img_id, $args['title']);
	}
	else {
		$thumbnail = get_the_post_thumbnail($help, $args['size']);
	}
?>
<a href="<?php echo $pl;?>" class="card-link">
	<div class="card__thumbnail">
		<?php echo $thumbnail; ?>
	</div>

	<div class="card__label">
		<h4><?php echo $args['title'];?></h4>
		<div class="card__button"><?php echo $args['button'];?></div>
	</div>
</a>
<?php
}



?>
<div id="help-block" class="help-block container">
	<h3 class="help-block__title"><?php _e('Support us', 'tst'); ?></h3>
	<div class="help-block__content">
		<div class="flex-grid--stacked <?php echo $grid_css;?>">
			<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
				<?php tst_linked_help_card($support, (int)$img1, array('size' => 'block-2col')) ;?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_colored_help_card($volunteer) ;?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--item">
				<?php tst_linked_help_card($corporate, (int)$img2, array('size' => 'block-small', 'title' => __('Corporate help', 'tst'), 'button' => __('Become partner', 'tst'))) ;?>
			</div>
		</div>
	</div>
</div>