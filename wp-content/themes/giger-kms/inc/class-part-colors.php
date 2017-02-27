<?php
/** pagebuilder section colors */

class TST_Part_Colors {
    
    private $_color_schemes = array(
        'tripleblock' => array(
            'white-orange-dbrown',
            'dolive-dgreengray-white',
            'white-dolive-dbrown',
            'olive-dbrown-greengray',
            'white-dolive-dgreengray',
        ),
        'news' => array(
            'white-dgreengray-white-dolive',
            'white-orange-white-dbrown',
        ),
        'help' => array(
            'orange-dbrown-dolive',
            'dolive-dgreengray-olive',
        ),
    );
	
	private static $_instance = null;
	
	private function __construct() {
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
	
    public function get_color_schemes() {
        return $this->_color_schemes;
    }
    
    public function get_pb_select_options( $block_type ) {
        $options = array();
        if( isset( $this->_color_schemes[$block_type] ) ) {
            $schemes = $this->_color_schemes[$block_type];
            foreach( $schemes as $scheme ) {
                $color_parts = explode( '-', $scheme );
                $color_parts_translated = array();
                foreach( $color_parts as $cp ) {
                    $color_parts_translated[] = __( $cp, 'tst' );
                }
                $options[ $scheme ] = implode( '-', $color_parts_translated );
            }
        }
        return $options;
    }
    
    public function get_pb_select_default_option( $block_type ) {
        $ret = '';
        if( isset( $this->_color_schemes[$block_type] ) ) {
            $ret = $this->_color_schemes[$block_type][0];
        }
        return $ret;
    }
    
} //class

TST_Part_Colors::get_instance();
__( 'white', 'tst' );
__( 'orange', 'tst' );
__( 'dbrown', 'tst' );
__( 'dolive', 'tst' );
__( 'dgreengray', 'tst' );
__( 'olive', 'tst' );
__( 'greengray', 'tst' );
