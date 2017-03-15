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

<div class="single-crumb container">
	<div class="search-block__title">
	    Результаты поиска
	</div>
</div>
<article class="single search">
    <header class="single__header">
        <div class="container">
            <div class="flex-grid--stacked">
                <div class="flex-cell--stacked lg-12 single__title-block">
                    <div class="search__regular"><?php get_search_form();?></div>
                    <div class="search__meta single-card__meta"><?php echo tst_build_results_label($num);?></div>
    			</div>
            </div>
        </div>
    </header>

    <div class="single__content">
        <div class="container">
            <div class="flex-grid--stacked">

                <div class="flex-cell--stacked lg-12 single-body">
                    <?php
            			if(empty($s_query)){
            				echo "<div class='search__hint'><p>Пожалуйста, укажите слова для поиска в форме и нажмите Enter.</p></div>";
            			}
            			elseif($num == 0) {
            				echo "<div class='search__hint'><p>".__('Unfortunately, nothing found under your request.', 'tst')."</p></div>";
            			}
        			?>
                    <?php if(!empty($s_query)) {
                    ?>
                    <div id="loadmore-search-results">
                        <?php
                        foreach($loop_1 as $i => $cpost) {
                            ?>
                            <div class="layout-section__item layout-section__item--card">
                                <?php tst_card_search($cpost); ?>
                            </div>
                        <?php }
                    }
                    ?>
                    </div>
                    
            		<div class="layout-section--loadmore">
            		<?php
            			if(isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
            				tst_load_more_button($wp_query, 'search_card', array(), "loadmore-search-results");
            			}
            		?>
            		</div>
                    
                </div>

            </div>
        </div>
    </div>

</article>

 <?php get_footer(); ?>
