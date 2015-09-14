<?php
/**
 * Template Name: Cards
 */

get_header();?>

	
<section class="cards-gallery">
<div class="mdl-grid">

<?php $cards_categories_number = get_field_object('object_categories', get_the_ID());
$cards_categories_number = count($cards_categories_number['value']);

if(function_exists('have_rows') && have_rows('object_categories')) {

    while(have_rows('object_categories')) { the_row();

        if($cards_categories_number > 1) {?>
            <h3><?php the_sub_field('category_name');?></h3>
        <?php }

        if(have_rows('objects')) { while(have_rows('objects')) { the_row();

            $name = get_sub_field('name');
            $url = esc_url(get_sub_field('url'));
            $pic = wp_get_attachment_image(get_sub_field('pic'), 'full', false, array('alt' => $name));
            $text = get_sub_field('short_descr');?>

            <div class="mdl-cell mdl-cell--4-col mdl-cell--3-col-desktop">
                <div class="mdl-card mdl-shadow--2dp tpl-partner">
                    <div class="mdl-card__media">
                        <?php if($url && $pic) {?>
                            <a class="logo-link" title="<?php echo esc_attr($name);?>" href="<?php echo $url;?>"><?php echo $pic;?></a>
                        <?php } else if($pic) {?>
                            <span class="logo-link" title="<?php echo esc_attr($name);?>"><?php echo $pic;?></span>
                        <?php }?>
                    </div>

                    <div class="mdl-card__title">
                        <h4 class="mdl-card__title-text">
                            <?php echo apply_filters(
                                'tst_the_title',
                                $url ? "<a class='name-link' href='$url' title='$name'>$name</a>" : $name
                            );?>
                        </h4>
                    </div>

                    <div class="mdl-card__supporting-text mdl-card--expand"><?php echo apply_filters('tst_the_title', $text);?></div>

                    <div class="mdl-card__actions mdl-card--border">
                        <?php if($url) {?>
                            <a class="mdl-button mdl-js-button mdl-button--primary" href="<?php echo $url;?>">Веб-сайт</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }
        }
    }
}?>
</div>
</section>	

<div class="page-content-grid">
<div class="mdl-grid">
	
	<div class="mdl-cell mdl-cell--3-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
		<?php		
			while(have_posts()){
				the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('tpl-page'); ?>>
				<div class="entry-content">
					<?php the_content(); ?>		
				</div>				
			</article>
		<?php } ?>
		
		
	</div>
	
	<div class="mdl-cell mdl-cell--3-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	
</div><!-- .mdl-grid -->

</div>

<div class="page-footer"><div class="mdl-grid">
	<div class="mdl-cell mdl-cell--3-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet"><?php get_sidebar(); ?></div>
	<div class="mdl-cell mdl-cell--3-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
</div></div>

<?php get_footer(); ?>
