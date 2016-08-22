<?php
/**
 * The template for displaying search forms 
 */
?>
	<form method="get" class="searchform tst-textfield" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<?php tst_svg_icon('icon-search');?>
		<input type="search" class="search-field tst-textfield__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="Найти" autocomplete="off">		
	</form>