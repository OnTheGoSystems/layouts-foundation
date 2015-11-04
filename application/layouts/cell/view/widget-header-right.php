<?php


genesis_markup( array(
	'html5'   => '<div %s>' . genesis_sidebar_title( 'header-right' ),
	'xhtml'   => '<div class="widget-area header-widget-area">',
	'context' => 'header-widget-area',
) );

	do_action( 'genesis_header_right' );
	add_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
	add_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );
	dynamic_sidebar( 'header-right' );
	remove_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
	remove_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );

echo '</div>';
