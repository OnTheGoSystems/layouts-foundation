<?php
/**
 * Draft of the Twenty Fifteen sidebar cell.
 */


/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar extends WPDDL_Cell_Abstract {
	protected $id = 'cornerstone-sidebar';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar_Cell_Factory';
}


/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'cornerstone-sidebar';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/cornerstone-sidebar.php';
	}

	public function __construct($name, $width, $css_class_name, $content, $css_id, $tag)
	{
		$css_class_name = $css_class_name . ' cornerstone-sidebar-cell';
		parent::__construct($name, $width, $css_class_name, $content, $css_id, $tag);
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Cornerstone Sidebar';

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Cornerstone_sidebar_Cell';

	public function __construct(){
        $this->description       = __('Display widgets placed under right sidebar widgets area.', 'ddl-layouts');
		$this->allow_multiple = true;
	}

	protected function setCellImageUrl() {
		$this->cell_image_url = DDL_ICONS_SVG_REL_PATH . 'sidebar-cell.svg';
	}

	public function get_editor_cell_template(){
		ob_start();
		?>
		<div class="cell-content">
			<p class="cell-name"><?php echo $this->name; ?></p>
			<div class="cell-preview theme-integration-preview-area">
                <div class="ddl-image-box-preview">
                    <img src="<?php echo DDL_ICONS_SVG_REL_PATH . 'widget-area.svg' ?>" height="110px">
                </div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}