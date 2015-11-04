<?php

class Layouts_Integration_Theme_Settings_Blog_Page_Template implements Layouts_Integration_Theme_Settings_Interface {

	public static function setup() {
		// remove the metabox "Blog Page Template"
		remove_meta_box( 'genesis-theme-settings-blogpage', 'toplevel_page_genesis' , 'main' );

		// remove "Blog Page Template" section in "Help" context (btn top right in admin screen)
		$screen = get_current_screen();
		$screen->remove_help_tab( 'toplevel_page_genesis-blog' );
	}

}