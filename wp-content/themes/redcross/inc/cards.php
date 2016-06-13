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

function rdc_featured_post_card(WP_Post $cpost){
	
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full'); 
	$pl = get_permalink($cpost);
?>
<article class="tpl-featured card">
	<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
	
	<div class="container featured-body">
		<a href="<?php echo $pl; ?>" class="featured-content">
			<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>'); ?></div>
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>			
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
		<a href="<?php echo $pl; ?>" class="featured-content">	
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>		
			<!--<div class="cta"><a href="<?php echo $pl; ?>" class="cta-button"><?php echo $cta;?></a></div>-->
		</a>	
	</div>	
</article>
<?php
}

function rdc_featured_action_card_markup($link, $title, $subtitle, $img_id){
		
	$thumbnail = wp_get_attachment_url($img_id);
?>
	<article class="tpl-featured-action container-wide">
		<div class="bg" style="background-image: url(<?php echo $thumbnail;?>);"></div>
		
		<div class="container featured-body">
			<a href="<?php echo $link; ?>" class="featured-content">	
				<h4 class="entry-title"><?php echo apply_filters('rdc_the_title', $title);?></h4>
				<?php if(!empty($subtitle)) { ?>
					<div class="entry-summary"><?php echo apply_filters('rdc_the_title', $subtitle);?></div>
				<?php } ?>
			</a>	
		</div>	
	</article>
<?php	
}

function rdc_intro_card_markup($title, $subtitle, $img_id, $link = '', $button_text = '', $has_sharing = false, $extend_width = false) {
	
	$button_text = (!empty($button_text)) ? $button_text : __('More', 'rdc');
?>
	<section class="intro-head-image <?php if($extend_width) { echo 'container-extended'; } ?>">
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo wp_get_attachment_url( $img_id );?>)"></div>
	</section>
	<section class="intro-head-content<?php if(!empty($link)) { echo '  has-button'; }?>"><div class="ihc-content">
		<h1 class="ihc-title"><?php echo apply_filters('rdc_the_title', $title);?></h1>
		<?php if($subtitle){ ?>
			<div class="frame">
				<div class="bit <?php if(!empty($link)){ echo 'md-8 exlg-9'; }?> ihc-desc"><?php echo apply_filters('rdc_the_content', $subtitle); ?></div>
				<?php if(!empty($link)) { ?>
				<div class="bit md-4 exlg-3"><a href="<?php echo esc_url($link);?>"><?php echo $button_text;?></a></div>
				<?php } ?>
			</div>
		<?php } ?>
		
	<?php if($has_sharing) { ?>	
		<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
	<?php }?>
	</div></section>
<?php
}

function rdc_related_post_card(WP_Post $cpost) {

	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 40, true));
?>
<article class="tpl-related-post card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<?php if('project' != $cpost->post_type) { ?>
		<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>');?></div>
		<?php } ?>
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
		<div class="entry-summary"><?php echo $ex;?></div>		
	</div>
</a></article>	
<?php
}

function rdc_event_card(WP_Post $cpost){
		
	//162	
	$event = new TST_Event($cpost);
	
	$pl = get_permalink($event->post_object);	
	
?>
<article class="tpl-event card" <?php echo $event->get_event_schema_prop();?>">
	<a href="<?php echo $pl; ?>" class="thumbnail-link" <?php echo $event->get_event_url_prop();?>>
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
	<div class="entry-data">
		<div class="entry-meta"><?php echo $event->posted_on_card();?></div>
		<h4 class="entry-title" <?php echo $event->get_event_name_prop();?>><?php echo get_the_title($cpost);?></h4>
		<div class="entry-summary">
			<p><?php echo apply_filters('tst_the_title', $event->get_participants_mark());?></p>
			<p><?php echo apply_filters('tst_the_title', $event->get_full_address_mark());?></p>
			<?php echo $event->get_event_offer_field();?>
		</div>
	</div>
	</a>
</article>
<?php
}

/** Projects */
function rdc_project_card(WP_Post $cpost){
	
	$pl = get_permalink($cpost);
?>
<article class="tpl-programm card"><a href="<?php echo $pl; ?>" class="entry-link">	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'square');?></div>
	<h4 class="entry-title"><span><?php echo get_the_title($cpost);?></span></h4>
</a></article>
<?php
}

function tst_project_card_group(WP_Post $cpost){
	rdc_project_card($cpost);	
}

function tst_project_card_single(WP_Post $cpost){
	rdc_project_card($cpost);	
}


/* People and orgs */
function rdc_person_card(WP_Post $cpost, $linked = true){
	$pl = get_permalink($cpost);	
?>
<article class="tpl-person card <?php if($linked) { echo 'linked'; }?>">
<?php if($linked) {?> <a href="<?php echo $pl; ?>" class="entry-link"><?php } ?>
	
	<div class="entry-preview"><?php echo rdc_post_thumbnail($cpost->ID, 'square');?></div>
	<div class="entry-data">
		<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
		<div class="entry-meta"><?php echo apply_filters('rdc_the_title', $cpost->post_excerpt);?></div>
	</div>
	
<?php if($linked) {?></a><?php } ?>
</article>
<?php
}

function tst_person_card_group(WP_Post $cpost){
	
	$linked = ($cpost->widget_class == 'linked-card') ? true : false;
	
	rdc_person_card($cpost, $linked);	
}

function tst_person_card_single(WP_Post $cpost){
	
	$linked = ($cpost->widget_class == 'linked-card') ? true : false;
	
	rdc_person_card($cpost, $linked);	
}


function rdc_org_card(WP_Post $cpost){
	
	$pl = esc_url($cpost->post_excerpt);
?>
<article class="tpl-org logo">
	<a href="<?php echo $pl;?>" class="logo-link logo-frame" target="_blank" title="<?php echo esc_attr($cpost->post_title);?>">
		<span><?php echo get_the_post_thumbnail($cpost->ID, 'full'); ?></span>
	</a>
</article>
<?php
}

function tst_org_card_group(WP_Post $cpost){

?>
<div class="bit bit-no-margin sm-6 md-3 lg-col-5"><?php rdc_org_card($cpost); ?></div>
<?php
}

function tst_org_card_single(WP_Post $cpost){
		
	rdc_org_card($cpost);	
}


/** search **/
function rdc_search_card(WP_Post $cpost) {
	
	$pl = get_permalink($cpost);
	$ex = apply_filters('rdc_the_title', rdc_get_post_excerpt($cpost, 40, true));
	
	
?>
<article class="tpl-search"><a href="<?php echo $pl; ?>" class="entry-link">
	<div class="entry-meta"><?php echo strip_tags(rdc_posted_on($cpost), '<span>');?></div>
	<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
	<div class="entry-summary"><?php echo $ex;?></div>
</a></article>
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
			$src = wp_get_attachment_image_src($default_thumb_id, $size);
			$src = ($src) ? $src[0] : '';
		}
	}
	
	return $src;
}





