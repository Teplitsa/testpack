<?php
/**
 * Donation history
 **/

$campaign = get_query_var('leyka_campaign_filter');

 
get_header();
?>
<section class="heading">
	<div class="container">
		<?php rdc_section_title(); ?>
		
	</div>
</section>

<section class="main-content donations-history-results"><div class="container">
	<div class="donation_history">
	<?php
		if(have_posts()){
			foreach($wp_query->posts as $p){
				
			}
		}
		else {
			echo "<p>";
			_e();
			echo "</p>";
		}
	?>
	</div>
</div></section>
<section class="paging"><?php rdc_paging_nav($wp_query); ?></section>

<?php get_footer();
