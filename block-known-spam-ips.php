<?php
/**
 * Plugin Name: Block Known Spam IP's
 * Description: Blocking known Spam IP's on new Comments
 * Plugin URI:  http://marketpress.com/
 * Author:      MarketPress, Thomas Scholz, Christian Brückner
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 * Version:     2013.10.22
 */


add_filter( 'preprocess_comment', 'marketpress_block_known_spam_ips' );

/**
 * Look up comment IP in DB and reject if spam from this IP was found.
 *
 * @wp-hook preprocess_comment
 * @param   array $commentdata
 * @return  array|void Dies on success, returns the comment data otherwise
 */
function marketpress_block_known_spam_ips( Array $commentdata ) {
	global $wpdb;

	// Use the same code as WordPress, because that is what we find in our database.
	$ip  = preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '';

	$sql = "
        SELECT `comment_author_IP`
        FROM   `$wpdb->comments`
        WHERE  `comment_author_IP` = '" . $ip . "' AND `comment_approved` = 'spam'
        LIMIT  1";

	$match = $wpdb->get_results( $sql );

	$is_valid = check_comment(
		$commentdata[ 'comment_author' ],
		$commentdata[ 'comment_author_email' ],
		$commentdata[ 'comment_author_url' ],
		$commentdata[ 'comment_content' ],
		$ip,
		$agent,
		$commentdata[ 'comment_type' ]
	);

	// you could send a redirect here instead.
	if ( !empty ( $match ) || !$is_valid )
		wp_die( __( 'Sry no spam allowed', 'marketpress' ) );

	return $commentdata;
}