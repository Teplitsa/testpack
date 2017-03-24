<?php
/**
 * The template for landings
 *
 * @package bb
 */


/** @var WP_Post $post */
$post = get_queried_object();

get_header();?>

<div class="single-crumb container">
	<?php if(isset($section)) { ?>
		<a href="<?php echo get_term_link($section[0]->term_id);?>"><?php echo apply_filters('tst_the_title', $section[0]->name); ?></a>
	<?php }?>
</div>

<article class="landing">

    <header class="landing-header">

        <div class="cover-general__title container">
            <h1 class="landing-header__title"><?php echo get_the_title($post);?></h1>
            <div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', get_post_meta($post->ID, 'landing_excerpt', true));?></div>
            <div class="landing-header__links">
                <a href="<?php echo tst_current_url().'about/';?>" class="text-link"><?php _e('Get details', 'tst');?></a>
                <a href="#marker-submit-form-wrapper" class="local-scroll button-link"><?php _e('Submit a problem!', 'tst');?></a>
            </div>
        </div>

    </header>

    <div class="singleblock-map pagebuilder-part">
        <div class="container scheme-color-1-ground">
            <div id="ecoproblems-map">
                <?php $included_groups = get_terms(array(
                    'taxonomy' => 'marker_cat',
                    'hide_empty' => false,
                    'name' => array('Проблемы', 'Решенные проблемы'),
                    'fields' => 'ids',
                ));
                echo do_shortcode('[tst_markers_map groups_ids="'.implode(',', $included_groups).'" enable_scroll_wheel="0"]');?>
            </div>
        </div>
    </div>

    <div class="singleblock-map pagebuilder-part">
        <div class="container scheme-color-1-ground">
            <div id="marker-submit-form-wrapper">
                <?php echo do_shortcode('[formidable id="'.get_option('ecoproblem_submission_form_id').'" title="true" description="true"]');?>
            </div>
        </div>
    </div>

	<section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>

<?php get_footer();