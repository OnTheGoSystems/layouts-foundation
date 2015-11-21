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
         add_action('init', array('WPDDL_Integration_Framework_Foundation', 'get_instance') );

    }

    /**
	 * Run Integration.
	 *
	 * @return bool|WP_Error True when the integration was successful or a WP_Error with a sensible message
	 *     (which can be displayed to the user directly).
	 */
	public function run() {
		return parent::run();
	}

	public function add_bootstrap_support(){
        return null;
    }

    public function frontend_enqueue(){
            parent::frontend_enqueue();
    }

	/**
	 * @todo Set supported theme version here.
	 * @return string
	 */
	protected function get_supported_theme_version() {
		return '';
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
			'template-page.php' => __( 'Template page', 'ddl-layouts' )
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

		/** @noinspection PhpUndefinedClassInspection */
		WPDDL_Integration_Theme_Template_Router::get_instance();

	}


	/**
	 * Add custom theme elements to Layouts.
	 *
	 * @todo Setup your custom layouts cell here.
	 */
	protected function add_layouts_cells() {

		// Custom boilerplate cell
		// @todo Remove this one completely after you are done with it.
		$boilerplate_cell = new WPDDL_Integration_Layouts_Cell_Boilerplate_Custom();
		$boilerplate_cell->setup();

		$sidebar_cell = new WPDDL_Integration_Layouts_Cell_Sidebar();
		$sidebar_cell->setup();

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
}