<?php
/*
Plugin Name: Toolset Cornerstone Integration
Plugin URI: http://wp-types.com/
Description: Layouts Integration for Cornerstone
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com
Version: 1.1
*/
if( !defined('WPDDL_CORNERSTONE_ABS') ) define('WPDDL_CORNERSTONE_ABS', dirname(__FILE__) );
if( !defined('WPDDL_CORNERSTONE_ABS_PUBLIC') ) define('WPDDL_CORNERSTONE_ABS_PUBLIC', dirname(__FILE__) . '/public' );
if( !defined('WPDDL_CORNERSTONE_ABS_APP') ) define('WPDDL_CORNERSTONE_ABS_APP', dirname( __FILE__ ) . '/application' );
if( !defined('WPDDL_CORNERSTONE_ABS_FRAMEWORK') ) define('WPDDL_CORNERSTONE_ABS_FRAMEWORK', WPDDL_CORNERSTONE_ABS_APP . '/framework' );
if( !defined('WPDDL_CORNERSTONE_ABS_THEME') ) define('WPDDL_CORNERSTONE_ABS_THEME', dirname( __FILE__ ) . '/application/theme' );
if( !defined('WPDDL_CORNERSTONE_ABS_TPLS') ) define('WPDDL_CORNERSTONE_ABS_TPLS', dirname( __FILE__ ) . '/application/theme/view' );

if( !defined('WPDDL_CORNERSTONE_URI') ) define('WPDDL_CORNERSTONE_URI', plugins_url( basename(dirname(__FILE__)), dirname(__FILE__) ) );
if( !defined('WPDDL_CORNERSTONE_URI_APP') ) define('WPDDL_CORNERSTONE_URI_APP', plugins_url( basename(WPDDL_CORNERSTONE_ABS_APP), WPDDL_CORNERSTONE_ABS_APP ) );
if( !defined('WPDDL_CORNERSTONE_URI_PUBLIC') ) define('WPDDL_CORNERSTONE_URI_PUBLIC', plugins_url( basename(WPDDL_CORNERSTONE_ABS_PUBLIC), WPDDL_CORNERSTONE_ABS_PUBLIC ) );
if( !defined('WPDDL_CORNERSTONE_URI_TPLS') ) define('WPDDL_CORNERSTONE_URI_TPLS', plugins_url( basename(WPDDL_CORNERSTONE_ABS_TPLS), WPDDL_CORNERSTONE_ABS_TPLS ) );
if( !defined('WPDDL_CORNERSTONE_URI_FRAMEWORK') ) define('WPDDL_CORNERSTONE_URI_FRAMEWORK', plugins_url( basename(WPDDL_CORNERSTONE_ABS_FRAMEWORK), WPDDL_CORNERSTONE_ABS_FRAMEWORK ) );


if( !defined('TOOLSET_INTEGRATION_PLUGIN_THEME_NAME') ){
    define('TOOLSET_INTEGRATION_PLUGIN_THEME_NAME','Cornerstone, for WordPress');
}
/**
 * Main plugin class.
 *
 * Checks for Layouts compatibility and ensures the integration begins at the right time.
 *
 */
class WPDDL_Cornerstone_Integration_Loader {

	private static $instance = null;

	public static function getInstance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	private function __construct() {
		add_action( 'wpddl_theme_integration_support_ready', array( $this, 'begin_loading' ), 10, 2 );
		add_action( 'init', array( $this, 'check_layouts' ) );
	}


	private function __clone() {}


	/**
	 * We need to continue only after the integration support has been loaded and we check the API version matches.
	 *
	 * This action should be fired at some point during the 'init' action.
	 *
	 * @param string $layouts_version
	 * @param int $integration_support_version
	 */
	public function begin_loading(
		/** @noinspection PhpUnusedParameterInspection */ $layouts_version, $integration_support_version )
	{
		$supported_integration_api_version = 1;
		if( version_compare( $layouts_version, '1.4.5' ) !== -1 && $supported_integration_api_version == $integration_support_version ) {
			require_once 'integration-loader.php';

			// We need to manually setup plugin name, since it depends on the main file name.
			// @todo Update class name.
			$loader = WPDDL_Integration_Cornerstone::get_instance();
			$loader->set_plugin_base_name( plugin_basename( __FILE__ ) );
			//WPDDL_Integration_Framework_Foundation::get_instance();
		} else {
			add_action( 'admin_init', array( $this, 'deactivate_plugin' ) );
			add_action( 'admin_notices', array( $this, 'print_api_version_mismatch_message' ) );
		}
	}


	/**
	 * Check that Layouts is active and fail+deactivate this plugin if not.
	 */
	public function check_layouts() {
		// We're doing this only in admin screen because we need to display the message.
		if( is_admin() && !defined( 'WPDDL_VERSION' ) ) {
			add_action( 'admin_init', array( $this, 'deactivate_plugin' ) );
			add_action( 'admin_notices', array( $this, 'print_layouts_inactive_message' ) );
		}
	}


	public function deactivate_plugin() {
		deactivate_plugins( plugin_basename( __FILE__ ), false, false );
	}


	public function print_layouts_inactive_message() {
		printf( '<div class="error"><p>%s</p></div>', __( 'Boilerplate integration plugin requires Layouts to be active.', 'ddl-layouts' ) );
	}


	public function print_api_version_mismatch_message() {
		printf(
			'<div class="error"><p>%s</p></div>',
			__( 'Theme integration plugin does not support older versions of Layouts. Please update to the latest version and try again.', 'ddl-layouts' )
		);
	}

}

// @todo Update class name.
WPDDL_Cornerstone_Integration_Loader::getInstance();