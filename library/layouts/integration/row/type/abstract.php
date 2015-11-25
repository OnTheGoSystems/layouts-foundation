<?php

abstract class WPDDL_Layouts_Integration_Row_Type_Abstract {

	protected $id;
	protected $name;
	protected $desc;
	protected $image;
	private $css_classes = array();

	protected function setup() {
		add_filter( 'ddl-get_rows_modes_gui_list',
			array( &$this, 'addRowMode' ) );

		add_filter( 'ddl_render_row_start',
			array( &$this, 'htmlOpen'), 99, 2 );

		add_filter( 'ddl_render_row_end',
			array( &$this, 'htmlClose'), 99, 3 );

        add_filter( 'ddl-get_fluid_type_class_suffix',
	        array( &$this, 'overrideRowSuffix') );

        add_filter( 'ddl_no_templates_at_all',
	        array( &$this, 'overrideTemplatesExist') );

        add_filter( 'ddl_check_layout_template_page_exists',
	        array( &$this, 'overrideTemplatesExistPostType'), 10, 2 );
	}

	public function htmlOpen( $markup, $args ) {}

	public function htmlClose( $output, $mode, $tag ) {}

	public function overrideTemplatesExist( $bool ){
		return false;
	}

	public function overrideTemplatesExistPostType( $bool, $post_type){
		return true;
	}

	function overrideRowSuffix( $suffix ){
		return '';
	}

	public function addRowMode( $lists_html ){
		ob_start();?>
		<li>
			<figure class="row-type">
				<img class="item-preview" data-name="row_<?php echo $this->id; ?>" src="<?php echo $this->image; ?>" alt="<?php echo $this->name; ?>">
				<span><?php echo $this->desc; ?></span>
			</figure>
			<label class="radio" data-target="row_<?php echo $this->id; ?>" for="row_<?php echo $this->id; ?>" style="display:none">
				<input type="radio" name="row_type" id="row_<?php echo $this->id; ?>" value="<?php echo $this->id; ?>">
				<?php echo $this->name; ?>
			</label>
		</li>
		<?php
		$lists_html .= ob_get_clean();

		return $lists_html;
	}

	protected function getCssClasses() {
		return $this->css_classes;
	}

	protected function addCssClass( $css_class ) {
		$this->css_classes[] = $css_class;

		return $this;
	}

}