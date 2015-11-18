<?php

/**
 * Singleton for setting up the integration.
 *
 * Note that it doesn't have to have unique name. Because of autoloading, it will be loaded only once (when this
 * integration plugin is operational).
 */
class WPDDL_Integration_Setup implements WPDDL_Integration_Theme_Settings_Interface{


	private static $instance;
	protected static $templates; // declare a static array to store templates

	private function __clone() {}

	/**
	 * @return void
	 */
	public static function setup(){
		self::$templates = array(
				'template-page.php' => __( 'Template page', 'ddl-layouts' )
		);
		add_filter('ddl-get_cell_categories', array(__CLASS__, 'overrideCellCategoriesOrder'), 99, 1 );
	}

	private function __construct() {
		self::setup();
	}

	/**
	 * @return WPDDL_Integration_Setup
	 */
	public static function getInstance() {
		if( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	/**
	 * Run Integration.
	 *
	 * @return bool|WP_Error True when the integration was successful or a WP_Error with a sensible message
	 *     (which can be displayed to the user directly).
	 */
	public function run() {

		// add Bootstrap support
		add_action( 'wp_enqueue_scripts', array( $this, 'addBootstrapSupport' ), 1 );

		// add CSS modifications
		add_action( 'wp_enqueue_scripts', array( $this, 'CSSModifications' ), 2 );

		// add Admin CSS modifications
		add_action( 'admin_enqueue_scripts', array( $this, 'AdminOverrides' ), 1 );

		$this->addLayoutsSupport();
		$this->tellLayoutsAboutTheme();
		$this->addLayoutCells();
		$this->modifyThemeSettings();

		return true;
	}


	/**
	 * @todo Set supported theme version here.
	 * @return string
	 */
	private function getSupportedThemeVersion() {
		return '';
	}


	/**
	 * Bootstrap Support
	 */
	public function addBootstrapSupport() {

		// @todo prove which parts of bs are needed instead of loading full bs
		wp_register_style(
			'bootstrap',
			plugins_url( '/../public/css/bootstrap.min.css', __FILE__ ),
			array(),
			'3.3.5'
		);

		// @todo probably not needed at all
		wp_register_script(
			'bootstrap',
			plugins_url( '/../public/js/bootstrap.min.js', __FILE__ ),
			array( 'jquery' ),
			'3.3.5',
			true
		);

		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_script( 'bootstrap' );
	}


	/**
	 * CSS Modifications
	 */
	public function CSSModifications() {
		wp_register_style(
			'layouts-theme-integration-frontend',
			plugins_url( '/../public/css/custom-frontend.css', __FILE__ ),
			array(),
			$this->getSupportedThemeVersion()
		);

		wp_enqueue_style( 'layouts-theme-integration-frontend' );

	}

	/**
	 * Admin CSS Modifications
	 */
	public function AdminOverrides() {
		wp_register_style(
			'layouts-theme-integration-backend',
			plugins_url( '/../public/css/custom-backend.css', __FILE__ ),
			array(),
			$this->getSupportedThemeVersion()
		);

		wp_enqueue_style( 'layouts-theme-integration-backend' );


		wp_register_script(
				'layouts-theme-integration-backend',
				plugins_url( '/../public/js/custom-backend.js', __FILE__ ),
				array('jquery', 'underscore', 'ddl_post_edit_page'),
				$this->getSupportedThemeVersion(),
				false
		);

		wp_localize_script('layouts-theme-integration-backend', 'Integration2015', array(
				'templates' => self::$templates
		));

		global $pagenow, $post;

		if (($pagenow == 'post.php' || $pagenow == 'post-new.php') &&
				$post->post_type === 'page' &&
				( is_array(self::$templates) && count(self::$templates) > 0 )
		) {
			wp_enqueue_script('layouts-theme-integration-backend');
		}

	}


	/**
	 * Layouts Support
	 *
	 * @todo Implement theme-specific logic here. For example, you may want to:
	 *     - if theme has it's own loop, replace it by the_ddlayout()
	 *     - remove headers, footer, sidebars, menus and such, if achievable by filters
	 *     - otherwise you will have to resort to something like redirecting templates (see the template router below)
	 *     - add $this->clearContent() to some filters to remove unwanted site structure elements
	 */
	private function addLayoutsSupport() {

		WPDDL_Integration_Theme_Template_Router::get_instance();

        // tell layouts to show all layouts on post/page edit
        add_filter( 'ddl_templates_have_layout', array( $this, 'activateLayoutsSelectForPosts' ) );
        add_filter( 'ddl_no_templates_at_all', array( &$this, 'overrideTemplatesExist') );
        add_filter( 'ddl_check_layout_template_page_exists', array( &$this, 'overrideTemplatesExistPostType'), 10, 2 );
        add_filter( 'ddl-theme_has_page_templates', array( &$this, 'overrideTemplatesExistPage'), 10, 1 );
        add_filter('ddl-determine_main_template', array(&$this, 'override_default_template'), 10, 3);
        add_filter('ddl_template_have_layout', array(&$this, 'setTemplateHaveLayout'), 10, 2 );
	}

	/**
	* Forces to Layouts to check our template files for compatibility 
	* @param boolean $bool
	* @param string $file
	* @return boolean $bool
	**/
	public function setTemplateHaveLayout( $bool, $file ){
            if( in_array( $file, apply_filters('ddl_templates_have_layout', array() ) ) ){
                    return true;
            }

        return $bool;
    }


	/**
	 * Layouts that the active theme supports Layouts.
	 */
	private function tellLayoutsAboutTheme() {
		$theme = wp_get_theme();
		$options_manager = new WPDDL_Options_Manager( 'ddl_template_check' );
		$option_name = 'theme-' . $theme->get('Name');
		if( ! $options_manager->get_options( $option_name ) ) {
			$options_manager->update_options( $option_name, 1 );
		}
	}

    public function override_default_template( $default, $template, $post_type ){
        if( $post_type === 'page' && apply_filters('ddl-template_have_layout', 'page.php') === false ){
            return 'template-page.php';
        }
        return $default;
    }


    /*
     * Layouts searches for template files (files with string "Template Name") to add created
     * Layouts to the Layouts Select menu (for example on page edit). As we cannot
     * change files in the theme we will add support through filter
     */
    public function activateLayoutsSelectForPosts( $templates ) {
        $templates = array(
            'template-page.php'
        );
        return $templates;
    }

    /**
     * @bool boolean whether the theme supports Layouts templates
     */

    public function overrideTemplatesExist( $bool ){
        return false;
    }

    /*
     * $bool boolean
     * $post_type string
     */
    public function overrideTemplatesExistPostType( $bool, $post_type ){
        return true;
    }

    public function overrideTemplatesExistPage( $bool ){
        return true;
    }


	/**
	 * Add custom theme elements to Layouts.
	 *
	 * @todo Setup your custom layouts cell here.
	 */
	private function addLayoutCells() {

		// Custom boilerplate cell
		// @todo Remove this one completely after you are done with it.
		$boilerplate_cell = new WPDDL_Integration_Layouts_Cell_Boilerplate_Custom();
		$boilerplate_cell->setup();

		$sidebar_cell = new WPDDL_Integration_Layouts_Cell_Sidebar();
		$sidebar_cell->setup();

	}


	/**
	 * This function is used to remove all theme settings which are obsolete with the use of Layouts
	 * i.e. "Default Layout" in "Theme Settings"
	 *
	 * @todo You can either use this class for very simple tasks or create classes in application/theme/settings, which
	 * @todo     implement the WPDDL_Integration_Theme_Settings_Interface interface.
	 */
	public function modifyThemeSettings() {
		// ...
	}


	/**
	 * @return string
	 */
	public function clearContent() {
		return '';
	}

	public static function overrideCellCategoriesOrder($categories)
    {
        if( isset( $categories[LAYOUTS_INTEGRATION_THEME_NAME] ) ){
            $tmp = $categories[LAYOUTS_INTEGRATION_THEME_NAME];
            unset($categories[LAYOUTS_INTEGRATION_THEME_NAME]);
            $categories = array_reverse($categories, true);
            $categories[LAYOUTS_INTEGRATION_THEME_NAME] = $tmp;
            $categories = array_reverse($categories, true);
        }

        return $categories;
    }

}