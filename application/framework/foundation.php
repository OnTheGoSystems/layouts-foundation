<?php

if( !defined('WPDDL_FOUNDATION_ASSETS') ) define('WPDDL_FOUNDATION_ASSETS', WPDDL_WPFORGE_URI_FRAMEWORK . DIRECTORY_SEPARATOR . 'assets');

class WPDDL_Integration_Framework_Foundation extends WPDDL_Framework_Integration_Abstract{

    protected function __construct(){
        parent::__construct();
        do_action('ddl-integration_override_before_init', 'foundation', 'Foundation by ZURB');
        $this->add_grid_override_hooks();
        add_action( 'ddl-init_integration_override', array(&$this, 'addImageResponsiveSupport') );
        add_filter( 'ddl-get_fluid_type_class_suffix', array( &$this, 'overrideRowSuffix'), 99, 2 );
        add_action( 'wp_head', array(&$this, 'do_header') );
    }

    private function add_grid_override_hooks(){
	    add_filter( 'ddl-get_'.WPDDL_Options::COLUMN_PREFIX.'_default_value', array($this, 'get_column_prefix_default_option'), 99);
	    add_filter( 'ddl-get_column_prefix_small', array( $this, 'get_column_prefix_small' ), 11, 1 );
	    add_filter( 'ddl-get_column_prefix_medium', array( $this, 'get_column_prefix_medium' ), 11, 1 );
	    add_filter( 'ddl-get_column_prefix_large', array( $this, 'get_column_prefix_large' ), 11, 1 );
	    add_filter( 'ddl-get_offset_prefix', array( $this, 'get_offset_prefix' ), 11, 1  );
	    add_filter( 'ddl-get_framework_prefixes_data', array( $this, 'get_framework_prefixes_data' ), 11, 1 );
	    add_filter( 'ddl-get_container_fluid_class', array($this, 'get_container_fluid_class'), 10, 2 );
    }

    public function get_column_prefix_default_option( ){
	    return array( WPDDL_Options::COLUMN_PREFIX => 'small-' );
    }

    public function get_offset_prefix( ){
        return 'push-';
    }

    public function get_container_fluid_class( $class, $mode ){
        return 'container';
    }

    public function do_header(){
        $this->print_favicon();
    }

    function overrideRowSuffix( $suffix, $mode ){
        return '';
    }

    public function getColumnPrefix( ){
        return 'small-';
    }

	function get_column_prefix_small() {
		return 'small-';
	}

	function get_column_prefix_large() {
		return 'large-';
	}

	function get_column_prefix_medium() {
		return 'medium-';
	}

    public function get_additional_column_class(){
        return ' columns';
    }

    public function addImageResponsiveSupport(){
        add_filter('ddl-get_thumbnail_class', array(&$this, 'get_thumbnail_class'));
        add_filter( 'ddl-get_bs_thumbnail_gui', array(&$this, 'get_bs_thumbnail_gui') );
        add_filter( 'ddl-get_image_effects_gui', array(&$this, 'get_bs_thumbnail_gui') );
        add_filter('ddl-get_image_box_image_data_attributes', array(&$this, 'overrideImageData'), 99, 3);
    }

    public function get_thumbnail_class(){
        return 'th';
    }

    public function get_bs_thumbnail_gui(){
        return '';
    }

    public function get_image_effects_gui(){
        return '';
    }

    public function overrideImageData( $data_string, $size, $instance ){

        $large = get_attachment_field_url('box_image', 'large');
        $original = get_attachment_field_url('box_image', 'original');
        $medium = get_attachment_field_url('box_image', 'medium');
        $thumbnail = get_attachment_field_url('box_image', 'thumbnail');

        $data_string .= 'data-interchange="['.$original[0].', (default)], ['.$large[0].', (large)], ['.$medium[0].', (medium)], ['.$thumbnail[0].', (small)]"';
        return $data_string;
    }

    private function print_favicon(){
        $uri = WPDDL_WPFORGE_URI_FRAMEWORK . DIRECTORY_SEPARATOR . 'assets/images/favicons/';
        ob_start();?>
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $uri; ?>favicon.ico">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $uri; ?>favicon.ico">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $uri; ?>favicon.ico">
        <?php
        echo ob_get_clean();
    }

    public function get_framework_prefixes_data( $dummy = array() ){
	    return array(
		    'small-' => array('label' => __('Small', 'ddl-layouts'), 'size' => __('768px and up', 'ddl-layouts')  ),
		    'medium-' => array('label' => __('Medium', 'ddl-layouts'), 'size' => __('992px and up', 'ddl-layouts') ),
		    'large-' => array('label' => __('Large', 'ddl-layouts'), 'size' => __('1200px and up', 'ddl-layouts') )
	    );
    }
}