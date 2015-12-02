<?php
/**
 * Template Name: Homepage
 * 
 */
 
$home_id = $post->ID;
$more_link = home_url('about/contacts');


//settings
$profiles_order = get_post_meta($home_id, 'home_profiles_order', true);
$profiles_filter = get_post_meta($home_id, 'home_profiles_cat', true);
$profiles_per_page = ($profiles_order == 'first') ? 6 : 4;

$news_order = get_post_meta($home_id, 'home_news_order', true);
//$news_filter = get_post_meta($home_id, 'home_news_cat', true);
$news_per_page = ($news_order == 'first') ? 5 : 3;

$projects_order = get_post_meta($home_id, 'home_projects_order', true);
$projects_per_page = ($projects_order == 'first') ? 2 : 3;

$sections = array();


//profiles
if($profiles_order != 'none'){
	$profiles_args = array(
		'post_type' => 'children',	
		'posts_per_page' => $profiles_per_page,
		'orderby' => 'rand'		
	);

	$profiles = new WP_Query($profiles_args);
	
	if($profiles->have_posts() && $profiles->found_posts > 1){
				
		$title = "Наши дети <a href='".home_url('all-children')."' title='Все'>(".$profiles->found_posts.")</a>";		
		$sections[$profiles_order] = array('posts' => $profiles->posts, 'title' => $title);
	}
}

//news
if($news_order != 'none'){
	
	$news_featured = array(
		'post_type' => 'post',	
		'posts_per_page' => 2,
		'cache_results' => false,
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => 'project-help'
			)
		)
	);
	
	$news_featured_query = new WP_Query($news_featured);
	
	$dnd = ($news_featured_query->have_posts()) ? tst_get_post_id_from_posts($news_featured_query->posts): array();
	$news_args = array(
		'post_type' => 'post',	
		'posts_per_page' => $news_per_page-2,
		'cache_results' => false,
		'post__not_in' => $dnd
	);
		
	$news_query = new WP_Query($news_args);
	$news = array_merge($news_featured_query->posts, $news_query->posts);
		
	if(!empty($news)){
		$count = $news_query->found_posts + 2;
		$title = "Наши новости <a href='".home_url('news')."' title='Все'>(".$count.")</a>";		
		$sections[$news_order] = array('posts' => $news, 'title' => $title);
	}
}

//progs
if($projects_order != 'none') {
	$projects_args = array(
		'post_type' => 'project',
		'posts_per_page' => $projects_per_page,
		'orderby' => 'rand',
		'cache_results' => false
	);
	
	$projects = new WP_Query($projects_args);
	
	if($projects->have_posts() && $projects->found_posts > 1){
		$all = get_post_type_archive_link('project');
		$title = "Наши проекты <a href='".$all."' title='Все'>(".$projects->found_posts.")</a>";		
		$sections[$projects_order] = array('posts' => $projects->posts, 'title' => $title);
	}
}

get_header();
//Att! no support for incorrect section order


if(isset($sections['first']['posts'])) {
	$num = count($sections['first']['posts']);
?>
<section class="home-section first">
	<div class="mdl-grid">
	<?php
		for($i = 0; $i < 2; $i++){
			tst_print_post_card($sections['first']['posts'][$i]);
		}
	?>
		<div class="mdl-cell mdl-cell--4-col mdl-cell--hide-phone mdl-cell--hide-tablet"><?php get_sidebar(); ?></div>
	</div>
	<div class="mdl-grid">
	<?php
		for($i = 2; $i < $num; $i++){
			$cpost = $sections['first']['posts'][$i];
			if($cpost->post_type == 'children'){
				$cpost->grid_css = 'mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone';
			}
			
			tst_print_post_card($cpost);
		}
	?>
	</div>
</section>
<?php } ?>

<!-- second -->
<?php if(isset($sections['second']['posts'])) {
	$num = count($sections['second']['posts']);
?>
<section class="home-section second">
	<div class="mdl-grid">
		<?php if(isset($sections['second']['title']) && !empty($sections['second']['title'])) { ?>
		<header class="mdl-cell mdl-cell--12-col">
			<h3 class="home-section-title"><?php echo $sections['second']['title'];?></h3>
		</header>
		<?php } ?>
		<?php
			for($i = 0; $i < $num; $i++){
				$cpost = $sections['second']['posts'][$i];
				if($cpost->post_type == 'children'){
					$cpost->grid_css = 'mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone';
				}
				tst_print_post_card($cpost);
			}
		?>		
	</div>	
</section>
<?php } ?>

<!-- third -->
<?php if(isset($sections['third']['posts'])) {
	$num = count($sections['third']['posts']);
?>
<section class="home-section third">
	<div class="mdl-grid">
		<?php if(isset($sections['third']['title']) && !empty($sections['third']['title'])) { ?>
		<header class="mdl-cell mdl-cell--12-col">
			<h3 class="home-section-title"><?php echo $sections['third']['title'];?></h3>
		</header>
		<?php } ?>
		<?php
			for($i = 0; $i < $num; $i++){
				$cpost = $sections['third']['posts'][$i];
				if($cpost->post_type == 'children'){
					$cpost->grid_css = 'mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone';
				}
				tst_print_post_card($cpost);				
			}
		?>		
	</div>	
</section>
<?php } ?>

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
	<h5 class="widget-title"><a href="<?php echo home_url('about/partners');?>">Нас поддерживают</a></h5>
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