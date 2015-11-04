<?php

/*
 * Original Code of genesis_do_breadcrumbs() in genesis/lib/functions/breadcrumbs
 */
$breadcrumb_markup_open = sprintf( '<div %s>', genesis_attr( 'breadcrumb' ) );

if ( function_exists( 'bcn_display' ) ) {
	echo $breadcrumb_markup_open;
	bcn_display();
	echo '</div>';
}
elseif ( function_exists( 'breadcrumbs' ) ) {
	breadcrumbs();
}
elseif ( function_exists( 'crumbs' ) ) {
	crumbs();
}
elseif ( class_exists( 'WPSEO_Breadcrumbs' ) && genesis_get_option( 'breadcrumbs-enable', 'wpseo_internallinks' ) ) {
	yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
}
elseif( function_exists( 'yoast_breadcrumb' ) && ! class_exists( 'WPSEO_Breadcrumbs' ) ) {
	yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
}
else {
	genesis_breadcrumb();
}