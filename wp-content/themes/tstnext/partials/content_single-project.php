<?php
/**
 * Single project
 **/

$show_thumb = (function_exists('get_field')) ? (bool)get_field('show_thumb') : true;
?>

<article <?php post_class('tpl-project-full'); ?>>

		
	<div class="entry-summary"><?php the_excerpt();?></div>
	<div class="sharing-on-top"><?php tst_social_share_no_js();?></div>
	
	<?php
		if($show_thumb && has_post_thumbnail()) {
			echo "<div class='entry-media'>";
			the_post_thumbnail('embed', array('alt' => __('Thumbnail', 'tst')));
			echo "</div>";
		}
	?>
	
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

