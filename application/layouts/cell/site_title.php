<?php
/**
 * Draft of the Twenty Fifteen sidebar cell.
 */


/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_Site_title extends WPDDL_Cell_Abstract {
	protected $id = 'site-title';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_Site_title_Cell_Factory';
}


/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_Site_title_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'site-title';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/site_title.php';
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_Site_title_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Site title';

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Site_title_Cell';

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'header-cell.svg';
	}
}