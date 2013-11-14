<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Allover WooCommerce PrettyPhoto Script
 * Description: Use the WooCommerce PrettyPhoto Lightbox for galleries and single images
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.11.14
 * Author:      MarketPress, Birgit Olzem
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */



/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
   	add_action( 'wp_enqueue_scripts', 'frontend_scripts_include_lightbox' );
   	/**
   	  * @global $woocommerce
   	  * @wp-hook wp_enqueue_script
   	  * @wp-hook wp_enqueue_style
   	  * @return void
   	  */ 
	function frontend_scripts_include_lightbox() {
	  global $woocommerce;
	 
	  $suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	  $lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;
	 
	  if ( $lightbox_en ) {
	    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
	    wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
	    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
	  }
	}
	
	add_filter('wp_get_attachment_link', 'marketpress_add_rel_attribute');
	/**
	 * @global $post  
	 * @return string
	 */
	function marketpress_add_rel_attribute($link) {
	global $post;
	return str_replace('<a href', '<a rel="prettyPhoto[pp_gal]" href', $link);
	}
}