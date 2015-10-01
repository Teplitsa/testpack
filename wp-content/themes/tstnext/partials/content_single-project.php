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
	</div>		
	
</article><!-- #post-## -->

<!-- panel -->
<?php
	add_action('tst_footer_position', function(){
		get_template_part('partials/panel', 'float');	
	});
?>

