<?php
/**
 * The template for displaying search forms
 */
?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="search" class="searchform__field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off" placeholder="Найти">
	<button class="searchform__icon" type="submit"><?php tst_svg_icon('icon-search');?></button>
</form>