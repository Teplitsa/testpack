<?php
/**
 * Template Name: Homepage
 * 
 */

 
$home_id = $post->ID;

$f_test = (function_exists('get_field')) ? get_field('home_intro', $home_id) : '';
$f_img_id = (function_exists('get_field')) ? get_field('home_intro_img', $home_id) : '';
$f_img = wp_get_attachment_image_src($f_img_id, 'embed' );

if(have_posts()) :
  // Main News
  $main_news = new WP_Query('posts_per_page=1');
  while ($main_news->have_posts()) :
    $main_news->the_post(); 
    $dnd[] = $post->ID; 
   //  the_excerpt();
  endwhile;
  // Next news
  $next_news = new WP_Query(array(
     //  'category_name' => 'novosti',
      'post__not_in'  =>  $dnd,
      'posts_per_page'  =>  2
  ));

  while ($next_news->have_posts()) :
    $next_news->the_post();
    $next_arr[] = $post->ID;
   //  the_excerpt();
  endwhile;
  // other news

  $other_news = new WP_Query(array(
     //  'category_name' => 'novosti',
      'post__not_in'  =>  array_merge($dnd, $next_arr),
      'posts_per_page'  =>  3
  ));
  
  while ($other_news->have_posts()) :
    $other_news->the_post();
   //  the_excerpt();
  endwhile;
endif;



get_header();
?>
<div class="container">
  <div class="entry-content">
    <?php echo apply_filters('the_content', $f_test); ?>
    <?php if(have_posts()) { ?>
    <section class="home-section">
    	
    	<div class="mdl-grid cases">
        <div class="column news-block">
          <?php
            foreach($main_news->posts as $m){
              rdc_related_post_card($m);
            }
          ?>
        </div>
        <div class="column other-news">
          <?php
            foreach($next_news->posts as $n){
              rdc_related_post_card($n);
            }
          ?>
        </div>
    	</div>
    	<div class="mdl-grid cases">
        <div class="column other-news-block">
          <?php
            foreach($other_news->posts as $o){
              rdc_related_post_card($o);
            }
          ?>
        </div>
    	</div>
    </section>
    <?php } ?>
  </div>
</div>
<!-- <section class="home-section intro">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--8-col">
			
			<div class="mdl-card mdl-shadow--2dp">
				<div class="mdl-card--expand">
					<div class="featured_text">
						<?php echo apply_filters('tstpsk_the_content', $f_test);?>
						<div class="sign">
							<svg class="logo pic-logo-pink">
								<use xlink:href="#pic-logo-pink" />
							</svg>
						</div>
					</div>
				</div>
				
				<div class="mdl-card__actions ">
					<a class="mdl-button mdl-js-button mdl-button--colored" href="<?php echo home_url('about');?>">Подробнее</a>
				</div>		
				
			</div><!-- .card -->
				
		<!--/div>
		
		<div class="mdl-cell mdl-cell--4-col mdl-cell--hide-phone mdl-cell--hide-tablet"><!--?php get_sidebar(); ?--><!--/div>
	</div>
</section> -->





<?php get_footer(); ?>
