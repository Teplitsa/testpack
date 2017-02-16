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

function tst_wds_get_field_name_prefix( $regex, $filter ) {
    preg_match( $regex, $filter, $matches);
    $name_prefix = '';
    if( isset( $matches[1] ) && $matches[1] ) {
        $name_prefix = $matches[1];
        $name_prefix = str_replace( '-', '_', $name_prefix );
    }
    return $name_prefix;
}

add_filter( 'wds_page_builder_fields_col3-6-3-3-section', 'tst_add_col3_section_field' );
add_filter( 'wds_page_builder_fields_col3-3-3-6-section', 'tst_add_col3_section_field' );
function tst_add_col3_section_field( $fields ) {

    $name_prefix = tst_wds_get_field_name_prefix( '/(col3-\d+-\d+-\d+)-section/', current_filter() );

    $new_fields = $name_prefix ? array(
        array(
            'name'    => 'Тип',
            'id'      => $name_prefix . '_post_type_col1',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => $name_prefix . '_post_id_col1',
            'type'    => 'text',
        ),
        
        array(
            'name'    => 'Тип',
            'id'      => $name_prefix . '_post_type_col2',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => $name_prefix . '_post_id_col2',
            'type'    => 'text',
        ),
         
        
        array(
            'name'    => 'Тип',
            'id'      => $name_prefix . '_post_type_col3',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => $name_prefix . '_post_id_col3',
            'type'    => 'text',
        ),
    ) : array();
    
    return array_merge( $fields, $new_fields );
}

add_filter( 'wds_page_builder_fields_col2-6-6-section', 'tst_add_col2_section_field' );
function tst_add_col2_section_field( $fields ) {

    $new_fields = array(
        array(
            'name'    => 'Тип',
            'id'      => 'col2_post_type_col1',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col2_post_id_col1',
            'type'    => 'text',
        ),

        array(
            'name'    => 'Тип',
            'id'      => 'col2_post_type_col2',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col2_post_id_col2',
            'type'    => 'text',
        ),
         
    );

    return array_merge( $fields, $new_fields );
}

add_filter( 'wds_page_builder_fields_col1-section', 'tst_add_col1_section_field' );
function tst_add_col1_section_field( $fields ) {

    $new_fields = array(
        array(
            'name'    => 'Тип',
            'id'      => 'col1_post_type_col1',
            'type'    => 'text',
        ),
        array(
            'name'    => 'Имя или ID записи',
            'id'      => 'col1_post_id_col1',
            'type'    => 'text',
        ),
    );

    return array_merge( $fields, $new_fields );
}


