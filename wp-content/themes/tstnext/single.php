<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$pt = $post_id = '';
get_header();
?>
<div class="page-content-grid">
<div class="mdl-grid">
		
	<div class="mdl-cell mdl-cell--8-col mdl-cell--6-col-phone">
		<?php		
			while(have_posts()){
				the_post();
				$pt = get_post_type();
				$post_id = get_the_ID();
				get_template_part('partials/content_single', get_post_type());				
			}			
		?>		
	</div>
	
	<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-cell--6-col-phone"><?php get_sidebar(); ?></div>
	
</div><!-- .row -->
</div>

<div class="page-footer"><div class="mdl-grid">
	<div class="mdl-cell mdl-cell--5-col mdl-cell--6-col-tablet">
	<?php		
		if('post' == $pt){ //related posts
			
			$r_query = frl_get_related_query($post, 'category', 3); 
			if($r_query->have_posts()){					
	?>
			<aside class="related-posts section">	
				<h5><?php _e('More news', 'tst');?></h5>	
				<?php
					foreach($r_query->posts as $rp){
						tst_compact_post_item($rp);
					}	
				?>
			</aside>
	<?php
			}
			
		}
		elseif('children' == $pt){ //related posts
			
			$r_query = new WP_Query(array(
				'post_type' => 'children',
				'post__not_in' => array($post_id),
				'posts_per_page' => 3,
				'orderby' => 'rand',
				'tax_query' => array(
					array(
						'taxonomy' => 'children_status',
						'field' => 'slug',
						'terms' => 'need-help'
					)
				)
			));
			
			if($r_query->have_posts()){					
	?>
			<aside class="related-posts section">	
				<h5>Требуется помощь</h5>	
				<?php
					foreach($r_query->posts as $rp){
						tst_compact_children_item($rp);
					}	
				?>
			</aside>
	<?php
			}
			
		}
		elseif('project' == $pt) {
			$r_query = new WP_Query(array(
				'post_type'       => 'post',
				'connected_type'  => 'project_post',
				'connected_items' => $post,
				'posts_per_page' => 3
			));
			
			$label = 'Новости проекта';
			
			if(!$r_query->have_posts()){
			
				$r_query = new WP_Query(array(
					'post_type' => 'project',
					'post__not_in' => array($post_id),
					'posts_per_page' => 3,
					'orderby' => 'rand'
				));
				
				$label = 'Еще проекты';
			}
			if($r_query->have_posts()){					
	?>
			<aside class="related-posts section">	
				<h5><?php echo $label; ?></h5>	
				<?php
					foreach($r_query->posts as $rp){
						if($rp->post_type == 'post'){
							tst_compact_post_item($rp);
						}
						else {
							tst_compact_project_item($rp);
						}						
					}	
				?>
			</aside>
	<?php
			}
		}		
	?>		
	</div>
	<div class="mdl-cell mdl-cell--7-col mdl-cell--2-col-tablet mdl-cell--hide-phone"></div>
</div></div>

<?php get_footer(); ?>
