<?php


class Layouts_Integration_Theme_Settings_Breadcrumbs implements Layouts_Integration_Theme_Settings_Interface {

	/**
	 * Genesis has the option to add Breadcrumbs to selected pages/archives. With Layouts we have an
	 * Element to show these Breadcrumbs - so we replace the option in "Theme Settings" with a hint
	 */
	public static function setup() {
		global $wp_meta_boxes;

		if( isset( $wp_meta_boxes['toplevel_page_genesis']['main']['default']['genesis-theme-settings-breadcrumb'] ) ) {
			$wp_meta_boxes['toplevel_page_genesis']['main']['default']['genesis-theme-settings-breadcrumb'] =
				array_merge(
					$wp_meta_boxes['toplevel_page_genesis']['main']['default']['genesis-theme-settings-breadcrumb'],
					array(
						'callback' => array( 'Layouts_Integration_Theme_Settings_Breadcrumbs', 'description' )
					)
				);
		}
	}

	public static function description() {
		require_once( dirname( __FILE__ ) . '/view/breadcrumbs.php' );
	}
}