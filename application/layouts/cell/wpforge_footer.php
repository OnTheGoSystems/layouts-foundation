<?php
/**
 * Draft of the Twenty Fifteen sidebar cell.
 */


/**
 * Cell abstraction. Defines the cell with Layouts.
 */
class WPDDL_Integration_Layouts_Cell_WPForge_footer extends WPDDL_Cell_Abstract {
	protected $id = 'wpforge-footer';

	protected $factory = 'WPDDL_Integration_Layouts_Cell_WPForge_footer_Cell_Factory';
}


/**
 * Represents the actual cell.
 */
class WPDDL_Integration_Layouts_Cell_WPForge_footer_Cell extends WPDDL_Cell_Abstract_Cell {
	protected $id = 'wpforge-footer';

	/**
	 * Each cell has it's view, which is a file that is included when the cell is being rendered.
	 *
	 * @return string Path to the cell view.
	 */
	protected function setViewFile() {
		return dirname( __FILE__ ) . '/view/wpforge-footer.php';
	}

	public function __construct($name, $width, $css_class_name, $content, $css_id, $tag)
	{
		parent::__construct($name, $width, $css_class_name, $content, $css_id, $tag);
	}
}


/**
 * Cell factory.
 */
class WPDDL_Integration_Layouts_Cell_WPForge_footer_Cell_Factory extends WPDDL_Cell_Abstract_Cell_Factory {
	protected $name = 'WPForge Footer';

	protected $cell_class = 'WPDDL_Integration_Layouts_Cell_WPForge_footer_Cell';

	public function __construct(){
        $this->description       = __('Display a widget area with WPForge style and hooks - This needs to be used in combination with WPForge Footer row mode for best display.', 'ddl-layouts');
		$this->allow_multiple = false;
	}

	protected function setCellImageUrl() {
		$this->cell_image_url = WPDDL_WPFORGE_URI_PUBLIC . DIRECTORY_SEPARATOR . 'img/wpforge-footer.svg';
	}

	public function get_editor_cell_template(){
		ob_start();
		?>
		<div class="cell-content">
			<p class="cell-name"><?php echo $this->name; ?></p>
			<div class="cell-preview">
                <div class="ddl-image-box-preview">
                    <img src="<?php echo WPDDL_WPFORGE_URI_PUBLIC . DIRECTORY_SEPARATOR . 'img/wpforge-footer-preview.svg' ?>" height="130px">
                </div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}