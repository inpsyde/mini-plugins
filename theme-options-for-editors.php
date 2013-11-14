<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Theme options for editors
 * Description: Allow editors to change theme options.
 * Plugin URI:  http://marketpress.com/?p=17582
 * Author:      MarketPress, Thomas Scholz
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 * Version:     2013.10.22
 */

register_activation_hook( __FILE__, 'marketpress_theme_options_for_editors' );

add_action( 'admin_notices', 'marketpress_deactivate_theme_options_for_editors', 0 );

/**
 * Add new capability to "editor" role.
 *
 * @wp-hook "activate_" . __FILE__
 * @return  void
 */
function marketpress_theme_options_for_editors() {
	global $wp_roles;

	if ( empty ( $wp_roles ) )
		$wp_roles = new WP_Roles;

	$wp_roles->add_cap( 'editor', 'edit_theme_options' );
}

/**
 * Print an explanation and deactivate this plugin.
 *
 * @wp-hook admin_notices
 * @return  void
 */
function marketpress_deactivate_theme_options_for_editors() {
	
	// Suppress default activation message.
	unset( $_GET['activate'] );
	$name = get_file_data( __FILE__, array ( 'Plugin Name' ), 'plugin' );
	?>

	<div class="updated">
		<p>Editors can change theme options now.<br>
		This is a permanent option, so you don&rsquo;t need the plugin running anymore.</p>
		<p><b><?php echo $name[0]; ?></b> has been deactivated.</p>
	</div>

	<?php
	deactivate_plugins( plugin_basename( __FILE__ ) );
}