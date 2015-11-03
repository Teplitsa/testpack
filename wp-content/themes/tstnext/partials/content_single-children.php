<?php
/**
 * @package bb
 */

$thumb_id = get_post_thumbnail_id(get_the_ID());
$thumb = wp_get_attachment_url($thumb_id);
$thumb = aq_resize($thumb, 300, 300, true, true, true);
$age = get_the_term_list(get_the_ID(), 'children_group', '', ', ', '');

?>

<article <?php post_class('tpl-children-full'); ?>>

	
	<div class="mdl-grid mdl-grid--no-spacing profile-meta">
		
		<div class="mdl-cell mdl-cell--4-col ">
			<div class="profile-pic mdl-shadow--2dp">
				<img src="<?php echo $thumb;?>" alt="Фото профиля - <?php the_title();?>">
			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col mdl-cell--4-col-tablet">
		<?php if(!empty($age)) { ?>
			<div class="profile-meta-tags">
				<?php echo $age; ?>
			</div>
		<?php } ?>	
			<div class="profile-meta-text">
				<?php the_excerpt();?>
			</div>
			
			<div class="profile-meta-tags help-btn"><a href="<?php echo home_url('/campaign/help-us/');?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Помочь сейчас</a></div>
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