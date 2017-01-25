<?php
/** Single Template for items  [formidable key="ask-question"]**/

$cpost = get_queried_object();
$item = new TST_Item($cpost);


get_header();
?>

<section class="main">
	<div class="single-item--title"><h1><?php echo apply_filters('tst_the_title', $item->get_root_title());?></h1></div>

	<div class="flex-grid question-grid">
		<div class="flex-cell flex-md-12 flex-lg-9">
			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>
		</div>
		<div class="flex-cell flex-md-8 flex-lg-9 ">
			<div class="question-form">
				<?php echo do_shortcode('[formidable key="ask-question"]');?>
			</div>
			<div class="sharing"><?php tst_social_share($item->post_object);?></div>
		</div>
		<div class="flex-cell flex-md-4 flex-lg-3 question-side">
			<?php echo $item->get_sidebar();?>
		</div>

	</div>
</section>

<?php
get_footer();