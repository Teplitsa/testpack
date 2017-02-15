<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

/** @var WP_Post $cpost */
$cpost = get_queried_object();
$event = new TST_Event($cpost);

get_header();?>
<section class="top-content">
	<div class="top-content__middle top-content__middle--padded">
		<header class="single-header">
			<div class="single-header__crumbs">
<!--				--><?php //echo tst_single_section($cpost);?>
			</div>
			<h1 class="single-header__title"><?php echo get_the_title($cpost);?></h1>
		</header>
	</div>
</section>

<div class="spacer hide-upto-large"></div>

<div class="frame frame-rail--lg-8">
<section class="main-content bit lg-8">
<div class="left-div-padder">

	<div class="single-body single-body--event border--space <?php echo $event->is_expired() ? 'event-expired' : '';?>">

		<div class="single-body--meta--ontop">
			<div class="event-full-meta">
				<div class="event-full-meta__item event-full-meta__item--date"><?php echo $event->get_full_date_mark('human');?></div>
				<div class="event-full-meta__item event-full-meta__item--place"><?php echo $event->get_full_address();?></div>
<!--				<div class="event-full-meta__item event-full-meta__item--atc">--><?php //echo tst_add_to_calendar_link($event, 'tst-add-calendar', __('Add to calendar', 'tst'), true); ?><!--</div>-->
			</div>
		</div>

		<?php if(has_post_thumbnail($cpost)) { ?>
			<div class="single-body--preview"><?php tst_single_thumbnail($cpost);?></div>
		<?php } ?>

<!--		<div class="single-body--meta">--><?php //echo $event->get_format_meta(); ?><!--</div>-->

		<article class="single-body--content">
			<?php $event->schema_markup();?>
			<div class="single-body--entry">
				<?php echo apply_filters('tst_entry_the_content', $cpost->post_excerpt); ?>
				<?php echo apply_filters('tst_entry_the_content', $cpost->post_content); ?>
			</div>

			<?php
				$contacts = $event->contact;
				if($contacts || $event->has_marker()) {?>

				<div class="single-body--contacts-block">
                <?php if($contacts) {?>
					<div class="event-contacts">
						<h5><?php _e('Contacts', 'tst');?></h5>
						<?php echo apply_filters('tst_the_content', $contacts);?>
					</div>
                <?php }?>

                <?php if($event->has_marker()) {
                    tst_post_map($cpost->ID);
                }?>
				</div>
			<?php }?>

			<?php
//				$readmore = tst_event_relative_links($cpost, 3);
//
//				if($readmore && isset($readmore['html'])) {
//			?>
<!--			<div class="single-body--relations">-->
<!--				<h4>--><?php //echo $readmore['title'];?><!--</h4>-->
<!--				--><?php //echo $readmore['html'];?>
<!--			</div>-->
<!--			--><?php //} ?>
		</article>

		<div class="single-body--microsharing border--regular">
<!--			--><?php //tst_social_share_mobile($cpost); ?>
			<div class="clear"></div>
		</div>

		<?php
//			$ngo = tst_single_ngos_list($cpost);
			$tags = apply_filters('tst_entry_the_content', get_the_term_list($cpost->ID, 'post_tag', '', ', ', '' ));
//			$regs = $event->get_regions_meta();

			if($tags ) {
		?>
			<div class="single-body--vcard border--regular">
			<?php if(!empty($tags)) { ?><div class="tags-vcard"><i><?php _e('Tags', 'tst');?>:</i> <?php echo $tags;?></div><?php } ?>
			</div>
		<?php }?>

	</div>

</div>
</section>
</div>


<?php get_footer();
