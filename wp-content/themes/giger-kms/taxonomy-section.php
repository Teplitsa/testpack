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
						$css .= 'flex-sm-7 flex-lg-5 scheme-five';
						break;

					case 5:
						$css .= 'flex-sm-5 flex-lg-3 scheme-four';
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


<section class="home-section join-stand-alone">
	<div class="join-stand-alone__title"><h3>Вступайте</h3><h4>в группу взаимопомощи людей, живущих с&nbsp;ВИЧ</h4></div>
	<div class="join-stand-alone__content"><?php do_shortcode( '[tst_join_whatsapp_group]' ) ?></div>
</section>


<?php
	$stories = array();
	if(is_tax('section', 'advices') || is_tax( 'section', 'resources' ) ){
		$stories = TST_Stories::get_rotated( 3 );
	}
?>

<?php if( count( $stories ) ): ?>
<section class="home-section info-block">
	<div class="info-block__title"><h3>Истории</h3></div>
	<div class="info-block__content">
		<div class="flex-grid">
		<?php foreach( $stories as $story ):?>
			<div class="flex-cell flex-sm-12 flex-md-4"><?php tst_story_card( $story ) ?></div>
		<?php endforeach;?>
		</div>
	</div>
</section>
<?php endif?>

<?php get_footer();
