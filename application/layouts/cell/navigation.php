<?php
/**
 * Draft of the Twenty Fifteen sidebar cell.
 */


/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_Navigation extends WPDDL_Cell_Abstract {
	protected $id = 'cornerstone-navigation';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_Navigation_Cell_Factory';
}


/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_Navigation_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'cornerstone-navigation';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/navigation.php';
	}

	public function __construct($name, $width, $css_class_name, $content, $css_id, $tag)
	{
		$css_class_name .= ' post-pagination-cell';
		parent::__construct($name, $width, $css_class_name, $content, $css_id, $tag);
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_Navigation_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'Cornerstone Post Navigation';

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_Navigation_Cell';

	public function __construct(){
        $this->description       = __('Display posts (archive) navigation with theme style.', 'ddl-layouts');
        $this->preview_image_url = WPDDL_CORNERSTONE_URI_PUBLIC . DIRECTORY_SEPARATOR . '/img/post-navigation-cell-description.png';
	}

	protected function setCellImageUrl() {
		$this->cell_image_url = WPDDL_CORNERSTONE_URI_PUBLIC . DIRECTORY_SEPARATOR . 'img/post-navigation.svg';
	}

	public function get_editor_cell_template(){
		ob_start();
		?>
		<div class="cell-content">
			<p class="cell-name"><?php echo $this->name; ?></p>
			<div class="cell-preview">
                <div class="ddl-image-box-preview">
                    <img src="<?php echo WPDDL_CORNERSTONE_URI_PUBLIC . DIRECTORY_SEPARATOR . 'img/post-navigation-preview.svg'; ?>" height="130px">
                </div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}