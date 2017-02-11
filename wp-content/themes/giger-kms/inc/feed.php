<?php

/** Customize feed query */
add_action('pre_get_posts', function($query){
    if($query->query_vars['feed']) {
        if (isset($query->query_vars['feed'])){
            $query->query_vars['post_type'] = array( 'post', 'project', 'event', 'landing' ); 
        }
    }

    return $query;
});
