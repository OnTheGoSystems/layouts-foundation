<?php

/**
 * Class Layouts_Integration_Layouts_Cell_Title_Area
 */
class Layouts_Integration_Layouts_Cell_Title_Area extends Layouts_Integration_Cell_Abstract {
	protected $id      = 'genesis-title-area';
	protected $factory = 'Layouts_Integration_Layouts_Cell_Title_Area_Cell_Factory';
}


/**
 * Class Layouts_Integration_Layouts_Cell_Title_Area_Cell
 */
class Layouts_Integration_Layouts_Cell_Title_Area_Cell extends Layouts_Integration_Cell_Abstract_Cell {
	protected $id = 'genesis-title-area';

	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/title-area.php';
	}
}


/**
 * Class Layouts_Integration_Layouts_Cell_Title_Area_Cell_Factory
 */
class Layouts_Integration_Layouts_Cell_Title_Area_Cell_Factory extends Layouts_Integration_Cell_Abstract_Cell_Factory {
	protected $name       = 'Title Area';
	protected $cell_class = 'Layouts_Integration_Layouts_Cell_Title_Area_Cell';

	protected function setCellImageUrl() {
		$this->cell_image_url = plugins_url( '/../../../public/img/title-area.svg', __FILE__ );
	}
}