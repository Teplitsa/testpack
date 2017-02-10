<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object();


get_header();?>

    <article class="single-card">
        <div class="single-card__header">
            <div class="single-card__title"><h1><?php echo get_the_title($cpost);?></h1></div>
            <div class="single-card__options">
                <div class="single-card__meta"><meta><?php echo tst_single_post_meta($cpost);?></div>
                <div class="sharing"><?php tst_social_share($cpost);?></div>
            </div>
        </div>

        <div class="single-card__content">

            <div class="frame">

                <div class="bit md-12 single-body">
                    <?php wds_page_builder_area( 'page_builder_default' );?>
                </div>
                
            </div>
        </div><!-- .frame .single-card__content -->
        
        <div class="single-body__footer single-body__footer-desktop"><?php tst_single_post_nav();?></div>
    </article>

<?php
get_footer();