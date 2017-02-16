<?php
/**
 * The template for landings
 *
 * @package bb
 */

$cpost = get_queried_object();


get_header();?>

    <article class="landing">

		<?php wds_page_builder_area( 'page_builder_default' );?>

    </article>

<?php
get_footer();