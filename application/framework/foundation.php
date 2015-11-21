<?php

if( !defined('WPDDL_FOUNDATION_ASSETS') ) define('WPDDL_FOUNDATION_ASSETS', WPDDL_CORNERSTONE_URI_FRAMEWORK . DIRECTORY_SEPARATOR . 'assets');

class WPDDL_Integration_Framework_Foundation extends WPDDL_Framework_Integration_Abstract{

    protected function __construct(){
        parent::__construct();
        do_action('ddl-integration_override_before_init', 'foundation', 'Foundation by ZURB');
        add_action( 'ddl-init_integration_override', array(&$this, 'addImageResponsiveSupport') );
    }

    public function getColumnPrefix(){
        return array('small-', 'medium-', 'large-');
    }

    public function get_additional_column_class(){
        return 'columns';
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
}