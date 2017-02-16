<?php
/**
 * Menu and navigation functions and filters
 **/

/** == Load more button == **/

//should received query object with paged arg == currenty printed page
function tst_load_more_button(WP_Query $query, $template = 'search_card', $dnd = array(), $target_id = false){

	$paged = tst_get_current_page_for_query($query) + 1;  //next page to load
	$nonce = wp_create_nonce('load_more_posts');
	$target = (!$target_id)?  uniqid('loadmore-') : $target_id;


	//build URL for no-js fallback
	$query_args = $query->query;
	$query_args['paged'] = $paged;
	//search
	if(isset($_GET['n']) && !isset($query->query_vars['s'])){
		$s = tst_filter_search_query($_GET['n']);
		if(!empty($s))
			$query_args['s'] = $s;
	}

	$url = add_query_arg($query_args, home_url('/'));

	//add dnd to query
	if(!empty($dnd))
		$query->query_vars['post__not_in'] = (isset($query->query_vars['post__not_in'])) ? array_merge((array)$query->query_vars['post__not_in'], (array)$dnd) : (array)$dnd;

	//set load more arg
	$query->set('load_more_request', 1);

	//print
	if(!$target_id) {
?>
	<div id="<?php echo esc_attr($target);?>"></div>
<?php } ?>

	<nav class="load-more">
		<span class="loader"></span>
		<a href="<?php echo esc_url($url);?>" class="load-more-btn"
			data-nonce="<?php echo $nonce;?>"
			data-query="<?php echo esc_attr(json_encode($query->query_vars));?>"
			data-pagenum="<?php echo $paged;?>"
			data-template = "<?php echo esc_attr($template);?>"
			data-target="<?php echo esc_attr($target);?>"><?php _e('Load more', 'tst');?></a>
	</nav>

<?php
}


add_action("wp_ajax_load_more_posts", "tst_load_more_posts_screen");
add_action("wp_ajax_nopriv_load_more_posts", "tst_load_more_posts_screen");

function tst_load_more_posts_screen() {

	$result = array('type' => 'ok', 'data' => array(), 'has_more' => false);

	if(!wp_verify_nonce($_REQUEST['nonce'], "load_more_posts")) {
		die('nonce error');
	}

	//get params
	$paged 		= (isset($_REQUEST['page'])) ? (int)$_REQUEST['page'] : 0;
	$query_vars = (isset($_REQUEST['query'])) ? json_decode( stripslashes( $_REQUEST['query'] ), true ) : null;
	$template	= (isset($_REQUEST['template'])) ? trim($_REQUEST['template']) :  'search_card';

	if(empty($query_vars) || $paged == 0){
		$result['type'] = 'error';
		echo json_encode($result);
		die();
	}

	//add paging arg to qv and mark for per_page recalculation
	$query_vars['paged'] = $paged;
	$query_vars['load_more_request'] = 1; //this works as hook for paging recalculation

    if($template == 'search_card') {

        $query_vars['orderby'] = 'meta_value_num';
        $query_vars['meta_key'] = 'event_date_start';

    }

	$query = new WP_Query( $query_vars );
//var_dump($query->query_vars);
	if(!$query->have_posts()){
		$result['type'] = 'error';
		echo json_encode($result);
		die();
	}

	//build results
	ob_start();

	//print html here
	foreach($query->posts as $i => $p){
			$class = ($i == (count($query->posts) - 1)) ? 'border--regular-last' : 'border--regular';

			if($template == 'events_card'){
		?>
			<div class="layout-section__item <?php echo $class;?>">
				<?php tst_card_event($p); ?>
			</div>

		<?php } elseif($template == 'search_card')	{ ?>
			<div class="layout-section__item <?php echo $class;?>">
				<?php tst_card_search($p); ?>
			</div>

		<?php } elseif($template == 'gallery_card')	{ ?>
<!--			--><?php //if(has_shortcode($p->post_content, 'gallery' )) { ?>
<!--				<div class="flex-cell flex-md-6">--><?php //tst_card_gallery_post($p); ?><!--</div>-->
<!--			--><?php //} ?>

		<?php } elseif($template == 'video_gallery')	{ ?>
<!--				<div class="flex-cell flex-sm-6 flex-md-4">--><?php //tst_card_video_post($p); ?><!--</div>-->

		<?php } elseif($template == 'project_card') { ?>
<!--				<div class="layout-section__item border--regular">--><?php //tst_project_card($p); ?><!--</div>-->

		<?php } elseif($template == 'ngo_profile') { ?>
<!--				<div class="layout-section__item border--regular">--><?php //tst_ngo_profile_card($p); ?><!--</div>-->

		<?php } else {
//				$template_args = explode('_', $template);
//				$middle_meta = (isset($template_args[1])) ? trim($template_args[1]) : 'topics';
//				$show_author = (isset($template_args[2])) ? (bool)$template_args[2] : false;
//		?>
<!---->
<!--			<div class="layout-section__item --><?php //echo $class;?><!--">-->
<!--				--><?php //tst_card_general($p, array('middle_meta' => $middle_meta, 'show_author' => $show_author)); ?>
<!--			</div>-->
		<?php
			}
	}

	$result['data'] = ob_get_contents();
	ob_end_clean();

	//check do we have next page
	if(isset($query->max_num_pages) && $paged < $query->max_num_pages) {
		$result['has_more'] = true;
    }

	echo json_encode($result);
	die();

}


/** == Paging == **/

/** Regular paging function **/
function tst_paginate_links(WP_Query $query = null, $echo = true) {

	if(!$query)
		return ;

	$current = ($query->query_vars['paged'] > 1) ? $query->query_vars['paged'] : 1;
	$parts = parse_url(get_pagenum_link(1));

	$pagination = array(
        'base' => trailingslashit(esc_url($parts['host'].$parts['path'])).'%_%',
        'format' => 'page/%#%/',
        'total' => $query->max_num_pages,
        'current' => $current,
		'prev_next' => true,
        'prev_text' => tst_svg_icon('icon-prev', false),
        'next_text' => tst_svg_icon('icon-next', false),
        'end_size' => 4,
        'mid_size' => 4,
        'show_all' => false,
        'type' => 'plain', //list
		'add_args' => array()
    );

    if( !empty($query->query_vars['s']) ) {
        $pagination['add_args'] = array('s' => str_replace(' ', '+', get_search_query()));
	}

	foreach(array('s') as $param) { // Params to remove

		if($param == 's') {
			continue;
		}

		if(isset($_GET[$param]) && !empty($_GET[$param])) {
			$pagination['add_args'] = array_merge($pagination['add_args'], array($param => esc_attr(trim($_GET[$param]))));
		}
	}


    if($echo) {
		echo paginate_links($pagination);
	} else {
		return paginate_links($pagination);
	}
}



/** next/previous post when applicable */
/* function tst_post_nav() {

	$previous = is_attachment() ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);

	if( !get_adjacent_post(false, '', false) && !$previous) { // Don't print empty markup if there's nowhere to navigate
		return;
	}?>

	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e('Post navigation', 'tst'); ?></h1>
		<div class="nav-links">
			<?php previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">&larr;</span>');
			next_post_link('<div class="nav-next">%link</div>', '<span class="meta-nav">&rarr;</span>');?>
		</div>
	</nav>
	<?php
}*/