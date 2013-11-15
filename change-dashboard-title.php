<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Change Dashboard Title
 * Description: Sets your blog name at first position in dashboard title.
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.11.06
 * Author:      MarketPress, Thomas Scholz
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */

// called in wp-admin/admin-header.php
add_filter( 'admin_title', 'marketpress_dashboard_title', 10, 2 );

/**
 * Change order of Dashboard title parts.
 *
 * @wp-hook admin_title
 * @param   string $complete_title
 * @param   string $section
 * @return  string
 */
function marketpress_dashboard_title( $complete_title, $section ) {

	if ( __( 'Dashboard' ) !== $section )
		return $complete_title;

	$blogname = esc_html( get_bloginfo( 'name' ) );

	return "$blogname | " . __( 'Dashboard' );
}