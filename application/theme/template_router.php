<?php

/**
 * Hooks into the template_include filter and select different page template for content that has an Layout assigned.
 */
final class WPDDL_Integration_Theme_Template_Router {


	private static $instance = null;


	public static function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	private function __construct() {
		add_filter( 'template_include', array( $this, 'template_include' ) );
	}


	public function template_include( $template ) {

		if( is_ddlayout_assigned() ) {
			$template_file = null;
			/*if( is_single() ) {
				$template_file = 'template-single.php';
			} else */ if( is_page() ) {
				$template_file = 'template-page.php';
			} /*else if( is_archive() ) {
				$template_file = 'template-archive.php';
			} else if( is_404() ) {
				$template_file = 'template-404.php';
			} else {
				$template_file = 'template-index.php';
			} */

			if( null != $template_file ) {
				$template = dirname( __FILE__ ) . '/view/' . $template_file;
			}
		}

		return $template;
	}
}