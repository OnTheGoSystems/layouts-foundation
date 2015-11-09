<?php
/**
 * Draft of the Twenty Fifteen sidebar cell.
 */


/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_Sidebar extends WPDDL_Cell_Abstract {
	protected $id = 'boilerplate-sidebar';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_Sidebar_Cell_Factory';
}


/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_Sidebar_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'boilerplate-sidebar';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/sidebar.php';
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_Sidebar_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Sidebar cell';

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Sidebar_Cell';

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'single-widget.svg';
	}
}