<?php

namespace OptimizeWP;

add_filter( 'xmlrpc_methods', __NAMESPACE__ . '\filter_xmlrpc_method', 10, 1 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $methods
 *
 * @return mixed
 */
function filter_xmlrpc_method( $methods ) {
	unset( $methods['pingback.ping'] );

	return $methods;
}

add_filter( 'wp_headers', __NAMESPACE__ . '\filter_headers', 10, 1 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $headers
 *
 * @return mixed
 */
function filter_headers( $headers ) {
	if ( isset( $headers['X-Pingback'] ) ) {
		unset( $headers['X-Pingback'] );
	}

	return $headers;
}

add_filter( 'rewrite_rules_array', __NAMESPACE__ . '\filter_rewrites' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $rules
 *
 * @return mixed
 */
function filter_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( preg_match( '/trackback\/\?\$$/i', $rule ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}

add_filter( 'bloginfo_url', __NAMESPACE__ . '\remove_pingback_url', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $output
 * @param $show
 *
 * @return string
 */
function remove_pingback_url( $output, $show ) {
	if ( $show === 'pingback_url' ) {
		$output = '';
	}

	return $output;
}

add_action( 'xmlrpc_call', __NAMESPACE__ . '\remove_xmlrpc' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $action
 *
 * @return void
 */
function remove_xmlrpc( $action ) {
	if ( $action === 'pingback.ping' ) {
		wp_die( 'Pingbacks are not supported', 'Not Allowed!', [ 'response' => 403 ] );
	}
}

