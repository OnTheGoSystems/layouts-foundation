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

	public static function locate_template($template_names, $load = false, $require_once = true ) {
		$located = '';
        $template_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'view';
		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists($template_path . '/' . $template_name)) {
				$located = $template_path . '/' . $template_name;
				break;
			} elseif ( file_exists($template_path . '/' . $template_name) ) {
				$located = $template_path . '/' . $template_name;
				break;
			}
		}

		if ( $load && '' != $located )
			load_template( $located, $require_once );

		return $located;
	}

    public static function get_header( $name = null ) {

        do_action( 'get_header', $name );

        $templates = array();
        $name = (string) $name;
        if ( '' !== $name )
            $templates[] = "header-{$name}.php";

        $templates[] = 'header.php';

        // Backward compat code will be removed in a future release
        if ('' == self::locate_template($templates, true))
            load_template( ABSPATH . WPINC . '/theme-compat/header.php');
    }

    public static function get_footer( $name = null ) {

        do_action( 'get_footer', $name );

        $templates = array();
        $name = (string) $name;
        if ( '' !== $name )
            $templates[] = "footer-{$name}.php";

        $templates[] = 'footer.php';

        // Backward compat code will be removed in a future release
        if ('' == self::locate_template($templates, true))
            load_template( ABSPATH . WPINC . '/theme-compat/footer.php');
    }
}