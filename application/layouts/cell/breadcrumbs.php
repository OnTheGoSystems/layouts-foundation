<?php


/**
 * Class Layouts_Integration_Layouts_Cell_Breadcrumbs
 */
class Layouts_Integration_Layouts_Cell_Breadcrumbs extends Layouts_Integration_Cell_Abstract {
	protected $id      = 'genesis-breadcrumbs';
	protected $factory = 'Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell_Factory';
}


/**
 * Class Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell
 */
class Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell extends Layouts_Integration_Cell_Abstract_Cell {
	protected $id = 'genesis-breadcrumbs';

	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/breadcrumbs.php';
	}
}


/**
 * Class Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell_Factory
 */
class Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell_Factory extends Layouts_Integration_Cell_Abstract_Cell_Factory {
	protected $name       = 'Breadcrumbs';
	protected $cell_class = 'Layouts_Integration_Layouts_Cell_Breadcrumbs_Cell';
}