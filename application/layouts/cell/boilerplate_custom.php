<?php
/**
 * Example of custom Layouts cell.
 */

/**
 * Cell abstraction. Defines the cell with Layouts.
 *
 * @todo You can, of course, abandon this mechanism and create custom cells entirely by hand:
 * @link https://wp-types.com/documentation/user-guides/creating-custom-cells-unique-functionality/
 *
 * @todo Name of this class should be "WPDDL_Integration_Layouts_Cell_{$cell_name}", where $cell_name corresponds to
 * @todo     the filename (so it is loaded properly by the autoloader).
 *
 * @todo You need to choose an unique cell ID and set it properly in all three classes below.
 */
class WPDDL_Integration_Layouts_Cell_Boilerplate_Custom extends WPDDL_Cell_Abstract {
	protected $id = 'boilerplate-custom'; // @todo update cell ID

	// @todo Update to the factory class name (the last one)
	protected $factory = 'WPDDL_Integration_Layouts_Cell_Boilerplate_Custom_Cell_Factory';
}


/**
 * Represents the actual cell.
 *
 * @todo Rename this class to "WPDDL_Integration_Layouts_Cell_{$cell_name}_Cell".
 */
class WPDDL_Integration_Layouts_Cell_Boilerplate_Custom_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'boilerplate-custom'; // @todo Update cell ID.

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 * @todo Provide your own view and update the path.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/boilerplate-custom.php';
	}
}


/**
 * Cell factory.
 *
 * @todo Rename this class to "WPDDL_Integration_Layouts_Cell_{$cell_name}_Cell_Factory".
 * @todo Take a look at WPDDL_Cell_Abstract_Cell_Factory and consider providing more complete information about the cell.
 */
class WPDDL_Integration_Layouts_Cell_Boilerplate_Custom_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Custom boilerplate cell'; // @todo Provide cell display name

	// @todo Update to the cell class name (the second one in this file)
	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Boilerplate_Custom_Cell';

	// @todo Provide an URL to cell image.
	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'generic-one-cell.svg';
	}
}