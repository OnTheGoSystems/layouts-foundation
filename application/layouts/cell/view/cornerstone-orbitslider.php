<div id="content" role="main">
	<?php do_action( 'cornerstone_before_content' ); ?>
	<?php WPDDL_Integration_Layouts_Cell_Orbit_Slider_Cell_Factory::orbit_slider( $this->get_content() ); ?>
	<?php do_action( 'cornerstone_after_content' ); ?>
</div>