<?php
/**
 * Template name: People
 **/


$cpost = get_queried_object();
$args = array(
	'posts_per_page'   => 50,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'person',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts_array = get_posts( $args );

function tst_team_member_vcard(WP_Post $member) {
	$name = apply_filters('tst_the_title', $member->post_title);
	$role = apply_filters('tst_the_title', $member->post_excerpt);;

	$thumb_id = get_term_meta($member->term_id, 'author_image_id', true);
	$thumb = '';
    $img = get_the_post_thumbnail($member->ID, 'thumbnail');
    $thumb_id = get_post_thumbnail_id( $member->ID );

	if($thumb_id){
		$src = wp_get_attachment_image_src($thumb_id, 'vcard');
		if($src)
			$thumb = "<div class='vcard-img-background' style='background-image: url(".$src[0].");'></div>";
	}

?>
<article class="person-vcard">
	<div class="person-vcard__thumbnail"><?php echo $thumb;?></div>
	<h4 class="person_vcard__title"><?php echo $name;?></h4>
	<div class="person-vcard__role"><?php echo $role;?></div>
</article>

<?php
}

get_header();?>
<section class="main-content tpl-page-regular person-page">
    <div class="container-narrow">
        <header class="page-header">
            <h1 class="page-title"><?php echo get_the_title($cpost);?></h1>
        </header>

        <div class="entry-content"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?></div>
    </div>
    <div class="left-div-padder">
    	<!-- page -->
    	<div class="single-body border--space">
    		<?php if(!empty($posts_array)) { ?>
    		<div class="flex-grid start">
    		<?php foreach($posts_array as $tm){ /*var_dump($tm);*/?>
    			
                <div class="flex-cell flex-mf-6 flex-md-3"><?php tst_team_member_vcard($tm);?></div>
    		<?php } ?>
    		</div>
    		<?php } ?>
    	</div>

    </div>
</section>  

<?php get_footer(); ?>