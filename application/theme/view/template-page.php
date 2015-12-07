<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages that use a Layout.
 *
 * Template name: Template page
 */

$template_router = WPDDL_Integration_Theme_Template_Router::get_instance();


$template_router->get_header();

the_ddlayout();

$template_router->get_footer();
