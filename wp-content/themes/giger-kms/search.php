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

<?php get_footer(); ?>

