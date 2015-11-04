<?php


/**
 * Class Layouts_Integration_Layouts_Cell_Menu
 */
class Layouts_Integration_Layouts_Cell_Menu extends Layouts_Integration_Cell_Abstract {
	protected $id      = 'genesis-menu';
	protected $factory = 'Layouts_Integration_Layouts_Cell_Menu_Cell_Factory';
}


/**
 * Class Layouts_Integration_Layouts_Cell_Menu_Cell
 */
class Layouts_Integration_Layouts_Cell_Menu_Cell extends Layouts_Integration_Cell_Abstract_Cell {
	protected $id = 'genesis-menu';

	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/menu.php';
	}
}


/**
 * Class Layouts_Integration_Layouts_Cell_Menu_Cell_Factory
 */
class Layouts_Integration_Layouts_Cell_Menu_Cell_Factory extends Layouts_Integration_Cell_Abstract_Cell_Factory {
	protected $name       = 'Menu';
	protected $cell_class = 'Layouts_Integration_Layouts_Cell_Menu_Cell';

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'layouts-menu-cell.svg';
	}
}