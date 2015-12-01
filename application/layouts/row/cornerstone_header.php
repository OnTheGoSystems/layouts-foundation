<?php
class WPDDL_Integration_Layouts_Row_Cornerstone_header extends WPDDL_Layouts_Integration_Row_Type_Preset_Fullwidth{
    public function setup() {

        $this->image =
        $this->id   = 'cornerstone_header';
        $this->name = __('Cornerstone Header', 'ddl-layouts');
        $this->desc = sprintf( __('%sCornerstone%s site header row', 'ddl-layouts'), '<b>', '</b>' );

        $this->addCssClass( 'top-bar' );
        $this->addDataAttributes( 'options', 'mobile_show_parent_link: true' );
        $this->addDataAttributes( 'topbar', '' );

        parent::setup();
    }
}