<?php
/**
 * General section templates
 **/


$section = get_queried_object();
$posts = $wp_query->posts;

$cover = $posts[0];
$posts = array_slice($posts, 1);

get_header();
?>
<article class="landing landing--section">
	<section class="section-intro">
	<?php
		$cover_id = get_post_thumbnail_id($cover);
		$cover_url = wp_get_attachment_url($cover_id, 'full');

		$cover_desc = get_post_meta($cover->ID, 'landing_excerpt', true);
		if(empty($cover_desc)){
			$cover_desc = tst_get_post_excerpt($cover, 10, true);
		}
	?>
		<header class="landing-header landing-header--section">
			<div class="cover-general__title container">
				<h1 class="landing-header__title"><?php echo apply_filters( 'single_term_title', $section->name );?></h1>
				<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', $section->description);?></div>
			</div>

			<?php if($cover) { ?>
			<div class="cover-general__item">
				<div class="cover-item__bg" style="background-image: url(<?php echo $cover_url;?>);"></div>
				<div class="container">
					<a href="<?php echo get_permalink($cover);?>" class="cover-item__link">
						<h4><?php echo get_the_title($cover);?></h4>
						<!-- meta -->
						<?php if(!empty($cover_desc)) {?>
							<div class="card__insummary"><?php echo apply_filters('tst_the_title', $cover_desc);?></div>
						<?php } ?>
					</a>
				</div>
			</div>
			<?php } ?>
		</header>

	</section>

	<!-- main blocks -->
	<section class="section-loop">
		<div class="container">
	<?php
		if(!empty($posts)) {
			//distibute items by steps and rows
			$steps = array();
			for($i=0; $i < ceil(count($posts) / 6); $i++){
				$steps[$i] = array_slice($posts, $i*6, 6);
			}

			foreach($steps as $i => $step) {
				$count = count($step);

				$row_1_data = array();
				$row_2_data = array();
				$row_3_data = array();

				switch($count) {

					case 1:
						$row_3_data = $step;
						break;

					case 2:
						$row_1_data = $step;
						break;

					case 3:
						$row_2_data = $step;
						break;

					case 4:
						$row_2_data = array_slice($step, 0, 3);
						$row_3_data = array_slice($step, 3);
						break;

					case 5:
						$row_1_data = array_slice($step, 0, 2);
						$row_2_data = array_slice($step, 2);
						break;

					case 6:
						$row_1_data = array_slice($step, 0, 2);
						$row_2_data = array_slice($step, 2, 3);
						$row_3_data = array_slice($step, 5);
						break;
				}

				if(!empty($row_1_data)) {
			?>
				<div class="doubleblock-picture section-row-1">
					<div class="flex-grid--stacked scheme-color-1-moss">
						<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
							<?php tst_card_linked($row_1_data[0], array('size' => 'block-2col')) ;?>
						</div>
						<div class="flex-cell--stacked sm-12 lg-6 card card--text block-2col">
							<div class="flex-column-centered"><?php tst_card_text($row_1_data[1]); ?></div>
						</div>
					</div>
				</div>
			<?php
				}
				if(!empty($row_2_data)) {
			?>
				<div class="tripleblock-2cards section-row-2">
					<div class="flex-grid--stacked row-reverse scheme-color-1-bark scheme-color-2-leaf">
						<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
							<?php tst_card_linked($row_2_data[0], array('size' => 'block-2col')) ;?>
						</div>
						<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
							<?php tst_card_colored($row_2_data[1]) ;?>
						</div>
						<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
							<?php tst_card_colored($row_2_data[2]) ;?>
						</div>
					</div>
				</div>
			<?php
				}
				if(!empty($row_3_data)) {
			?>
				<div class="singleblock-picture section-row-3"><div class="scheme-color-1-ground">
					<div class="card card--linked block-full label-left_top ">
						<?php tst_card_linked($row_3_data[0], array('size' => 'block-full')) ;?>
					</div>
				</div></div>
			<?php
				}

			} //foreach steps


		}//endif posts

	?>
		</div>
	</section>

	<!-- cta footer -->
	<section class="section-cta">

		<?php
			$news_all_link = home_url('news/');
			$news_grid_css = 'scheme-color-1-leaf scheme-color-2-wetsoil';

			$news_args = array( //should we relate them somehow?
				'post_type' => 'post',
				'posts_per_page' => 4
			);

			$news = get_posts($news_args);
		?>
		<div class="news-block container">
			<h3 class="news-block__title">
				<?php if(!empty($news_all_link)) { ?><a href="<?php echo $news_all_link;?>"><?php } ?>
					<?php _e('News', 'tst'); ?>
				<?php if(!empty($news_all_link)) { ?></a><?php } ?>
			</h3>
			<div class="news-block__content">
				<div class="flex-grid--stacked <?php echo $news_grid_css;?>">
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

		<?php if($section->slug != 'supportus') { ?>
		<?php
			//get real IDs of pages
			$support = get_page_by_path('donate', 'OBJECT', 'leyka_campaign');
			$volunteer = get_page_by_path('volunteer');
			$corporate = get_page_by_path('corporate');

			$help_grid_css = 'scheme-color-pollen';
		?>
		<div id="help-block" class="help-block container">
			<h3 class="help-block__title"><?php _e('Support us', 'tst'); ?></h3>

			<div class="help-block__content">
				<div class="flex-grid--stacked <?php echo $help_grid_css;?>">
					<div class="flex-cell--stacked sm-12 lg-6 card card--linked block-2col">
						<?php tst_linked_help_card($support, 0, array('size' => 'block-2col')) ;?>
					</div>
					<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
						<?php tst_colored_help_card($volunteer) ;?>
					</div>
					<div class="flex-cell--stacked sm-6 lg-3 card card--item">
						<?php tst_linked_help_card($corporate, 0, array('size' => 'block-small', 'title' => __('Corporate help', 'tst'), 'button' => __('Become partner', 'tst'))) ;?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</section>
</article>
<?php get_footer();
