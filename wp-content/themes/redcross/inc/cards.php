<?php

/** == Posts elements == **/
function rdc_post_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 25, true));
?>
<article class="tpl-post card">
	<a href="<?php echo $pl; ?>" class="thumbnail-link">
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>');?></div>
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
		<div class="entry-summary"><?php echo $ex;?></div>
	</div>
	</a>
</article>
<?php
}

function rdc_project_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="tpl-programm card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'square');?></div>
	<h4 class="entry-title"><span><?php echo get_the_title($cpost);?></span></h4>
</a></article>
<?php
}

function rdc_featured_post_card(WP_Post $cpost){
	
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 40, true));
?>
<article class="tpl-featured container-wide">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container featured-body">
		<a href="<?php echo $pl; ?>" class="featured-content">
			<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>'); ?></div>
			<h4 class="entry-title"><span><?php echo get_the_title($cpost);?></span></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</a>
	</div>	
</article>
<?php
}

function rdc_featured_action_card(WP_Post $cpost, $cta = ''){
	
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
	$pl = get_permalink($cpost);
	$cta = (!empty($cta)) ? $cta : __('View', 'rdc');
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 40, true));
?>
<article class="tpl-featured-action container-wide">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container featured-body">
		<div class="featured-content">			
			<h4 class="entry-title"><a href="<?php echo $pl; ?>"><?php echo get_the_title($cpost);?></a></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
			<div class="cta"><a href="<?php echo $pl; ?>" class="cta-button"><?php echo $cta;?></a></div>
		</div>
	</div>	
</article>
<?php
}

function rdc_related_post_card(WP_Post $cpost) {

	$pl = get_permalink($cpost);
?>
<article class="tpl-related-post card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<?php if('project' != $cpost->post_type) { ?>
		<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>');?></div>
		<?php } ?>
		<h4 class="entry-title"><span><?php echo get_the_title($cpost);?></span></h4>
	</div>
</a></article>	
<?php
}

function rdc_event_card(WP_Post $cpost){
		
	//162	
	$event = new TST_Event($cpost);
	
	$pl = get_permalink($event->post_object);
	$col_css = (has_post_thumbnail($event->ID)) ? 'md-8' : 'md-12';
	$ex = apply_filters('tst_the_title', tst_get_post_excerpt($event, 40, true));
	$icons = $event->get_icons();
?>
	<article class="tpl-event section-events" <?php echo $event->get_event_schema_prop();?>>
		<a href="<?php echo $pl;?>" class="post-link" <?php echo $event->get_event_url_prop();?>>
			<div class="s-frame">
			<?php if(has_post_thumbnail($event->ID)) { ?>
				<div class="s-bit md-4">
					<?php echo tst_post_thumbnail($event->ID, 'post-thumbnail'); ?>
				</div>
			<?php } ?>
				<div class="s-bit <?php echo $col_css;?> event-content">
					<div class="entry-meta"><?php echo $event->posted_on_card();?></div>
					<h2 class="entry-title" <?php echo $event->get_event_name_prop();?>><?php echo get_the_title($event->post_object);?></h4>
					<div class="entry-summary">
						<p><?php echo apply_filters('tst_the_title', $event->get_participants_mark());?></p>
						<p><?php echo apply_filters('tst_the_title', $event->get_what_where_mark());?></p>
						<?php echo $event->get_event_offer_field();?>
					</div>
					<?php echo tst_get_taglist_for_card($cpost);?>
				</div>
				<?php if($icons) { ?>
					<div class="entry-format-icons"><?php echo $icons;?></div>
				<?php } ?>
				
			</div><!-- .s-frame -->
		</a>		
	</article>
<?php
}

function rdc_person_card(WP_Post $cpost, $linked = true){
	$pl = get_permalink($cpost);	
?>
<article class="tpl-person card">
<?php if($linked) {?> <a href="<?php echo $pl; ?>" class="entry-link"><?php } ?>
	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'square');?></div>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-meta"><?php echo apply_filters('rdc_the_title', $cpost->post_excerpt);?></div>
	
<?php if($linked) {?></a><?php } ?>
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



/** == Helpers == **/

/** Excerpt **/
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





