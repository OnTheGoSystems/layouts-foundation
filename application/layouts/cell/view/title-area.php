<?php

genesis_markup( array(
	'html5'   => '<div %s>',
	'xhtml'   => '<div id="title-area">',
	'context' => 'title-area',
) );
do_action( 'genesis_site_title' );
do_action( 'genesis_site_description' );

echo '</div>';
