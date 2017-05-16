<?php
class WPDDL_Integration_Layouts_Row_Cornerstone_footer extends WPDDL_Row_Type_Preset_Fullwidth_Background{

    public function setup() {

        $this->image =
        $this->id   = 'wpforge_footer';
        $this->name = __('Cornerstone Footer', 'ddl-layouts');
        $this->desc = sprintf( __('%sCornerstone%s footer row', 'ddl-layouts'), '<b>', '</b>' );

        $this->setCssId( 'footer' );

        parent::setup();
    }

    public function htmlOpen( $markup, $args ) {

        if( $args['mode'] === $this->id ) {

            $el_css = '';

            $css_classes = $this->getCssClasses();
            $wrapper_id = $this->getCssid();

            $el_css .= ! empty( $css_classes )
                ? ' ' . implode( $css_classes, ' ' )
                : '';

            $el_css .= isset( $args['additionalCssClasses'] )
                ? ' '.$args['additionalCssClasses']
                : '';

            $el_id = isset( $args['cssId'] ) && ! empty( $args['cssId'] )
                ? ' id="' . $args['cssId'] . '"'
                : '';

            $wrapper_id = isset( $wrapper_id ) && ! empty( $wrapper_id )
                ? ' id="' . $wrapper_id . '"'
                : '';

            $el_css .= ' container-fluid';

            ob_start();
            echo '<' . $args['tag'] . $wrapper_id . ' class="' . $el_css . '" '.$this->renderDataAttributes().'>';
            echo '<footer class="' . $args['row_class'] . '" role="contentinfo" '. $el_id . ' >';
            $markup = ob_get_clean();
        }

        return $markup;
    }

    public function htmlClose( $output, $mode, $tag ) {

        if( $mode === $this->id ) {
            $output =  '</footer>';
            $output .= '</' . $tag . '>';
        }

        return $output;
    }
}