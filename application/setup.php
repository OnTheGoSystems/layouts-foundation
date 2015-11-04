<?php
/**
 * Class Layouts_Integration_Setup
 */
class Layouts_Integration_Setup {

	private static $instance;

	private function __clone() {}
	private function __construct() {}

	/**
	 * @return Layouts_Integration_Setup
	 */
	public static function getInstance() {
		if( self::$instance === null )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Run Integration
	 */
	public function run() {
		// add Bootstrap support
		add_action( 'wp_enqueue_scripts', array( $this, 'addBootstrapSupport' ), 1 );

		// add CSS modifications
		add_action( 'wp_enqueue_scripts', array( $this, 'CSSModifications' ), 2 );

		// add Admin CSS modifications
		add_action( 'admin_enqueue_scripts', array( $this, 'AdminCSSModifications' ), 2 );


		$this->addLayoutsSupport()
		     ->addLayoutCells()
		     ->modifyThemeSettings()
			 ->modifyThemeWidgets();
	}

	/**
	 * Bootstrap Support
	 */
	public function addBootstrapSupport() {

		// @todo prove which parts of bs are needed instead of loading full bs
		wp_register_style(
			'bootstrap',
			plugins_url( '/../public/css/bootstrap.min.css', __FILE__ ),
			array(),
			'3.3.5'
		);

		// @todo probably not needed at all
		wp_register_script(
			'bootstrap',
			plugins_url( '/../public/js/bootstrap.min.js', __FILE__ ),
			array( 'jquery' ),
			'3.3.5',
			true
		);

		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_script( 'bootstrap' );
	}

	/**
	 * CSS Modifications
	 */
	public function CSSModifications() {
		wp_register_style(
			'layouts-theme-support-genesis',
			plugins_url( '/../public/css/layouts-genesis.css', __FILE__ ),
			array(),
			'2.2.3' // supported genesis version
		);

		wp_enqueue_style( 'layouts-theme-support-genesis' );

	}

	/**
	 * Admin CSS Modifications
	 */
	public function AdminCSSModifications() {
		wp_register_style(
			'layouts-theme-support-genesis-admin',
			plugins_url( '/../public/css/layouts-genesis-admin.css', __FILE__ ),
			array(),
			'2.2.3' // supported genesis version
		);

		wp_enqueue_style( 'layouts-theme-support-genesis-admin' );
	}

	/**
	 * Layouts Support
	 */
	private function addLayoutsSupport() {
		remove_action( 'genesis_loop', 'genesis_do_loop' );
		add_action( 'genesis_loop', function() { the_ddlayout(); } );

		show_admin_bar( false );
		// remove genesis header
		remove_action( 'genesis_header', 'genesis_do_header' );
		remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
		remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

		// remove genesis footer
		remove_action( 'genesis_footer', 'genesis_do_footer' );
		remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
		remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

		// remove genesis sidebar
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

		// remove genesis menujj
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
		remove_action( 'genesis_after_header', 'genesis_do_subnav' );

		// remove genesis site structure output
		add_filter( 'genesis_markup_content-sidebar-wrap_output', array( $this, 'clearContent' ) );
		add_filter( 'genesis_markup_content_output', array( $this, 'clearContent' ) );
		add_filter( 'genesis_markup_site-inner_output', array( $this, 'clearContent' ) );
		//add_filter( 'genesis_markup_site-container_output', array( $this, 'clearContent' ) );
		//add_filter( 'genesis_markup_sidebar-primary_output', array( $this, 'clearContent' ) );

		// closing elements have no context set by genesis - so they cannot be target exactly
		// @todo make sure the site structure does not get destroyed and remove the filter later on
		add_filter( 'genesis_markup__output', array( $this, 'clearContent' ) );

		// say Layouts that the theme supports Layouts
		$theme = wp_get_theme();
		$options_manager = new WPDDL_Options_Manager( 'ddl_template_check' );
		if( ! $options_manager->get_options( 'theme-' . $theme->get('Name') ) )
			$options_manager->update_options( 'theme-' . $theme->get('Name'), 1 );

		return $this;
	}

	/**
	 * Add Genesis Elements to Layouts
	 * @return $this
	 */
	private function addLayoutCells() {

		// Widget Header Right
		$widget_header_right = new Layouts_Integration_Layouts_Cell_Widget_Header_Right();
		$widget_header_right->setup();

		// Title Area
		$title_area = new Layouts_Integration_Layouts_Cell_Title_Area();
		$title_area->setup();

		// Menu
		$menu = new Layouts_Integration_Layouts_Cell_Menu();
		$menu->setup();

		// Breadcrumbs
		$breadcrumbs = new Layouts_Integration_Layouts_Cell_Breadcrumbs();
		$breadcrumbs->setup();


		return $this;
	}

	/**
	 * This function is used to remove all Genesis settings which are obsolete with the use of Layouts
	 * i.e. "Default Layout" in "Theme Settings"
	 */
	public function modifyThemeSettings() {

		// remove "Default Layouts" in Genesis > Theme Settings
		add_action( 'load-toplevel_page_genesis', array( 'Layouts_Integration_Theme_Settings_Default_Layouts', 'setup' ), 100 );

		// remove "Blog Page Template" in Genesis > Theme Settings
		add_action( 'load-toplevel_page_genesis', array( 'Layouts_Integration_Theme_Settings_Blog_Page_Template', 'setup' ), 100 );

		// replace default "Breadcrumb" option with a hint to a new Layouts Element
		add_action( 'genesis_admin_before_metaboxes', array( 'Layouts_Integration_Theme_Settings_Breadcrumbs', 'setup') );


		return $this;
	}

	/**
	 * Unregister Genesis Sidebars "Primary" & "Secondary"
	 */
	public function modifyThemeWidgets() {
		unregister_sidebar( 'sidebar' );
		unregister_sidebar( 'sidebar-alt' );
	}

	/**
	 * @return string
	 */
	public function clearContent() {
		return '';
	}

}