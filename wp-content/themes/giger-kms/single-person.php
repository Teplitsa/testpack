<?php
/**
 * The template for person
 *
 * @package bb
 */

$cpost = get_queried_object();


get_header();?>
<article class="main-content tpl-page-regular">
    <div class="container">
    
    <section class="single-person">

		<?php tst_single_person_card($cpost);?>

    </section>
    
</article>
<?php
get_footer();