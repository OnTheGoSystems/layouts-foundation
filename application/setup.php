<?php

/**
 * Singleton for setting up the integration.
 *
 * Note that it doesn't have to have unique name. Because of autoloading, it will be loaded only once (when this
 * integration plugin is operational).
 *
 * @todo Take look at the parent class, explore it's code and figure out if anything needs overriding.
 */
/** @noinspection PhpUndefinedClassInspection */
class WPDDL_Integration_Setup extends WPDDL_Theme_Integration_Setup_Abstract {


	protected function __construct(){
         add_action('init', array(&$this, 'run_foundation') );
    }

    public function run_foundation(){
        WPDDL_Integration_Framework_Foundation::get_instance();
    }

    public function run(){
        $this->set_layouts_path( dirname( dirname( __FILE__) ) . DIRECTORY_SEPARATOR . 'public/layouts' );
        parent::run();
        $this->add_shortcodes();
        return true;
    }

	public function add_bootstrap_support(){
        return null;
    }

    public function frontend_enqueue(){
            parent::frontend_enqueue();
    }

    public function admin_enqueue(){
        parent::admin_enqueue();
        $this->register_and_enqueue_cells_scripts();
    }

    private function register_and_enqueue_cells_scripts(){
        wp_register_script(
            'layouts-cornerstone-custom-js',
            WPDDL_CORNERSTONE_URI. DIRECTORY_SEPARATOR . $this->get_custom_backend_js_path(),
            array( 'jquery', 'underscore'),
            $this->get_supported_theme_version(),
            true
        );

        wp_register_script(
            'ddl-orbit-slider-cell',
            WPDDL_CORNERSTONE_URI_PUBLIC. DIRECTORY_SEPARATOR . 'js/ddl-orbit-slider-cell.js',
            array( 'jquery', 'underscore'),
            $this->get_supported_theme_version(),
            true
        );

        wp_localize_script('ddl-orbit-slider-cell', 'CornerstoneOrbit', array(
                'Settings' => array(
                    'strings' => array(
                        'select_default' => __('Select', 'ddl-layouts')
                    )
                )
            )
        );

        global $pagenow, $post;

        // Enqueue only on post edit/new post page
        if ( ( $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'dd_layouts_edit' )

        ) {
            wp_enqueue_script('ddl-orbit-slider-cell');
            wp_enqueue_script('layouts-cornerstone-custom-js');
        }
    }

	/**
	 * @todo Set supported theme version here.
	 * @return string
	 */
	protected function get_supported_theme_version() {
		return '4.0.0';
	}


	/**
	 * Build URL of an resource from path relative to plugin's root directory.
	 *
	 * @param string $relative_path Some path relative to the plugin's root directory.
	 * @return string URL of the given path.
	 */
	protected function get_plugins_url( $relative_path ) {
		return plugins_url( '/../' . $relative_path , __FILE__ );
	}


	/**
	 * Get list of templates supported by Layouts with this theme.
	 *
	 * @return array Associative array with template file names as keys and theme names as values.
	 * @todo Update the array of templates according to what the integration plugin offers
	 */
	protected function get_supported_templates() {
		return array(
            $this->getPageDefaultTemplate() => __( 'Template page', 'ddl-layouts' ),
            'template-index.php' => __( 'Layouts archive template', 'ddl-layouts' )
		);
	}


	/**
	 * Layouts Support
	 *
	 * @todo Implement theme-specific logic here. For example, you may want to:
	 *     - if theme has it's own loop, replace it by the_ddlayout()
	 *     - remove headers, footer, sidebars, menus and such, if achievable by filters
	 *     - otherwise you will have to resort to something like redirecting templates (see the template router below)
	 *     - add $this->clear_content() to some filters to remove unwanted site structure elements
	 */
	protected function add_layouts_support() {

		parent::add_layouts_support();
        $this->layouts_menu_cells_overrides();
		/** @noinspection PhpUndefinedClassInspection */
		WPDDL_Integration_Theme_Template_Router::get_instance();

	}

    protected function layouts_menu_cells_overrides(){
        add_filter('ddl-menu_has_container', array(&$this, 'return_false'), 99, 2 );
        add_filter('ddl-wrap_menu_start', array(&$this, 'wrap_menu_start'), 10, 3 );
        add_filter('ddl-wrap_menu_end', array(&$this, 'wrap_menu_end'), 10, 3 );
        add_filter('ddl-menu_toggle_controls', array(&$this, 'clear_content'), 10, 3 );
        add_filter( 'ddl-get_menu_walker', array(&$this, 'get_menu_walker'), 10, 2 );
        add_action( 'ddl-menu_additional_fields', array(&$this, 'menu_additional_fields') );
        add_filter( 'ddl-get_menu_class', array(&$this, 'add_menu_class_if'), 10, 2 );
        add_filter( 'ddl-menu-walker-args', array(&$this, 'add_menu_args'), 10 );
        add_filter( 'ddl-get_cell_element_classes', array(&$this, 'add_topbar_class'), 999, 3 );
      //  add_filter( 'ddl-additional_cells_tag_attributes_render', array(&$this, 'add_menu_style'), 999, 3 );
    }

    public function add_topbar_class( $classes, $renderer, $cell ){
        if( apply_filters('ddl-is_cell_and_of_type', $cell, 'menu-cell') &&
            $cell->get_content_field_value('menu_dir') === 'nav-horizontal' &&
            $cell->get_content_field_value('topbar')
         ){
            $classes .= ' top-bar-section';
            $classes .= ' top-bar-'.$cell->get_content_field_value( 'menu_align' );
        }
        return $classes;
    }

    public function add_menu_style( $props, $renderer, $cell ){
        if( apply_filters('ddl-is_cell_and_of_type', $cell, 'menu-cell') &&
            $cell->get_content_field_value('menu_dir') === 'nav-horizontal' &&
            $cell->get_content_field_value('topbar') &&
            $cell->get_content_field_value('menu_align') == 'left'

        ){
            $props .= ' style="float:left!important;" ';
        }
        return $props;
    }

    public function wrap_menu_start( $tag, $menu_dir, $object ){
        if( get_ddl_field('menu_dir') === 'nav-horizontal' && get_ddl_field('topbar') ){
            return '';
        }
        return $tag;
    }

    public function wrap_menu_end( $tag, $menu_dir, $object ){
        if( get_ddl_field('menu_dir') === 'nav-horizontal' && get_ddl_field('topbar') ){
            return '';
        }
        return $tag;
    }

    public function add_menu_class_if( $class, $menu ){
        $align = get_ddl_field('menu_align');

        if( is_null( $align  ) === false ){
            $class = ' top-bar-'.$align;
        }

        if( get_ddl_field('menu_dir') === 'nav-horizontal' && !get_ddl_field('topbar') ){
            $class .= ' inline-list';
        }

        elseif( get_ddl_field('topbar') ){
            $class .= ' menu dropdown';
        }

        return $class;
    }

    public function add_menu_args( $args ){
        if( get_ddl_field('menu_dir') === 'nav-horizontal' && !get_ddl_field('topbar') ){
            $args['flying_class'] = 'no';
        } elseif( get_ddl_field('topbar') ){
            $args['items_wrap'] = '<ul id="%1$s" class="%2$s" data-dropdown-menu>%3$s</ul>';
        }
        return $args;
    }

    public function return_false( $bool, $menu ){
        if( get_ddl_field('menu_dir') === 'nav-horizontal' ){
            return false;
        } else {
            return true;
        }
    }

    public function add_shortcodes() {
        // post-template
        $post_content = new WPDDL_Integration_Theme_Shortcode_Post_Template();
        $post_content->setup();

        return $this;
    }

	/**
	 * Add custom theme elements to Layouts.
	 *
	 * @todo Setup your custom layouts cell here.
	 */
	protected function add_layouts_cells() {
        $sidebar_cell = new WPDDL_Integration_Layouts_Cell_Site_title();
        $sidebar_cell->setup();

        $navigation_cell = new WPDDL_Integration_Layouts_Cell_Navigation();
        $navigation_cell->setup();

        $sidebar_cell = new WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar();
        $sidebar_cell->setup();

        if( function_exists( 'Orbit' ) ){
            $orbit_slider = new WPDDL_Integration_Layouts_Cell_Orbit_Slider();
            $orbit_slider->setup();
        }

        $footer_cell = new WPDDL_Integration_Layouts_Cell_Cornerstone_footer();
        $footer_cell->setup();
	}

    protected function add_layout_row_types() {
        // Site Header
        $cornerstone_header = new WPDDL_Integration_Layouts_Row_Cornerstone_header();
        $cornerstone_header->setup();

        $cornerstone_footer = new WPDDL_Integration_Layouts_Row_Cornerstone_footer();
        $cornerstone_footer->setup();
    }


	/**
	 * This method can be used to remove all theme settings which are obsolete with the use of Layouts
	 * i.e. "Default Layout" in "Theme Settings"
	 *
	 * @todo You can either use this class for very simple tasks or create dedicated classes in application/theme/settings.
	 */
	protected function modify_theme_settings() {
		// ...
	}

    public function get_menu_walker( $walker, $style ){
        $is_top = get_ddl_field('menu_dir') === 'nav-horizontal' && get_ddl_field('topbar');
        if ( class_exists( 'WPDDL_Theme_Cornerstone_Menu_Walker' ) ){
            $walker = new WPDDL_Theme_Cornerstone_Menu_Walker(
                array(
                    'in_top_bar' => $is_top,
                    'item_type' => 'li'
                )
            );
            return $walker;
        }
        return null;
    }

    public function menu_additional_fields(){
        ob_start();?>
        <p>
            <label for="<?php the_ddl_name_attr('topbar'); ?>" class="ddl-manual-width-190"><?php _e('Cornerstone top menu', 'ddl-layouts'); ?></label>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="<?php the_ddl_name_attr('topbar'); ?>" value="1" checked />
            <span><i class="fa fa-question-circle question-mark-and-the-mysterians js-ddl-question-mark" data-tooltip-text="<?php _e( 'Allows to render Cornerstone Foundation based top menu bar inside a Cornerstone header row.', 'ddl-layouts' ) ?>"></i></span>
        </p>
        <p>
            <label for="<?php the_ddl_name_attr('menu_align'); ?>" class="ddl-manual-width-190"><?php _e('Alignment', 'ddl-layouts'); ?></label>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="<?php the_ddl_name_attr('menu_align'); ?>" value="left" checked /><?php _e('Align left', 'ddl-layouts');?> &nbsp;
            <input type="radio" name="<?php the_ddl_name_attr('menu_align'); ?>" value="right" /><?php _e('Align right', 'ddl-layouts');?>
        </p>
        <?php
        echo ob_get_clean();
    }
}