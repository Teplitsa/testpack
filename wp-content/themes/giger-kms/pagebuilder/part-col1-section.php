<?php
/**
 * Part Name: 1 колонка
 * Description: 1 колонка 
 */

$post_type = wds_page_builder_get_this_part_data( 'col1_post_type_col1' );
$post_name = wds_page_builder_get_this_part_data( 'col1_post_id_col1' );
$post1 = tst_get_pb_post( $post_name, $post_type );

?>

<h2>Секция одного поста. Вид в зависимости от ПТ нужно реализовать внутри секции.</h2>

<div class="column-content flex-grid">
	
	<div style="border:2px solid yellow;" class="flex-md-12">
		<?php if( $post1 ): ?>
			<h1><?php echo $post1->post_title ?></h1>
		<?php endif?>
	</div>
	
</div>