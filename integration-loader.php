<?php
/**
 * Integration loader. Determines if the integration should execute and if yes, execute it properly.
 *
 * When this file is loaded, we already know Layouts are active, theme integration support is loaded and it has
 * correct API version.
 *
 * See WPDDL_Theme_Integration_Abstract for details.
 *
 * @todo This class name has to be unique. Use pattern "WPDDL_Theme_Name_Integration".
 */
final class WPDDL_Integration_Boilerplate extends WPDDL_Theme_Integration_Abstract {

	/**
	 * Theme-specific initialization.
	 */
	protected function initialize() {

		// Setup the autoloader
		$autoloader = WPDDL_Theme_Integration_Autoloader::getInstance();
		$autoloader->addPath( dirname( __FILE__ ) . '/application' );

		// Run the integration setup
		$integration = WPDDL_Integration_Setup::getInstance();
		$integration->run();
	}


	/**
	 * Determine whether the expected theme is active and the integration can begin.
	 *
	 * @return bool
	 * @todo Replace this by your custom logic.
	 */
	protected function is_theme_active() {
		return function_exists( 'twentyfifteen_setup' );
	}


	/**
	 * Name of the supported theme. It will be used as an unique identifier of the integration plugin.
	 *
	 * @return string Theme name
	 * @todo Replace this by relevant value.
	 */
	protected function get_theme_name() {
		return 'Twenty Fifteen';
	}
}


// @todo Update the class name.
WPDDL_Integration_Boilerplate::get_instance();