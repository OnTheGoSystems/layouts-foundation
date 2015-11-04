<?php

/**
 * Class Layouts_Integration_Layouts_Cell_Widget_Header_Right
 */
class Layouts_Integration_Layouts_Cell_Widget_Header_Right extends Layouts_Integration_Cell_Abstract {
	protected $id      = 'genesis-widget-header-right';
	protected $factory = 'Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell_Factory';
}


/**
 * Class Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell
 */
class Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell extends Layouts_Integration_Cell_Abstract_Cell {
	protected $id = 'genesis-widget-header-right';

	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/widget-header-right.php';
	}
}


/**
 * Class Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell_Factory
 */
class Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell_Factory extends Layouts_Integration_Cell_Abstract_Cell_Factory {
	protected $name       = 'Widget Header Right';
	protected $cell_class = 'Layouts_Integration_Layouts_Cell_Widget_Header_Right_Cell';

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'single-widget.svg';
	}
}