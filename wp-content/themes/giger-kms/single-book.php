<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object();

$book_author = get_post_meta( $cpost->ID, 'book_author', true );
$book_download_url = tst_get_book_url($cpost->ID);

get_header(); ?>

<article class="single-card single-book">
	<div class="single-card__header">
		<div class="single-card__title"><h1><?php echo get_the_title($cpost);?></h1></div>
		<div class="single-card__options">
			<div class="single-card__meta"></div>
		</div>
	</div>

	<div class="single-card__content <?php if(!has_post_thumbnail($cpost)) {echo 'no-thumbnail'; } ?>">
	<div class="frame">

		<div class="bit md-8 single-body">

			<?php if(has_post_thumbnail($cpost)) { ?>
				<div class="single-body__preview "><?php tst_single_thumbnail($cpost);?></div>
			<?php } ?>

			<div class="single-body--entry"><?php echo apply_filters('tst_entry_the_content', $cpost->post_content);?></div>

			<?php if( $book_author ):?>
				<div class="single-card__author"><?php echo sprintf( __( 'Book author: %s', 'tst' ), $book_author )?></div>
			<?php endif?>

			<?php if( $book_download_url ): ?>
			<div class="single-card__download"><a class="book-download-link" href="<?php echo $book_download_url?>"><i class="material-icons">file_download</i> <?php _e( 'Download book', 'tst' ) ?></a></div>
			<?php endif?>

			<div class="single-body__footer single-body__footer-mobile"><?php tst_single_post_nav();?></div>
		</div>

		<div class="bit md-4 single-aside">
		<?php
			$related = tst_get_related_query($cpost, 'post_tag', 4);
			if(!empty($related)) {
		?>
			<div class="widget">
				<div class="widget__title"><?php _e('More books', 'tst');?></div>
				<div class="widget__content"><?php tst_related_books($related->posts); ?></div>
			</div>
		<?php
			}
		?>
		</div>
	</div></div><!-- .frame .single-card__content -->
	<div class="single-body__footer single-body__footer-desktop"><?php tst_single_post_nav( __('Prev. Book', 'tst'), __('Next Book', 'tst') );?></div>
</article>

<?php
get_footer();