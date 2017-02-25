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

<!--                    --><?php //if(has_post_thumbnail($cpost)) { ?>
<!--                        <div class="single-body__preview">--><?php //tst_single_thumbnail($cpost);?><!--</div>-->
<!--                    --><?php //} ?>


                    <div class="single-body--entry">
<!--                        --><?php //echo apply_filters('tst_entry_the_content', $cpost->post_content);?>
                        <?php
                            $included_groups = get_terms(array(
                                'taxonomy' => 'marker_cat',
                                'hide_empty' => false,
                                'name' => array('Проблемы', 'Решенные проблемы'),
                                'fields' => 'ids',
                            ));
                            echo do_shortcode('[tst_markers_map groups_ids="'.implode(',', $included_groups).'" enable_scroll_wheel="0"]');?>
                        <div class="marker-submit-form" style="display: none">
                            <?php echo do_shortcode('[formidable id=5]');?>
                        </div>
                    </div>
                    <div class="single-body__footer single-body__footer-mobile"><?php tst_single_post_nav();?></div>
                </div>

                <div class="bit md-12 single-aside">
                    <?php
                    $related = tst_get_related_query($cpost, 'post_tag', 4);
                    if(!empty($related)) {
                        ?>
                        <div class="widget">
                            <div class="widget__title"><?php _e('More news', 'tst');?></div>
                            <div class="widget__content"><?php tst_related_list($related->posts); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div></div><!-- .frame .single-card__content -->
        <div class="single-body__footer single-body__footer-desktop"><?php tst_single_post_nav();?></div>
    </article>

<?php
get_footer();