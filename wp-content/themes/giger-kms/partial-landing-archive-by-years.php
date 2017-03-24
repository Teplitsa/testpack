<?php
/**
 * The template for landings with publications
 *
 * @package bb
 */


//var_dump($wp_query->posts);
$cpost = get_queried_object();
$is_archive = get_query_var('item_archive');
$landing_excerpt = get_post_meta( $cpost->ID, 'landing_excerpt', true );

get_header();?>
<?php
    if($is_archive && $is_archive == 1)  { //print about page markup
        //$cpost = $wp_query->posts[0]; var_dump($cpost)

    $section = get_the_terms($cpost, 'section');
?>
<div class="single-crumb container">
    <?php if($section) { ?>
        <a href="<?php echo get_term_link($section[0]->term_id);?>"><?php echo apply_filters('tst_the_title', $section[0]->name); ?></a>
    <?php } ?>
</div>

<article class="single archive-landing-<?php echo $cpost->post_name?>">

    <header class="single__header single__header--smaller">
        <div class="container">
            <div class="flex-grid--stacked">
                <div class="flex-cell--stacked md-9 single__title-block">
                    <h1><?php printf(__('%s: What are we doing', 'tst'), get_the_title($cpost));?></h1>
                    <div class="sharing"><?php tst_social_share($cpost);?></div>
                </div>

                <div class="flex-cell--stacked md-3 single__nav--back">
                        <?php tst_card_iconic($cpost); ?>
                </div>

            </div>
        </div>
    </header>
    
    <div class="single__content"><div class="container">
        <div class="flex-grid--stacked">

            <div class="lg-9 single-body">
            
                <?php if( isset( $tst_callback_get_sticky_posts ) ):
                	$sticky_posts = call_user_func( $tst_callback_get_sticky_posts );
                	if( !empty( $sticky_posts ) ):
            	?>
            
                <div class="projects-block container attachments-archive-list">
                    <div class="projects-block__content">
                        <div class="projects-block__icon hide-upto-medium"><?php tst_svg_icon('icon-pdf');?></div>
                        <div class="projects-block__list">
                            <ul>
                            <?php foreach($sticky_posts as $p) { ?>
                                <li><?php echo tst_get_special_attachment_link( $p )?></li>
                            <?php }    ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
            	<?
            	    endif;
                endif; 
                 
                ?>
                
                
                <?php if( $landing_excerpt ):?>
                	<div class="single-body">
                		<div class="single-body--entry">
                			<?php echo apply_filters('tst_the_content', $landing_excerpt );?>
                		</div>
                	</div>
                <?php endif;?>
                

                <?php
                $years = tst_get_past_years( 200 );

                foreach( $years as $year ) {
                    
                    $posts = call_user_func( $tst_callback_get_latest_posts, $year );
                    
                    if( !empty( $posts ) ) {
                    ?>
                        <div class="projects-block container attachments-archive-list">
                            <h3 class="projects-block__title"><?php echo $year; ?></h3>

                            <div class="projects-block__content">
                                <div class="projects-block__icon hide-upto-medium"><?php tst_svg_icon('icon-pdf');?></div>

                                <div class="projects-block__list">
                                    <ul>
                                    <?php foreach($posts as $p) { ?>
                                        <li><?php echo tst_get_special_attachment_link( $p )?></li>
                                    <?php }    ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php }?>

            </div>

            <div class="flex-cell--stacked lg-3 single-sidenews">
                <?php
                    $news = tst_get_latest_news(1);
                    if(!empty($news)) {
                        tst_news_apart_card($news[0]);
                    }
                ?>
            </div>
        </div>
    </div></div><!-- .single__content -->

    <section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>



<?php }  else { ?>

<article class="landing dront-publication">
    <?php wds_page_builder_area( 'page_builder_default' );?>
    <section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>

<?php }

get_footer();