<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Use WooCommerce PrettyPhoto Everywhere
 * Description: Use the WooCommerce PrettyPhoto Lightbox for galleries and single images.
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.11.15
 * Author:      MarketPress, Birgit Olzem
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */

// Called in woocommerce/woocommerce.php
add_action( 'woocommerce_loaded', 'marketpress_init_prettyphoto' );

function marketpress_init_prettyphoto() {
	add_action( 'wp_enqueue_scripts', 'marketpress_enqueue_prettyphoto_scripts' );
	add_filter( 'wp_get_attachment_link', 'marketpress_add_rel_attribute' );
}



/**
 * Register JavaScript and CSS files.
 *
 * @global  $woocommerce
 * @wp-hook wp_enqueue_script
 * @return  void
 */
function marketpress_enqueue_prettyphoto_scripts() {
	global $woocommerce;

	// Lightbox disabled.
	if ( get_option( 'woocommerce_enable_lightbox' ) !== 'yes' )
		return;

	$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$woo_url = $woocommerce->plugin_url();

	wp_enqueue_script(
		'prettyPhoto',
		"$woo_url/assets/js/prettyPhoto/jquery.prettyPhoto$suffix.js",
		array( 'jquery' ),
		'3.1.5',
		TRUE
	);
	wp_enqueue_script(
		'prettyPhoto-init',
		"$woo_url/assets/js/prettyPhoto/jquery.prettyPhoto.init$suffix.js",
		array( 'jquery' ),
		$woocommerce->version,
		TRUE
	);
	wp_enqueue_style(
		'woocommerce_prettyPhoto_css',
		"$woo_url/assets/css/prettyPhoto.css"
	);
}

/**
 * Change attachment links.
 *
 * @wp-hook wp_get_attachment_link
 * @param   string $link
 * @return  string
 */
function marketpress_add_rel_attribute( $link ) {
	return str_replace( '<a href', '<a rel="prettyPhoto[pp_gal]" href', $link );
}
