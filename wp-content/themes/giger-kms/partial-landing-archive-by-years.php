<?php
/**
 * The template for landings with publications
 *
 * @package bb
 */


//var_dump($wp_query->posts);
$cpost = get_queried_object();
$is_archive = get_query_var('item_archive');

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

<article class="single">

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

            <div class="flex-cell--stacked lg-9 single-body">

                <div class="single-body--entry">
                
                    <?php
                    $years = tst_get_past_years( 20 );
                    
                    foreach( $years as $year ) {
                        $posts = call_user_func( $tst_callback_get_latest_posts, $year );
                        if( !empty( $posts ) ) {
                        ?>
                            <div class="projects-block container">
                                <h3 class="projects-block__title"><?php echo $year; ?></h3>
                                
                                <div class="projects-block__content">
                                    <div class="projects-block__icon hide-upto-medium"><?php tst_svg_icon('icon-pdf');?></div>
                    
                                    <div class="projects-block__list">
                                        <ul>
                                        <?php foreach($posts as $p) { ?>
                                            <li><a href="<?php echo wp_get_attachment_url( $p->ID );?>"><?php echo get_the_title($p);?></a></li>
                                        <?php }    ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }?>
                    
                </div>

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
    <section>
        <header class="landing-header">
        	<div class="cover-general__title container">
        		<h1 class="landing-header__title"><?php echo get_the_title($cpost);?></h1>
        		<div class="landing-header__tagline"><?php echo apply_filters('tst_the_title', get_post_meta($cpost->ID, 'landing_excerpt', true));?></div>
        		<div class="landing-header__links">
        			<a href="<?php echo trailingslashit(get_permalink($cpost)).'archive';?>"><?php _e('View archive', 'tst');?></a>
        			<a href="#help-block" class="local-scroll"><?php _e('Join us', 'tst');?></a>
        		</div>
        	</div>
        </header>    
    </section>
    
    <?php
    $main_pub_cols = 3;
    
    $landing_main_posts = isset( $tst_callback_get_main_posts ) && $tst_callback_get_main_posts ? call_user_func( $tst_callback_get_main_posts, $cpost ) : array();
    $main_pub_sections = range( 0, ceil( count( $landing_main_posts ) / $main_pub_cols ) );
    TST_Color_Schemes::get_instance()->build_named_full_scheme( $cpost->ID, $cpost->post_type . '-' . $cpost->post_name . '-main-items', $main_pub_sections );
    
    if( !empty( $landing_main_posts ) ):
    ?>
    <div class="news-block container">
		<div class="news-block__content publications-block__content">
    <?php 
    foreach( $main_pub_sections as $section ) {
        $grid_css = TST_Color_Schemes::get_instance()->get_named_full_scheme( $cpost->ID, $cpost->post_type . '-' . $cpost->post_name . '-main-items', $section );
        
        if( empty( $main_pub_sections ) ) {
            break;
        }
        
        ?>
        <div class="flex-grid--stacked <?php echo $grid_css;?>">
        <?php
        for( $i = 0; $i < $main_pub_cols; $i += 1 ) {
            $pub_post_id = array_shift( $landing_main_posts );
            if( !$pub_post_id ) {
                break;
            }
            ?>
            <div class="flex-cell--stacked sm-6 lg-4 card card--colored">
            	<?php tst_card_colored( $pub_post_id ) ;?>
            </div>
            <?php 
        }
        
        if( empty( $main_pub_sections ) ) {
            break;
        } 
        
        ?>
        </div>
        <?php 
    }
    ?>
    	</div>
	</div>
    
    <?php
    endif;
    
    
    $years = tst_get_past_years( 20 );
    TST_Color_Schemes::get_instance()->build_named_full_scheme( $cpost->ID, $cpost->post_type . '-' . $cpost->post_name . '-archive', $years );
    $full_years = 0;
    
    foreach( $years as $year ) {
        if( $full_years >= 2) {
            break;
        }
        
        $grid_css = TST_Color_Schemes::get_instance()->get_named_full_scheme( $cpost->ID, $cpost->post_type . '-' . $cpost->post_name . '-archive', $year );
        $posts = call_user_func( $tst_callback_get_latest_posts, $year, 4 );
        if( !empty( $posts ) ) {
            $full_years += 1;
        ?>
            <div class="news-block container">
                <h3 class="news-block__title"><?php echo $year; ?></h3>
                <div class="news-block__content publications-block__content">
                    <div class="flex-grid--stacked <?php echo $grid_css;?>">
                    <?php foreach($posts as $i => $pup_post) { ?>
                		<div class="flex-cell--stacked sm-6 lg-3 card card--colored">
                			<?php tst_card_colored( $pup_post ) ;?>
                		</div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }?>
    
    <?php wds_page_builder_area( 'page_builder_default' );?>
    <section class="cta"><?php wds_page_builder_area('cta'); ?></section>
    
</article>

<?php }

get_footer();