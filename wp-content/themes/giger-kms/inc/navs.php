<?php

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
<?php
    } 
?>

    <nav class="load-more">
        <div class="loader"><?php _e('Loading...', 'tst');?></div>
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
    $paged     	= (isset($_REQUEST['page'])) ? (int)$_REQUEST['page'] : 0;
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
        $template_args = explode('_', $template);
        $middle_meta = (isset($template_args[1])) ? trim($template_args[1]) : 'topics';
        $show_author = (isset($template_args[2])) ? (bool)$template_args[2] : false;
        ?>
			<div class="layout-section__item layout-section__item--card"><?php
			    if( $template == 'search_card' ) {
			        tst_card_search( $p );
			    }
			    else {
			        tst_cell( $p );
			    }
		    ?></div>
        <?php
    }


    $result['data'] = ob_get_contents();
    ob_end_clean();

    //check do we have next page
    if(isset($query->query_vars['has_next_page']) && $query->query_vars['has_next_page'])
        $result['has_more'] = true;


    echo json_encode($result);
    die();
}

