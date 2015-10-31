<?php
/**
 * Template Name: Homepage
 * 
 */
 
$home_id = $post->ID;
$more_link = home_url('about/contacts');

//news
$f_children =new WP_Query(array(
	'post_type' => 'children',	
	'posts_per_page' => 3,
	'orderby' => 'rand',
	'tax_query' => array(
		array(
			'taxonomy' => 'children_status',
			'field' => 'slug',
			'terms' => 'need-help'
		)
	)
));

//news
$f_post = new WP_Query(array(
	'post_type' => 'post',
	'posts_per_page' => 3,
	'cache_results' => false
));

//progs
$progs = new WP_Query(array(
	'post_type' => 'project',
	'posts_per_page' => 3,
	'orderby' => 'radn',
	'cache_results' => false
	
));

//var_dump($progs->get('query_thumbnails'));
get_header();
?>
<?php if(!empty($f_children->posts)) { ?>
<section class="home-section children">
	
	<div class="mdl-grid">	
		<?php foreach($f_children->get_posts() as $chp) {
			tst_children_card($chp);
		}?>
	</div>
</section>
<?php }?>

<section class="home-section help-info">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--2-col mdl-cell--1-col-tablet mdl-cell--hide-phone"></div>
		<div class="mdl-cell mdl-cell--8-col"><?php get_sidebar();?></div>
		<div class="mdl-cell mdl-cell--2-col mdl-cell--1-col-tablet mdl-cell--hide-phone"></div>
	</div>
</section>

<?php if(!empty($progs->posts)) { ?>
<section class="home-section programms">
	
	<div class="mdl-grid">
		<header class="mdl-cell mdl-cell--12-col">
			<h3 class="home-section-title">Наши программы <a href="<?php echo home_url('projects');?>" title="Все программы">(<?php echo $progs->found_posts;?>) &gt;</a></h3>
		</header>
	<?php foreach($progs->get_posts() as $mp) {
        tst_project_card($mp);
    }?>
	</div>
</section>
<?php }?>

<?php if(!empty($f_post)) { ?>
<section class="home-section posts">
	
	<div class="mdl-grid">
		<header class="mdl-cell mdl-cell--12-col">
			<h3 class="home-section-title">Последние новости <a href="<?php echo home_url('novosti');?>" title="Все новости">(<?php echo $f_post->found_posts;?>) &gt;</a></h3>
		</header>
	<?php foreach($f_post->get_posts() as $fp) {
        tst_post_card($fp);
    }?>
	</div>
</section>
<?php }?>

<?php //$parnter_bg = wp_get_attachment_url(get_post_meta($home_id, 'partners_bg', true));

	$part_ids = (get_post_meta($home_id, 'home_partners', true)); 
	$partners = array();
	if($part_ids) {
		$partners  = get_posts(array('post_type' =>'org', 'post__in' => $part_ids, 'post_status' => 'publish', 'cache_results' => false));
	}

	if($partners) { ?>
<section class="home-partners-block">
<div class="mdl-grid">
<div class="mdl-cell mdl-cell--12-col">
	<h5 class="widget-title">Нас поддерживают</h5>
	<div class="widget-content mdl-shadow--2dp">
		
		<!-- gallery -->
		<?php if(!empty($partners)) { ?>
		<div class="partners-gallery">
		<?php foreach($partners as $org) {

				$title = apply_filters('tst_the_title', $org->post_title);  
				$url = esc_url($org->post_excerpt);								
				$logo = get_the_post_thumbnail($org->ID,'full', array('alt' => $title));?>

			<div class="logo"><div class="logo-frame">			
				<a class="logo-link" title="<?php echo esc_attr($title);?>" target="_blank" href="<?php echo $url;?>"><?php echo $logo;?></a>
			</div></div>
		<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>
</div>
</section>
<?php } ?>

<section class="home-footer-block">
	<div class="mdl-grid">
	
	<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
		<?php dynamic_sidebar('footer_1-sidebar'); ?>	
	</div>
	
	<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
		<?php dynamic_sidebar('footer_2-sidebar'); ?>	
	</div>
	
	<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
		<?php dynamic_sidebar('footer_3-sidebar'); ?>	
	</div>
	
	</div>
</section>

<?php get_footer(); ?>