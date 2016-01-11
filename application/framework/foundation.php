<?php

if( !defined('WPDDL_FOUNDATION_ASSETS') ) define('WPDDL_FOUNDATION_ASSETS', WPDDL_CORNERSTONE_URI_FRAMEWORK . DIRECTORY_SEPARATOR . 'assets');

class WPDDL_Integration_Framework_Foundation extends WPDDL_Framework_Integration_Abstract{

    protected function __construct(){
        parent::__construct();
        do_action('ddl-integration_override_before_init', 'foundation', 'Foundation by ZURB');
        //add_action( 'ddl-init_integration_override', array(&$this, 'addCarouselOverrides') );
        $this->addCarouselOverrides();
        add_action( 'ddl-init_integration_override', array(&$this, 'addImageResponsiveSupport') );
        add_filter( 'ddl-get_fluid_type_class_suffix', array( &$this, 'overrideRowSuffix'), 99, 2 );
        add_action( 'wp_head', array(&$this, 'do_header') );
    }

    public function do_header(){
        $this->print_favicon();
    }

    function overrideRowSuffix( $suffix, $mode ){
        return '';
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

    public function addCarouselOverrides(){
        add_filter( 'ddl-carousel_element_tag', array(&$this, 'carousel_element_tag') );
        add_filter( 'ddl-carousel_elements_tag', array(&$this, 'carousel_elements_tag') );
        add_filter( 'ddl-carousel_container_additional_attributes', array(__CLASS__, 'carousel_element_data_attribute') );
        add_filter( 'ddl-carousel_container_class', array(&$this, 'carousel_container_class') );
        add_filter('ddl-carousel_caption_class_attribute', array(&$this, 'carousel_caption_class_attribute'));
        add_filter('ddl-get_carousel_indicators', array(&$this, 'get_carousel_indicators'));
        add_filter( 'ddl-carousel_control_left', array(&$this, 'get_bs_thumbnail_gui') );
        add_filter( 'ddl-carousel_control_right', array(&$this, 'get_bs_thumbnail_gui') );
        add_filter( 'ddl-get_autoplay_script', array(&$this, 'orbit_js_overrides'), 10, 2 );
        add_filter( 'ddl-slider_cell_additional_options', array(&$this, 'add_slider_controls') );
        add_filter( 'ddl-get_carousel_indicators_bottom', array(&$this, 'add_bullets'), 10, 3 );
        add_filter( 'ddl-carousel_active_element_class', array(&$this, 'get_slider_active_class'), 10, 1 );
        add_filter( 'ddl-carousel_element_class_attribute', array(&$this, 'get_slider_element_class_attribute'), 10, 1 );
        add_filter( 'ddl-carousel_items_classes', array(&$this, 'get_slider_items_classes'), 10, 1 );
        add_filter('ddl-get_additional_carousel_top_controls_if', array(&$this, 'get_additional_carousel_top_controls'), 10, 1);
    }

    public function get_additional_carousel_top_controls( $html ){
        ob_start();?>
        <button class="orbit-previous" aria-label="<?php _e('previous','cornerstone'); ?>"><span class="show-for-sr"><?php _e('Previous Slide','cornerstone'); ?></span>&#10094;</button>
        <button class="orbit-next" aria-label="<?php _e('next','cornerstone'); ?>"><span class="show-for-sr"><?php _e('Next Slide','cornerstone'); ?></span>&#10095;</button>
        <?php
        echo ob_get_clean();
    }

    public function add_bullets($html, $unique_id, $count_slides)
    {
        if (get_ddl_field('bullets')):?>
            <nav class="orbit-bullets" id="bullets-<?php $unique_id;?>"> <?php
                for ($i = 0; $i < $count_slides-1; $i++) {
                    echo '<button class="' . ($i == 0 ? 'is-active ' : '') . '" data-slide="' . $i . '"><span class="show-for-sr">slide' . $i . 'details.</span></button>';
                }
                ?></nav>
            <?php
        endif;
    }

    public function get_slider_element_class_attribute( $class ){
        $class .= ' orbit-container';
        return $class;
    }

    public function get_slider_items_classes( $class ){
        $class .= ' orbit-slide ';
        return $class;
    }

    public function get_slider_active_class(){
        return 'is-active';
    }

    public function add_slider_controls(){
        ob_start();?>
        <label class="checkbox" for="<?php the_ddl_name_attr('bullets'); ?>">
            <input type="checkbox" name="<?php the_ddl_name_attr('bullets'); ?>" id="<?php the_ddl_name_attr('bullets'); ?>" value="bullets">
            <?php _e( 'Bullets', 'ddl-layouts' ) ?>
        </label>
        <?php
        echo ob_get_clean();
    }

    public function carousel_element_tag(){
            return 'ul';
    }

    public function carousel_elements_tag(){
        return 'li';
    }

    public static function carousel_element_data_attribute()
    {
        $data = 'data-orbit ';
        $data .= ' data-use-m-u-i="true" ';
        $data .= self::carousel_element_data_options();

        return $data;
    }

    public static function carousel_element_data_options(){
        $autoplay = get_ddl_field('autoplay') ? 'true' : 'false';
        $data = 'data-options="autoPlay:'.$autoplay.';
                  animation:slide;
                  pauseOnHover:'.get_ddl_field('pause').';
                  timerDelay:' . get_ddl_field('interval') . ';
                  resume_on_mouseout: true;
                  bullets:'. get_ddl_field('bullets') .';
                  accessible:true;
                  navButtons:true;
                  boxOfBullets:orbit-bullets;"';

        return $data;
    }

    public function carousel_container_class(){
        return 'orbit';
    }

    public function carousel_caption_class_attribute(){
        return 'orbit-caption';
    }

    public function get_carousel_indicators(){
        return '';
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
        $uri = WPDDL_CORNERSTONE_URI_FRAMEWORK . DIRECTORY_SEPARATOR . 'assets/images/favicons/';
        ob_start();?>
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $uri; ?>favicon.ico">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $uri; ?>favicon.ico">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $uri; ?>favicon.ico">
        <?php
        echo ob_get_clean();
    }

    public function orbit_js_overrides(){
        return '';
    }
}