<?php
/**
 * @package bb
 */

$thumb_id = get_post_thumbnail_id(get_the_ID());
$thumb = wp_get_attachment_url($thumb_id);
$thumb = aq_resize($thumb, 300, 300, true, true, true);
$age = get_post_meta(get_the_ID(), 'child_age', true);
?>

<article <?php post_class('tpl-children-full'); ?>>

	
	<div class="mdl-grid mdl-grid--no-spacing profile-meta">
		
		<div class="mdl-cell mdl-cell--4-col ">
			<div class="profile-pic mdl-shadow--2dp">
				<img src="<?php echo $thumb;?>" alt="Фото профиля - <?php the_title();?>">
			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col mdl-cell--4-col-tablet">
		<?php if($age) { ?>	
			<div class="captioned-text profile-meta-text">
				<div class="caption">Возраст</div>
				<div class="text"><?php echo apply_filters('the_title', $age);?></div>
			</div>
		<?php } ?>	
			<div class="captioned-text profile-meta-text">
				<div class="caption">Требуется помощь</div>
				<div class="text"><?php the_excerpt();?></div>
			</div>
			
			<div class="captioned-text profile-meta-text help-btn"><a href="<?php echo home_url('/campaign/help-us/');?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Помочь сейчас</a></div>
		</div>
	</div>
	
	
	<div class="entry-content">		
		<?php the_content(); ?>
	</div>
	
	<div class="sharing-on-bottom"><?php tst_social_share_no_js();?></div>
	
</article><!-- #post-## -->

<!-- panel -->
<?php
	add_action('tst_footer_position', function(){
		get_template_part('partials/panel', 'float');	
	});
?>