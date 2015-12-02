<?php
/**
 * Single project
 **/


?>

<article <?php post_class('tpl-project-full'); ?>>

		
	<div class="entry-summary"><?php the_excerpt();?></div>
	<div class="sharing-on-top"><?php tst_social_share_no_js();?></div>
		
	
	<div class="entry-content">		
		<?php the_content(); ?>
		<div class="support-block">
			<h5>Поддержите проект</h5>
			<div class="mdl-grid mdl-grid--no-spacing">
				<div class="mdl-cell mdl-cell--8-col ">
					<p>Ваше пожертвование поможет детям продолжить борьбу с болезнью, восстановить здоровье и вернуться к полноценной жизни.</p>
				</div>
				<div class="mdl-cell mdl-cell--4-col">
					<a href="<?php echo home_url('campaign/help-us');?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Помочь сейчас</a>
				</div>
			</div>
		</div>
	</div>		
	
</article><!-- #post-## -->

<!-- panel -->
<?php
	add_action('tst_footer_position', function(){
		get_template_part('partials/panel', 'float');	
	});
?>

