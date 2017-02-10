<?php
/**
 * Pagebuilder functions
 **/

add_action( 'init', 'tst_pagebuilder_conifg', 30 );
function tst_pagebuilder_conifg() {
    add_theme_support( 'wds-simple-page-builder' );

    if(function_exists('wds_page_builder_theme_support')) {
        wds_page_builder_theme_support( array(
            'hide_options'    => false,
            'parts_dir'       => 'pagebuilder',
            'parts_prefix'    => 'part',
            'use_wrap'        => 'off',
            'container'       => 'div',
            'container_class' => 'pagebuilder-part',
            'post_types'      => array( 'page', 'landing' ),
        ) );
    }
}

add_filter( 'wds_page_builder_fields_col3-section', 'tst_add_col3_section_field' );
function tst_add_col3_section_field( $fields ) {

    $new_fields = array(
        array(
            'name'    => 'Тип',
            'id'      => 'col3_post_type_col1',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col3_post_id_col1',
            'type'    => 'text',
        ),
        
        array(
            'name'    => 'Тип',
            'id'      => 'col3_post_type_col2',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col3_post_id_col2',
            'type'    => 'text',
        ),
         
        
        array(
            'name'    => 'Тип',
            'id'      => 'col3_post_type_col3',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col3_post_id_col3',
            'type'    => 'text',
        ),
    );
    
    return array_merge( $fields, $new_fields );
}

