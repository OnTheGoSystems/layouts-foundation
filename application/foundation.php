<?php
class WPDDL_Integration_Foundation extends WPDDL_Framework_Integration_Abstract{

    protected function __construct(){
        $this->setUpFrameWork( 'foundation', 'Foundation by ZURB' );
        parent::__construct();
    }

    public function getColumnPrefix(){
        return array('small-', 'medium-', 'large-');
    }

    public function get_additional_column_class(){
        return 'columns';
    }
}