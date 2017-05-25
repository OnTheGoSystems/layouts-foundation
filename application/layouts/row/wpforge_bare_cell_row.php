<?php
class WPDDL_Integration_Layouts_Row_WPForge_bare_cell_row extends WPDDL_Row_Type_Preset_Fullwidth_Background{
    public function setup() {

        $this->image =
        $this->id   = 'wpforge_header';
        $this->name = __('WP-Forge Row Bare Column Row', 'ddl-layouts');
        $this->desc = sprintf( __('%sWP-Forge Row%s bare column row', 'ddl-layouts'), '<b>', '</b>' );

        parent::setup();
    }

    public function htmlOpen( $markup, $args, $row = null, $render = null ) {

        if( $args['mode'] === $this->id ) {
	        $markup = '';
        }

        return $markup;
    }

    public function htmlClose( $output, $mode, $tag ) {
        if( $mode === $this->id ) {
            $output = '';
        }

        return $output;
    }
}