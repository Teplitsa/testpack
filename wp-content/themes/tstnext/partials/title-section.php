<?php
/**
 * Title */


if(is_front_page()) { ?>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--12-col">
		<div class="home-logo"><?php tst_site_logo('regular');?></div>
	</div>
</div>
<?php } elseif(is_singular(array('post', 'event', 'project'))) { ?>
<div class="mdl-grid">
	
	<div class="mdl-cell mdl-cell--8-col mdl-cell--6-col-phone">
		<h1 class="page-title"><?php echo get_the_title($post);?></h1>
	</div>
	<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-cell--6-col-phone"></div>
</div>
<?php } elseif(is_page()) { //by templates type
	
		$template = get_post_meta( $post->ID, '_wp_page_template', true );		
		if($template == 'page-sidebar.php') { // with sidebar
	?>
		<div class="mdl-grid">	
			<div class="mdl-cell mdl-cell--8-col mdl-cell--6-col-phone">
				<h1 class="page-title"><?php echo get_the_title($post);?></h1>
			</div>
			<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-cell--6-col-phone"></div>
		</div>
	<?php
		}
		elseif($template == 'page-cards.php' || $template == 'page-calendar.php') { //full width 
	?>
			<div class="mdl-grid">	
				<div class="mdl-cell mdl-cell--12-col">
					<h1 class="page-title"><?php echo get_the_title($post);?></h1>
				</div>
				<div class="mdl-cell mdl-cell--12-col"></div>
			</div>
		<?php
		}
		else { //regular page			
	?>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
			<div class="mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet">	
				<h1 class="page-title"><?php echo get_the_title($post);?></h1>
			</div>
			<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
		</div>
	<?php
		}		
	} elseif(is_singular('leyka_campaign')) { ?>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	<div class="mdl-cell mdl-cell--8-col mdl-cell--8-col-tablet">	
		<h1 class="page-title"><?php echo get_the_title($post);?></h1>
	</div>
	<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
</div>
<?php } elseif(is_search() || is_404()) { ?>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--3-col mdl-cell--hide-phone mdl-cell--hide-tablet"></div>
	<div class="mdl-cell mdl-cell--6-col mdl-cell--5-col-tablet">	
		<h1 class="page-title">
		<?php
			if(is_search()) {
				_e('Search results', 'tst');
			}
			else {
				_e('404: Page not found', 'tst');
			}
		?>
		</h1>
	</div>
	<div class="mdl-cell mdl-cell--3-col "></div>
</div>
<?php } else { ?>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--12-col">
	<h1 class="page-title"><?php
		if(is_home()){
			$p = get_post(get_option('page_for_posts'));
			if($p)
				echo get_the_title($p);
		}
		elseif(is_category() || is_tax()){		
			single_cat_title();
			
		}				
		elseif(is_post_type_archive('org')){
			echo tst_get_post_type_archive_title('org');
		}
		elseif(is_post_type_archive('project')){
			echo tst_get_post_type_archive_title('project');
		}			
	?>
	</h1>
	<?php if(is_tax('auctor') || is_category()) {

		$qo = get_queried_object();
		echo "<div class='author-description'>"; //print event empty - we need it for layout
		if(isset($qo->description)){			
			echo apply_filters('tst_the_title', $qo->description);			
		}
		echo "</div>";
	}
	?>
	</div>
</div>
<?php } //singular ?>


