<?php
/**
 * General section templates
 **/

if(is_tax('section', 'news')){
	get_template_part('index'); //just to please hierarchy
	exit;
}

$section = get_queried_object();


get_header();
?>
<section class="heading" <?php echo tst_get_heading_style();?>>
	<div class="heading__block">
		<div class="heading__title"><h1><?php echo apply_filters('tst_the_title', $section->name);?></h1></div>
		<?php if(!empty($section->description)) { ?>
			<div class="heading__desc"><?php echo apply_filters('tst_the_content', $section->description);?></div>
		<?php } ?>
	</div>
</section>

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
