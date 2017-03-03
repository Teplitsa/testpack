<?php
/**
 * Part Name: Подзаголовок
 * Description: Подзаголовок секций
 */

$prefix = "subtitle_";

$subtitle = wds_page_builder_get_this_part_data($prefix.'subtitle_text'); //direct-revers

if(empty($subtitle))
	return;

?>
<div class="subtitle-block container">
	<h3 class="subtitle-block__text"><?php echo apply_filters('tst_the_title', $subtitle); ?></h3>
</div>