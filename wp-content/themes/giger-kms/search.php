<?php
/**
 * Search tempalte
 **/

$s_query = get_search_query();
$num = 0;
$loop_1 = $wp_query->posts;

if(!empty($s_query) && $wp_query->found_posts > 0){
	$num = (int)$wp_query->found_posts;
}


//build correct label for results
function tst_build_results_label($number){
	
	$label = "Найдено %d страниц";	
	$test = $number % 10;
	
	if($test == 1)
		$label = "Найдена %d страница";
	elseif($test > 1 && $test < 5)
		$label = "Найдено %d страницы";
	
	//11 case		
	if($number % 100 >= 11 &&  $number % 100 <= 19){
		$label = "Найдено %d страниц";
	}
	
	return sprintf($label, $number);

}
 
get_header();
?>
<!-- <section class="top-content">
	<div class="top-content__middle top-content__middle--padded">
		<header class="single-card__header">
			<div class="single-card__title">
				<h1 class="err-404"><!?php _e('404: Page not found', 'tst');?></h1>
			</div>
		</header>
	</div>
</section> -->



<div class="spacer"></div>
<section class="main-content">
	<div class="layout-section layout-section--full">
		<div class="layout-section__content">

			<article class="sr-block">				
				<div class="sr-form regular-search"><?php get_search_form();?></div>
				<div class="sr-num"><?php echo tst_build_results_label($num);?></div>
			</article>
			<div class="layout-section layout-section--card">
				<?php
					if(empty($s_query)){
						echo "<div class='layout-section__item layout-section__item--card'><p>".__('Пожалуйста, укажите слова для поиска в форме и нажмите Enter.', 'tst')."</p></div>";
					}
					elseif($num == 0) {
						echo "<div class='layout-section__item layout-section__item--card'><p>".__('К сожалению, по вашему запросу ничего не найдено. Попробуйте сформулировать иначе.', 'tst')."</p></div>";
					}
					else {
						foreach($loop_1 as $i => $cpost) {
							$class = ($i == (count($loop_1) - 1)) ? 'layout-section__item--card' : 'layout-section__item--card';
				?>
						<div class="layout-section__item <?php echo $class;?>">
							<?php tst_card_search($cpost); ?>
						</div>
				<?php }} ?>


				<?php
					if(!empty($s_query) && $num > 0 && isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
						$more_template = 'search_card';
						tst_load_more_button($wp_query, $more_template);
					}
				?>

			</div>
		</div>
	</div>
</section>






<!-- <section class="heading">
	<div class="container">
		<!--?php tst_section_title(); ?-->
		<!--div id="sr_form" class="sr-form"><!?php get_search_form();?></div>
		<div class="sr-num"><!?php echo tst_build_results_label($num);?></div>
	</div>
</section> -->
<!-- <section class="main-content bit lg-8">
	<div class="layout-section layout-section--full">
		<div class="layout-secton__content">
		<!?php
			if(empty($s_query)){
				echo "<p>".__('Пожалуйста, укажите слова для поиска в форме и нажмите Enter.', 'tst')."</p>";
			}
			elseif($num == 0) {
				echo "<p>".__('К сожалению, по вашему запросу ничего не найдено. Попробуйте сформулировать иначе.', 'tst')."</p>";
			}
			else {
				foreach($loop_1 as $i => $cpost) {
					$class = ($i == (count($loop_1) - 1)) ? 'border--regular-last' : 'border--regular';
		?>
				<div class="layout-section__item <!?php echo $class;?>">
					<!?php tst_card_search($cpost); ?>
				</div>
		<!?php }} ?>


		<!?php
			if(!empty($s_query) && $num > 0 && isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
				$more_template = 'search_card';
				tst_load_more_button($wp_query, $more_template);
			}
		?>

		</div>
	</div>
</section> -->


<?php get_footer(); ?>
<!-- <section class="heading">
	<div class="container">
		<!?php tst_section_title(); ?>
		<div id="sr_form" class="sr-form"><!?php get_search_form();?></div>
		<div class="sr-num"><!?php echo tst_build_results_label($num);?></div>
	</div>
</section>

<section class="main-content search-results <!?php if(!$num || !get_search_query()):?>service-message<!?php endif?>"><div class="container">
	<!?php
		if(empty($s_query)){
			$l = __('Enter terms for search in the form and hit Enter', 'tst');
			echo "<article class='tpl-search'><div class='entry-summary'><p>{$l }</p></div></article>";							
		}
		elseif($num == 0){
			$l = __('Nothing found under your request', 'tst');
			echo "<article class='tpl-search'><div class='entry-summary'><p>{$l}</p></div></article>";				
		}
		else {
			foreach($wp_query->posts as $sp){
				tst_search_card($sp);
			}
		}
	?>
</section>
<section class="paging"><div class="container">
    <!?php tst_paging_nav($wp_query); ?>
</div></section> -->

