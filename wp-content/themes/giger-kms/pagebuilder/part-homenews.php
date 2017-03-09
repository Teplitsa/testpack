<?php
/**
 * Part Name: Новости для главной
 * Description: Тройной блок связанных элементов - картинка, плашка, карточка
 */

$prefix = "homenews_";



$el1 = wds_page_builder_get_this_part_data($prefix.'element1_file_id'); //correct field id
$el2 = wds_page_builder_get_this_part_data($prefix.'element2_file_id'); //correct field id


//news
$all_link = ''; //should we have it ?

$news_args = array(
	'post_type' => 'post',
	'posts_per_page' => 4
);

//detect dnd
$home_dnd = array();
$sec = get_post_meta(get_queried_object_id(), '_wds_builder_template', true);
//echo "<pre>";var_dump($sec);echo "</pre>";
if(!empty($sec)){
	foreach($sec as $i => $obj){
		if(isset($obj['template_group']) && $obj['template_group'] == 'tripleblock-picture'){
			$home_dnd[] = (int)$sec[$i]['tripleblock_picture_element1_post'];
			$home_dnd[] = (int)$sec[$i]['tripleblock_picture_element2_post'];
			$home_dnd[] = (int)$sec[$i]['tripleblock_picture_element3_post'];
		}
	}
}

if(isset($home_dnd) && !empty($home_dnd)) {
	$news_args['post__not_in'] = $home_dnd;
}

$news = get_posts($news_args);

?>
<div class="homenews-block container">
	<h3 class="homenews-block__title">
		<?php if(!empty($all_link)) { ?><a href="<?php echo $all_link;?>"><?php } ?>
			<?php _e('News and Publishings', 'tst'); ?>
		<?php if(!empty($all_link)) { ?></a><?php } ?>
	</h3>
	<div class="homenews-block__content">
		<div class="flex-grid--stacked scheme-color-1-leaf scheme-color-2-moss">
			<?php if(isset($news[0])) { ?>
				<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
					<?php tst_card_linked($news[0], array('size' => 'block-2col')) ;?>
				</div>
			<?php } ?>
			<?php if(isset($news[1])) { ?>
				<div class="flex-cell--stacked sm-6 lg-3 card card--colored card--news">
					<?php tst_news_card($news[1], 'colored'); ?>
				</div>
			<?php } ?>
			<?php if(isset($news[2])) { ?>
				<div class="flex-cell--stacked sm-6 lg-3 card card--item card--news">
					<?php tst_card_linked($news[2], array('size' => 'block-1col', 'show_desc' => true)); ?>
				</div>
			<?php } ?>
		</div>

		<div class="flex-grid--stacked row-reverse scheme-color-1-wetsoil scheme-color-2-loam">
			<?php if(isset($news[2])) { ?>
				<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
					<?php tst_card_linked($news[3], array('size' => 'block-2col')) ;?>
				</div>
			<?php } ?>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_card_colored((int)$el1) ;?>
			</div>
			<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
				<?php tst_card_colored((int)$el2) ;?>
			</div>
		</div>
	</div>
</div>
