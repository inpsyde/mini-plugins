<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Term list shortcode
 * Description: List posts for a term with [termlist term='termslug' taxonomy='taxonomyslug']headline[/termlist].
 * Plugin URI:  http://marketpress.com/
 * Version:     2013.12.05
 * Author:      MarketPress, Thomas Scholz
 * Author URI:  http://marketpress.com/
 * Licence:     GPL 2
 * License URI: http://opensource.org/licenses/GPL-2.0
 */

add_shortcode( 'termlist', 'marketpress_termlist_shortcode' );

/**
 * Handle the shortcode.
 *
 * @param  array  $atts
 * @param  string $content
 * @return string
 */
function marketpress_termlist_shortcode( $atts, $content = '' ) {

	if ( empty ( $atts[ 'term' ] ) )
		return;

	$default = array (
		'taxonomy'    => 'category',
		'max'         => 24,
		'post_type'   => 'post',
		'post_status' => 'publish',
		'term'        => ''
	);

	$args = shortcode_atts( $default, $atts );

	// Attachments have post status 'inherit' by default
	if ( 'attachment' === $args[ 'post_type' ] && 'publish' === $args[ 'post_status' ] )
		$args[ 'post_status' ] = 'inherit';

	$posts = get_posts(
		array (
			'posts_per_page'    => $args[ 'max' ],
			'post_type'         => $args[ 'post_type' ],
			'post_status'       => $args[ 'post_status' ],
			$args[ 'taxonomy' ] => $args[ 'term' ]
		)
	);

	if ( empty ( $posts ) )
		return;

	$list = array ();

	foreach ( $posts as $post ) {
		$list[] = sprintf(
			'<li><a href="%1$s">%2$s</a></li>',
			get_permalink( $post->ID ),
			get_the_title( $post->ID )
		);
	}

	return "<div class='marketpress_termlist'>$content<ul>" . join( '', $list ) . '</ul></div>';
}