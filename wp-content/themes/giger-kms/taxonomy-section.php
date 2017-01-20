<?php
/**
 * General section templates
 **/

if(is_tax('section', 'news')){
	get_template_part('index'); //just to please hierarchy
	exit;
}

$section = get_queried_object();

$additional_blocks = array();

if(is_tax('section', 'advices') || is_tax( 'section', 'resources' ) ){
    $additional_blocks = get_posts( array(
        'post_type' => 'story',
        'posts_per_page' => 3,
    ) );
}

if( count( $additional_blocks ) ) {
    $posts = array_merge( $posts, $additional_blocks );
}

get_header();
?>
<section class="main">
	<div class="flex-grid start">
		<?php
			$count = 1;
			if(!empty($posts)) { foreach($posts as $i => $cpost) {
				$css = 'flex-cell ';
				if($count > 5)
					$count = 1;

				switch($count){
					case 1:
						$css .= 'flex-sm-7 flex-lg-7 scheme-one';
						break;

					case 2:
						$css .= 'flex-sm-5 flex-lg-5 scheme-two';
						break;

					case 3:
						$css .= 'flex-sm-5 flex-lg-4 scheme-three';
						break;

					case 4:
						$css .= 'flex-sm-7 flex-lg-5 scheme-four';
						break;

					case 5:
						$css .= 'flex-sm-5 flex-lg-3 scheme-five';
						break;
				}

				$count++;
		?>
			<div class="<?php echo $css;?> cell-bg"><?php tst_card($cpost);?></div>
		<?php }} else { ?>
		<div class="flex-cell flex-mf-12"><p><?php _e('Nothing found under your request', 'tst');?></p></div>
		<?php } ?>
	</div>
</section>


<?php get_footer();
