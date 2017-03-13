<?php
/** Error **/

$er_text = get_option('er_text');
$src = get_template_directory_uri().'/assets/img/er404.jpg';
get_header();
?>

<div class="single-crumb container">
	<div class="search-block__title">
	    <?php _e('404: Page not found', 'tst');?>
	</div>
</div>
<article class="single search">
    <header class="single__header">
        <div class="container">            
            <div class="flex-grid--stacked">
                <div class="flex-cell--stacked lg-12 single__title-block">
                    <div class="search__regular"><?php get_search_form();?></div>
    			</div>                
            </div>            
        </div>
    </header>
	
	<div class="single__content">
        <div class="container">
            <div class="flex-grid--stacked">
                
                <div class="flex-cell--stacked lg-12 single-body">
                    <div class="search__hint"><p><?php echo apply_filters('tst_the_content', $er_text); ?></p></div>
                </div>
                
            </div>
        </div>
    </div>
	
</article>

<?php get_footer(); ?>