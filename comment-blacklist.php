<?php
/**
 * Plugin Name: Comment Blacklist
 * Description: Adding/removing dynamically the IP of an Spam comment to WordPress-Blacklist
 * Plugin URI:  http://marketpress.com/
 * Author:      MarketPress, Christian Brückner
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 * Version:     2013.10.22
 */


register_activation_hook( __FILE__, 'marketpress_comment_blacklist_activation' );

/**
 * Callback for Plugin-Activation
 *
 * @wp-hook register_activation_hook
 *
 * @return  Void
 */
function marketpress_comment_blacklist_activation() {

	$comment_args   = array( 'status' => 'spam' );
	$comments       = get_comments( $comment_args );

	foreach( $comments as $comment ){
		marketpress_comment_blacklist( 'spam', '', $comment );
	}

}



/**
 * Callback to add or remove a IP to/from blacklist
 *
 * @wp-hook transition_comment_status
 * @uses    get_option, update_option
 *
 * @param   String $new_status
 * @param   String $old_status
 * @param   $comment
 * return   Void
 */
function marketpress_comment_blacklist( $new_status, $old_status, $comment ){

	$orig_blacklist = get_option( 'blacklist_keys', array() );

	if( !is_array( $orig_blacklist ) ){
		$orig_blacklist = explode( "\n" , trim( $orig_blacklist ) );
	}
	$new_blacklist = $orig_blacklist;
	$the_ip        = $comment->comment_author_IP;

	if ( $old_status === 'spam' && !in_array( $new_status, array( 'trash', 'delete' ) ) ){
		// comment is approved/unapproved, not trashed

		if( in_array( $the_ip, $orig_blacklist ) ){
			// ...and the ip does exists in blacklist
			$new_blacklist  = array_diff( $new_blacklist, array( $the_ip ) );
		}

		$new_blacklist = apply_filters( 'marketpress_comment_blacklist_remove', $new_blacklist, $new_status, $old_status, $comment );

	}
	else if ( $new_status === 'spam' ) {
		// comment is now spam

		if( !in_array( $the_ip, $orig_blacklist ) ){
			// ...and the ip does not exists in blacklist
			$new_blacklist[]    = $the_ip;
		}

		$new_blacklist = apply_filters( 'marketpress_comment_blacklist_add', $new_blacklist, $new_status, $old_status, $comment );

	}

	// do we have an update?
	if( count( $new_blacklist ) !== count( $orig_blacklist ) ) {
		$new_blacklist = implode( "\n", $new_blacklist );
		update_option( 'blacklist_keys', $new_blacklist );
	}

}

add_action( 'transition_comment_status', 'marketpress_comment_blacklist', 10, 3 );