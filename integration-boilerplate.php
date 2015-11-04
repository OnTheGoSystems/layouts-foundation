<?php
/*
Plugin Name: Layouts : Integration boilerplate plugin
Plugin URI: http://wp-types.com/
Description: Layouts Integration for <insert theme name>
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com
Version: 0.0.1
*/

// @todo Setup the plugin header.
// @todo Rename this file to match the plugin slug.

/**
 * @todo This class name has to be unique. Use pattern "WPDDL_Theme_Name_Integration".
 */
final class WPDDL_Integration_Boilerplate {

	private static $instance = null;

	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	private function __construct() {
		add_action( 'wpddl_theme_integration_support_ready', array( $this, 'initialize' ) );
	}


	private function initialize() {
		if( ! $this->is_theme_active() ) {
			return;
		}

		// Abort if Layouts is not active
		if( ! defined( 'WPDDL_DEVELOPMENT' ) ) {
			return;
		}

		// Abort if another integration is already active
		if( defined( 'LAYOUTS_INTEGRATION_THEME_NAME' ) ) {
			return;
		}

		// Now it's official.
		define( 'LAYOUTS_INTEGRATION_THEME_NAME', $this->get_theme_name() );

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
	private function is_theme_active() {
		return function_exists( 'genesis' );
	}


	/**
	 * Name of the theme. It will be used as an unique identifier of the integration plugin.
	 *
	 * @return string Theme name
	 * @todo Replace this by relevant value.
	 */
	private function get_theme_name() {
		return 'Genesis';
	}
}


// @todo Update the class name.
WPDDL_Integration_Boilerplate::get_instance();