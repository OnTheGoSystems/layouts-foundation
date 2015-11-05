<?php
/*
Plugin Name: Layouts : Integration boilerplate plugin
Plugin URI: http://wp-types.com/
Description: Layouts Integration for <insert theme name>
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com
Version: 0.1
*/

// @todo Setup the plugin header.
// @todo Rename this file to match the plugin slug.


// @todo Update function name
add_action( 'wpddl_theme_integration_support_ready', 'wpddl_integration_boilerplate_init', 10, 2 );


/**
 * We need to continue only after the integration support has been loaded and we check the API version matches.
 *
 * This action should be fired at some point during the 'init' action.
 *
 * @param string $layouts_version
 * @param int $integration_support_version
 * @todo This function name has to be unique. Use pattern "wpddl_integration_{$theme_name}_init".
 */
function wpddl_integration_boilerplate_init(
	/** @noinspection PhpUnusedParameterInspection */ $layouts_version, $integration_support_version )
{
	$supported_integration_api_version = 1;
	if( $supported_integration_api_version == $integration_support_version ) {
		require_once 'integration-loader.php';
	}
}