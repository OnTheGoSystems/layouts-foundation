<?php

/**
 * Singleton for setting up the integration.
 *
 * Note that it doesn't have to have unique name. Because of autoloading, it will be loaded only once (when this
 * integration plugin is operational).
 */
class WPDDL_Integration_Setup {

	private static $instance;

	private function __clone() {}
	private function __construct() {}

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
	 */
	public function run() {

		// add Bootstrap support
		add_action( 'wp_enqueue_scripts', array( $this, 'addBootstrapSupport' ), 1 );

		// add CSS modifications
		add_action( 'wp_enqueue_scripts', array( $this, 'CSSModifications' ), 2 );

		// add Admin CSS modifications
		add_action( 'admin_enqueue_scripts', array( $this, 'AdminCSSModifications' ), 2 );

		$this->addLayoutsSupport();
		$this->tellLayoutsAboutTheme();
		$this->addLayoutCells();
		$this->modifyThemeSettings();
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
	public function AdminCSSModifications() {
		wp_register_style(
			'layouts-theme-integration-backend',
			plugins_url( '/../public/css/custom-backend.css', __FILE__ ),
			array(),
			$this->getSupportedThemeVersion()
		);

		wp_enqueue_style( 'layouts-theme-integration-backend' );
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

}