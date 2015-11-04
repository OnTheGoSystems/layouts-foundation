<?php

class Layouts_Integration_Theme_Settings_Default_Layouts implements Layouts_Integration_Theme_Settings_Interface {

	public static function setup() {
		// remove the metabox "Default Layouts"
		remove_meta_box( 'genesis-theme-settings-layout', 'toplevel_page_genesis' , 'main' );

		// remove "Default Layouts" section in "Help" context (btn top right in admin screen)
		$screen = get_current_screen();
		$screen->remove_help_tab( 'toplevel_page_genesis-layout' );
	}

}