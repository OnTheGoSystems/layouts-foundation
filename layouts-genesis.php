<?php
/*
Plugin Name: Layouts : Genesis
Plugin URI: http://wp-types.com/
Description: Layouts Integration for Theme Genesis
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com
Version: 0.0.1
*/

// run before Layouts init which is on 9
add_action( 'init', 'run_layouts_integration', 8 );

if( ! function_exists( 'run_layouts_integration' ) ) {

	function run_layouts_integration() {

		/**
		 * Abort if Genesis is not active
		 */
		if( ! function_exists( 'genesis' ) )
			return;

		/**
		 * Abort if Layouts is not active
		 */
		if( ! defined( 'WPDDL_DEVELOPMENT' ) )
			return;

		/**
		 * Theme Name
		 */
		if( ! defined( 'LAYOUTS_INTEGRATION_THEME_NAME' ) )
			define( 'LAYOUTS_INTEGRATION_THEME_NAME', 'Genesis' );

		/**
		 * Layouts Integration Autoloader
		 */
		if( ! class_exists( 'Layouts_Integration_Autoloader' ) ) {
			require_once( dirname( __FILE__ ) . '/library/layouts/integration/autoloader.php' );
		}

		$autoloader = Layouts_Integration_Autoloader::getInstance();
		$autoloader->addPaths( array(
			dirname( __FILE__ ) . '/application',
			dirname( __FILE__ ) . '/library/layouts/integration',
		) );

		$autoloader->addPrefix( 'Layouts_Integration' );

		/**
		 * Run integration...
		 */
		$integration = Layouts_Integration_Setup::getInstance();
		$integration->run();
	}
}

run_layouts_integration();

