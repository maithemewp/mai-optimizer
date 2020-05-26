<?php

namespace OptimizeWP;

remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
add_filter( 'rest_authentication_errors', __NAMESPACE__ . '\rest_authentication_errors' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return \WP_Error
 */
function rest_authentication_errors() {
	return new \WP_Error( 'rest_forbidden', __( 'REST API forbidden.', 'optimizewp' ), [ 'status' => rest_authorization_required_code() ] );
}
