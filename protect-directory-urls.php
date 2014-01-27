<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Protect directory URLs
 * Description: Prevents the creation of permalinks matching existing directories.
 * Plugin URI:  http://marketpress.com/
 * Version:     2014.01.27
 * Author:      MarketPress, Thomas Scholz
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */

// Pages and other hierarchical post types.
add_filter(
	'wp_unique_post_slug_is_bad_hierarchical_slug',
	'marketpress_prevent_directory_slugs',
	10, 2
);
// Posts and other non-hierarchical post types.
add_filter(
	'wp_unique_post_slug_is_bad_flat_slug',
	'marketpress_prevent_directory_slugs',
	10, 2
);
// Attachments.
add_filter(
	'wp_unique_post_slug_is_bad_attachment_slug',
	'marketpress_prevent_directory_slugs',
	10, 2
);

/**
 * Do not allow slugs matching existing directories.
 *
 * @author  MarketPress.com
 * @see     wp_unique_post_slug()
 * @wp-hook wp_unique_post_slug_is_bad_hierarchical_slug
 * @wp-hook wp_unique_post_slug_is_bad_flat_slug
 * @wp-hook wp_unique_post_slug_is_bad_attachment_slug
 * @param   bool   $bool Boolean value passed by the hook, default to false.
 * @param   string $slug
 * @return  bool   TRUE if there is a directory with the same name.
 */
function marketpress_prevent_directory_slugs( $bool, $slug )
{
	if ( is_dir( ABSPATH . '/' . $slug ) )
		return TRUE;

	return $bool;
}