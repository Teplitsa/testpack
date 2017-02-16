<?php
/**
 * Part Name: 3 колонки 6-3-3
 * Description: 3 колонки 6-3-3 
 */

$post_type = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_type_col1' );
$post_name = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_id_col1' );
$post1 = tst_get_pb_post( $post_name, $post_type );

$post_type = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_type_col2' );
$post_name = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_id_col2' );
$post2 = tst_get_pb_post( $post_name, $post_type );

$post_type = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_type_col3' );
$post_name = wds_page_builder_get_this_part_data( 'col3_6_3_3_post_id_col3' );
$post3 = tst_get_pb_post( $post_name, $post_type );

?>

<h2>Секция из трех каких-то постов, где типы постов и их ID (или name) указаны в настройках секции.</h2>

<div class="column-content flex-grid">
	
	<div style="border:2px solid red;" class="flex-md-6">
		<?php if( $post1 ): ?>
			<h1><?php echo $post1->post_title ?></h1>
		<?php endif?>
	</div>
	
	<div style="border:2px solid green;" class="flex-md-3">
		<?php if( $post2 ): ?>
			<h1><?php echo $post2->post_title ?></h1>
		<?php endif?>
	</div>

	<div style="border:2px solid magenta;" class="flex-md-3">
		<?php if( $post3 ): ?>
			<h1><?php echo $post3->post_title ?></h1>
		<?php endif?>
	</div>
	
</div>