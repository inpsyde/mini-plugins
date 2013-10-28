<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Featured image for Open Graph
 * Description: Insert featured image as Open Graph image for social sharing.
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.10.25
 * Author:      MarketPress, Thomas Scholz
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */

add_action( 'wp_head', 'marketpress_ogp_image' );

/**
 * Create meta element for preview image.
 *
 * @wp-hook wp_head
 * @return  void
 */
function marketpress_ogp_image() {

	// restricted to singular pages only
	if ( ! is_singular() )
		return;

	// there has to be a featured image set
	$thumb_id = get_post_thumbnail_id();

	// no featured image. stop.
	if ( empty ( $thumb_id ) )
		return;

	// FALSE or array
	$image = wp_get_attachment_image_src( $thumb_id );

	// nothing found for unknown reasons
	if ( empty ( $image ) )
		return;

	// make sure it is a real url
	$src = esc_url( $image[ 0 ] );

	// esc_url() returns an empty string for some invalid URLs
	if ( '' !== $src )
		print "<meta property='http://ogp.me/ns#image' content='$src' />";
}