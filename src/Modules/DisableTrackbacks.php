<?php

namespace OptimizeWP\Modules;

/**
 * Class DisableTrackbacks
 *
 * @package \OptimizeWP\Modules
 */
class DisableTrackbacks extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\add_filter( 'xmlrpc_methods', [ $this, 'filter_xmlrpc_method' ], 10, 1 );
		\add_filter( 'wp_headers', [ $this, 'filter_headers' ], 10, 1 );
		\add_filter( 'rewrite_rules_array', [ $this, 'filter_rewrites' ] );
		\add_filter( 'bloginfo_url', [ $this, 'kill_pingback_url' ], 10, 2 );
		\add_action( 'xmlrpc_call', [ $this, 'kill_xmlrpc' ] );
	}

	/**
	 * Disables trackbacks/pingbacks
	 *
	 * You can enable/disable this feature in functions.php (or app/setup.php if you're using Sage):
	 * add_theme_support('soil-disable-trackbacks');
	 */
	/**
	 * Disable pingback XMLRPC method
	 */
	function filter_xmlrpc_method( $methods ) {
		unset( $methods['pingback.ping'] );

		return $methods;
	}

	/**
	 * Remove pingback header
	 */
	function filter_headers( $headers ) {
		if ( isset( $headers['X-Pingback'] ) ) {
			unset( $headers['X-Pingback'] );
		}

		return $headers;
	}

	/**
	 * Kill trackback rewrite rule
	 */
	function filter_rewrites( $rules ) {
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
	function kill_pingback_url( $output, $show ) {
		if ( $show === 'pingback_url' ) {
			$output = '';
		}

		return $output;
	}

	/**
	 * Disable XMLRPC call
	 */
	function kill_xmlrpc( $action ) {
		if ( $action === 'pingback.ping' ) {
			wp_die( 'Pingbacks are not supported', 'Not Allowed!', [ 'response' => 403 ] );
		}
	}


}
