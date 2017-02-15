<?php
/**
 * Events Template
 **/

global $wp_query;

$loop_1 = $wp_query->posts;
$found = $wp_query->found_posts;
$dnd = array();

get_header();?>

    <div class="frame">

        <section class="main-content bit lg-9 shift-left">
            <div class="layout-section layout-section--full">

                <div class="layout-section__rail-title events-states">
                    <div class="frame">

                        <div class="bit md-9 lg-10 mf-12">
                            <div class="events-states--current-rail">
                            </div>
                        </div>
                        <div class="bit md-3 lg-2 hide-upto-medium">
                            <div class="events-states--found">
                                <span class="events-states--found-mark"><?php echo sprintf(__('Total: %d', 'tst'), $found );?></span>
                            </div>
                        </div>
                    </div><!-- .frame -->

                </div>

                <div class="layout-section__content layout-section__content--card">
                    <?php if($loop_1) {?>

                        <?php foreach($loop_1 as $i => $cpost) { ?>
                            <?php $border_css = ($i == (count($loop_1) - 1)) ? 'border--regular-last' : 'border--regular'; ?>
                            <div class="layout-section__item <?php echo $border_css;?>">
                                <?php tst_card_event($cpost); ?>
                            </div>
                        <?php } ?>

                        <?php $target = uniqid('loadmore-'); //container for posts ?>
                        <div id="<?php echo esc_attr($target);?>" class="loadmore-container"></div>

                    <?php } else { ?>

                        <p><?php _e('Unfortunately, nothing found under your request.', 'tst');?></p>

                    <?php }?>
                </div>

                <?php if($loop_1 /*&& !empty($wp_query->query_vars['paged'])*/) {
                    tst_load_more_button($wp_query, 'events_card', $dnd, $target);
                }?>

            </div>

        </section>

    </div>

    <section class="bottom-content hide-on-large">

        <div class="hide-on-large border--regular events-side-content-fixer">
            <a href="<?php echo $fp_btn_url;?>" class="button--fullwidth"><?php echo $fp_btn_label; ?></a>
        </div>

        <?php if($banner_bottom) { ?>
            <div class="widget"><?php echo $banner_bottom;?></div>
        <?php }?>

        <div class="side-footer"><?php tst_cached_fragment('side_footer');?></div><!-- .side-footer -->
    </section>

<?php get_footer();