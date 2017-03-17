<?php
/**
 * Archive list template
 **/

$loop_1 = $wp_query->posts;

get_header();
?>

<div class="single-crumb container">
	<div class="search-block__title"><?php _e('Archive', 'tst');?></div>
</div>

<article class="single search archive-list">
    <header class="single__header">
        <div class="container">
            <div class="flex-grid--stacked">
                <div class="flex-cell--stacked lg-12 single__title-block">
                    <div class="search__meta archive-meta"><?php _e('Articles and documents fromt previous versions of out site', 'tst');?></div>
    			</div>
            </div>
        </div>
    </header>

    <div class="single__content">
        <div class="container">
			<div class="flex-grid--stacked">
                <div class="flex-cell--stacked lg-12 single-body">

            <div class="archive-list" id="loadmore-archive-import">
			<?php
				if(empty($loop_1)) {
				   echo "<div class='search__hint'><p>".__('Unfortunately, nothing found under your request.', 'tst')."</p></div>";
				}
				else {
					foreach($loop_1 as $i => $cpost) {
			?>
				<div class="layout-section__item"><?php tst_card_archive($cpost); ?></div>
			<?php }}?>
            </div>
            
    		<div class="layout-section--loadmore">
    		<?php
    			if(isset($wp_query->query_vars['has_next_page']) && $wp_query->query_vars['has_next_page']) {
    				tst_load_more_button($wp_query, 'archive-import', array(), "loadmore-archive-import");
    			}
    		?>
    		</div>
            

			<div></div>
        </div>
    </div>

</article>

 <?php get_footer(); ?>
