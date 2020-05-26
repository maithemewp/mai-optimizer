<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableRestAPI
 *
 * @package \OptimizeWP\Modules
 */
class DisableRestAPI extends AbstractModule {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		\remove_action( 'template_redirect', 'rest_output_link_header', 11 );
		\remove_action( 'wp_head', 'rest_output_link_wp_head' );
		\add_filter( 'rest_authentication_errors', [ $this, 'rest_authentication_errors' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Error
	 */
	public function rest_authentication_errors() {
		return new \WP_Error(
			'rest_forbidden',
			__( 'REST API forbidden.', 'optimizewp' ),
			[
				'status' => \rest_authorization_required_code()
			]
		);
	}

}
