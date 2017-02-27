<?php
/**
 * Part Name: Новости
 * Description: Тройной блок связанных элементов - картинка, плашка, карточка
 */

$prefix = "news_";

$all_link = wds_page_builder_get_this_part_data($prefix.'all_link'); //all link

//to-do: get relevant news
$news = get_posts(array(
	'post_type' => 'post',
	'posts_per_page' => 4
));

//add grid class
$grid_css =  tst_get_colors_for_section();

//colorschemes
?>
<div class="news-block container">
	<h3 class="news-block__title">
		<?php if(!empty($all_link)) { ?><a href="<?php echo $all_link;?>"><?php } ?>
			<?php _e('News', 'tst'); ?>
		<?php if(!empty($all_link)) { ?></a><?php } ?>
	</h3>
	<div class="news-block__content">
		<div class="flex-grid--stacked <?php echo $grid_css;?>">
		<?php foreach($news as $i => $n) { ?>
			<?php if($i %2 > 0) { ?>
				<div class="flex-cell--stacked sm-6 lg-3 card card--colored card--news">
					<?php tst_news_card($n, 'colored'); ?>
			<?php } else { ?>
				<div class="flex-cell--stacked sm-6 lg-3 card card--item card--news">
					<?php tst_news_card($n, 'pictured'); ?>
			<?php } ?>
				</div>
		<?php } ?>
		</div>
	</div>
</div>
