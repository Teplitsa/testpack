<?php
/**
 * Template name: Landing
 **/


get_header();?>

<article class="landing">
	<?php wds_page_builder_area( 'page_builder_default' );?>
	<section class="cta"><?php wds_page_builder_area('cta'); ?></section>
</article>

<?php 

get_footer();