<?php
/**
 * Template name: People
 **/


$cpost = get_queried_object();
$section = get_the_terms($cpost, 'section');
if($section)
	$section = $section[0];


$args = array(
	'posts_per_page'   => -1,
	'orderby'          => array('menu_order' => 'DESC', 'title' => 'ASC'),
	'post_type'        => 'person',
	'post_status'      => 'publish',
	'suppress_filters' => true
);

$posts_array = get_posts( $args );


function tst_break_at_first_word($string, $break_str = ' ', $repl_str = '<br>') {
  if ( strpos($string, $break_str) )
    return substr( $string, 0, strpos($string, $break_str) ) . $repl_str . substr( $string, strpos($string, $break_str) + strlen($break_str)  );
  else
    return $string;
}

function tst_team_member_vcard(WP_Post $member) {

	$name = tst_break_at_first_word(apply_filters('tst_the_title', $member->post_title));
	$role = apply_filters('tst_the_title', $member->post_excerpt);

    $thumb = get_the_post_thumbnail($member->ID, 'thumbnail');
?>
<article class="person-vcard">
	<div class="person-vcard__thumbnail"><?php echo $thumb;?></div>
	<h4 class="person_vcard__title"><a href="<?php echo get_permalink($member);?>"><?php echo $name;?></a></h4>
	<div class="person-vcard__role"><?php echo $role;?></div>
</article>

<?php
}

get_header();?>
<div class="landing page-general">
	<header class="page-general__header">
		<div class="container-narrow">
		<?php if($section) { ?>
			<div class="page-general__crumbs"><a href="<?php echo get_term_link($section);?>"><?php echo apply_filters('tst_the_title', $section->name);?></a></div>
		<?php } ?>
			<h1 class="page-general__title"><?php echo get_the_title($cpost);?></h1>

		</div>


	</header>

	<div class="page-general__content">
		<div class="container">

		<?php if(!empty($posts_array)) { ?>
		<div class="flex-grid start">
		<?php foreach($posts_array as $tm){ /*var_dump($tm);*/?>

			<div class="flex-cell flex-mf-6 flex-md-3"><?php tst_team_member_vcard($tm);?></div>
		<?php } ?>
		</div>
		<?php } ?>

		</div>


		<footer class="page-general__footer"><div class="container-narrow">

			<div class="flex-grid--stacked">
			<?php if($section) { ?>
				<div class="flex-cell--stacked md-6 inpage-block inpage-block--section">
					<div class="inpage-block__content">
						<h3><?php printf(__('At the section &laquo;%s&raquo;', 'tst'), $section->name);?></h3>
						<?php tst_section_list($section->term_id, $cpost->ID);?>
					</div>
				</div>
			<?php } ?>

				<div class="flex-cell--stacked md-6 inpage-block inpage-block--join">
					<div class="inpage-block__content"><h3><?php _e('Join us', 'tst');?></h3>
					<?php tst_join_list(); ?>
					</div>
				</div>
			</div>

		</div></footer>

	</div>
</div>

<?php get_footer(); ?>