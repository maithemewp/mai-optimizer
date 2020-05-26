<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableTrackbacks
 *
 * @package \OptimizeWP\Modules
 */
class DisableTrackbacks extends AbstractModule {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\add_filter( 'option_default_ping_status', '__return_false', 99 );
		\add_filter( 'xmlrpc_methods', [ $this, 'filter_xmlrpc_method' ], 10, 1 );
		\add_filter( 'wp_headers', [ $this, 'filter_headers' ], 11, 1 );
		\add_filter( 'rewrite_rules_array', [ $this, 'filter_rewrites' ] );
		\add_filter( 'bloginfo_url', [ $this, 'kill_pingback_url' ], 10, 2 );
		\add_action( 'xmlrpc_call', [ $this, 'kill_xmlrpc' ] );
	}

	/**
	 * Disable pingback XMLRPC method
	 */
	public function filter_xmlrpc_method( $methods ) {
		unset( $methods['pingback.ping'] );

		return $methods;
	}

	/**
	 * Remove pingback header
	 */
	public function filter_headers( $headers ) {
		if ( isset( $headers['X-Pingback'] ) ) {
			unset( $headers['X-Pingback'] );
		}

		return $headers;
	}

	/**
	 * Kill trackback rewrite rule
	 */
	public function filter_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( preg_match( '/trackback\/\?\$$/i', $rule ) ) {
				unset( $rules[ $rule ] );
			}
		}

		return $rules;
	}

	/**
	 * Kill bloginfo('pingback_url')
	 */
	public function kill_pingback_url( $output, $show ) {
		if ( $show === 'pingback_url' ) {
			$output = null;
		}

		return $output;
	}

	/**
	 * Disable XMLRPC call
	 */
	public function kill_xmlrpc( $action ) {
		if ( $action === 'pingback.ping' ) {
			wp_die( 'Pingbacks are not supported', 'Not Allowed!', [ 'response' => 403 ] );
		}
	}

}
