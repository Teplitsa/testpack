<?php
/* actions on posts CRUD */

/** Remove unused metaboxes iframe */
add_action( 'add_meta_boxes' , 'tst_remove_wrong_metaboxes', 30 );
function tst_remove_wrong_metaboxes() {


	remove_meta_box('leyka_campaign_embed', 'leyka_campaign', 'normal');

	remove_meta_box('frm_create_entry', 'post', 'side');
	remove_meta_box('frm_create_entry', 'page', 'side');
	remove_meta_box('frm_create_entry', 'item', 'side');

	remove_meta_box('members-cp', 'post', 'advanced');
	remove_meta_box('members-cp', 'page', 'advanced');
	remove_meta_box('members-cp', 'item', 'advanced');


}


/** == Landings == **/
add_action('save_post', 'tst_reset_color_scheme', 30, 2);
function tst_reset_color_scheme($post_id, $post){

	TST_Color_Schemes::clear_scheme($post_id);

	update_post_meta($post_id, 'landing_reset_colors', '');
}


/** == Transients == **/

/* delete transients on post save */
add_action('wp_trash_post', 'tst_delete_post_related_transients', 20);
add_action('save_post', 'tst_delete_post_related_transients', 26, 2);
function tst_delete_post_related_transients($post_id, $post = null){

	if(!$post)
		$post = get_post($post_id);


	//thumb cache
	delete_post_meta($post_id, 'post_thumbnail_markup_lazy');
	delete_post_meta($post_id, 'post_video_thumbnail_markup_lazy');


}

/** == Attachments (bublications, reports etc..) == **/
add_action( 'edit_attachment', 'tst_change_attachment_date', 20, 1 );
function tst_change_attachment_date( $post_id ){
    remove_action( 'edit_attachment', 'tst_change_attachment_date', 20, 1 );
    
    $post_date = get_post_meta( $post_id, 'file_publication_date', true );
    
    if( $post_date ) {
        
        $post_date = date( 'Y-m-d', $post_date );
        
        wp_update_post( array(
            'ID' => $post_id,
            'post_date' => $post_date,
            'post_date_gmt' => get_gmt_from_date( $post_date ),
        ) );
        
    }
    
    add_action( 'edit_attachment', 'tst_change_attachment_date', 20, 1 );
}
