<?php


abstract class WPDDL_Layouts_Integration_Row_Type_Preset_Fullwidth
	extends WPDDL_Layouts_Integration_Row_Type_Abstract {

	protected function setup() {
		$this->image = WPDDL_GUI_RELPATH . 'dialogs/img/tn-full-fluid.png';

		parent::setup();
	}

	public function htmlOpen( $markup, $args ) {

		if( $args['mode'] === $this->id ) {

			$el_css = 'ddl-full-width-row ' . $args['row_class'] . $args['type'];

			$css_classes = $this->getCssClasses();

			$el_css .= ! empty( $css_classes )
				? ' ' . implode( $css_classes, ' ' )
				: '';

			$el_css .= isset( $args['additionalCssClasses'] )
				? ' '.$args['additionalCssClasses']
				: '';

			$el_id = isset( $args['cssId'] ) && ! empty( $args['cssId'] )
				? ' id="' . $args['cssId'] . '"'
				: '';

			ob_start();
			echo '<div class="' . $args['container_class'] . '">';
			echo '<' . $args['tag'] . $el_id . ' class="' . $el_css . '">';

			$markup = ob_get_clean();
		}

		return $markup;
	}

	public function htmlClose( $output, $mode, $tag ) {
		if( $mode = $this->id ) {
			$output = '</' . $tag . '></div>';
		}

		return $output;
	}
}