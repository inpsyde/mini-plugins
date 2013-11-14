<?php
/**
 * Plugin Name: WP_User_Query-WP_Date_Query support
 * Description: Adding Support for WP_Date_Query on column "user_registered" for WP_User_Query
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.11.14
 * Author:      MarketPress, Christian Brückner
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */


/**
 * Adding the column "user_registered" to WP_Date_Query
 *
 * @param   Array $columns
 * @return  Array
 */
function marketpress_date_query_column_user_registered( $columns ){
	$columns[] = 'user_registered';
	return $columns;
}
add_filter( 'date_query_valid_columns', 'marketpress_date_query_column_user_registered' );


/**
 * adding the missing WP_Date_Query to WP_User_Query on "user_registered"
 *
 * @param       WP_User_Query $user_query
 * @internal    WP_Date_Query
 * @return      WP_User_Query
 */
function marketpress_get_users_date_query( WP_User_Query $user_query ){
	if( isset( $user_query->query_vars[ 'date_query' ] ) ) {
		$date_query = new WP_Date_Query( $user_query->query_vars[ 'date_query' ], 'user_registered' );
		$user_query->query_where .= ' ' . $date_query->get_sql();
	}
	return $user_query;
}
add_action( 'pre_user_query', 'marketpress_get_users_date_query' );