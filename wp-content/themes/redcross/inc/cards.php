<?php

/** == Posts elements == **/
function rdc_post_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 25, true));
?>
<article class="tpl-post card">
	
	<a href="<?php echo $pl; ?>" class="thumbnail-link"><?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?></a>
	<div class="entry-data">
		<div class="entry-meta"><?php echo rdc_posted_on($cpost);?></div>
		<h4 class="entry-title"><a href="<?php echo $pl; ?>"><?php echo get_the_title($cpost);?></a></h4>
		<div class="entry-summary"><?php echo $ex;?></div>
	</div>
</article>
<?php
}

function rdc_project_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="tpl-programm card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<?php echo rdc_post_thumbnail($cpost->ID, 'square');?>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
</a></article>
<?php
}

function rdc_featured_post_card(WP_Post $cpost){
	
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 40, true));
?>
<article class="tpl-featured">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container featured-body">
		<a href="<?php echo $pl; ?>" class="featured-content">
			<div class="entry-meta"><?php echo rdc_posted_on($cpost);?></div>
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</a>
	</div>	
</article>
<?php
}



/** Excerpt  **/
function rdc_get_post_excerpt($cpost, $l = 30, $force_l = false){
	
	if(is_int($cpost))
		$cpost = get_post($cpost);
	
	$e = (!empty($cpost->post_excerpt)) ? $cpost->post_excerpt : wp_trim_words(strip_shortcodes($cpost->post_content), $l);
	if($force_l)
		$e = wp_trim_words($e, $l);	
	
	return $e;
}


/** Deafult thumbnail for posts **/
function rdc_get_default_post_thumbnail($type = 'default_thumbnail', $size){
		
	$default_thumb_id = attachment_url_to_postid(get_theme_mod($type));
	$img = '';
	if($default_thumb_id){
		$img = wp_get_attachment_image($default_thumb_id, $size);	
	}
	
	return $img;
}

function rdc_post_thumbnail($post_id, $size = 'post-thumbnail'){
	
	$thumb = get_the_post_thumbnail($post_id, $size);
	
	if(!$thumb){
		$thumb = rdc_get_default_post_thumbnail('default_thumbnail', $size);
	}
			
	return $thumb;
}

function rdc_post_thumbnail_src($post_id, $size = 'post-thumbnail'){
	
	$src = get_the_post_thumbnail_url($post_id, $size);
	if(!$src){
		$default_thumb_id = attachment_url_to_postid(get_theme_mod('default_thumbnail'));
		if($default_thumb_id){
			$src = get_the_post_thumbnail_url($default_thumb_id, $size);
		}
	}
	
	return $src;
}

function rdc_related_post_card(WP_Post $cpost) {
	
	$pl = get_permalink($cpost);	
?>
<article class="tpl-related-post <?php echo $cpost->post_type;?>">
	<a href="<?php echo $pl; ?>" class="thumbnail-link">
		<?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?>
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	</a>
</article>
<?php
}

function rdc_person_card(WP_Post $cpost){
		
?>
<article class="tpl-person">
	
	<div class="avatar"><?php echo rdc_post_thumbnail($cpost->ID, 'thumbnail');?></div>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-meta"><?php echo apply_filters('rdc_the_title', $cpost->post_excerpt);?></div>
	<div class="entry-summary"><?php echo apply_filters('rdc_the_content', $cpost->post_content);?></div>		
	
</article>
<?php
}

function rdc_org_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="tpl-org logo">
	<a href="<?php echo $pl;?>" class="logo-link logo-frame" target="_blank" title="<?php echo esc_attr($cpost->post_title);?>">
		<span><?php echo get_the_post_thumbnail($cpost->ID, 'full'); ?></span>
	</a>
</article>
<?php
}