<?php

namespace OptimizeWP;

add_action( 'template_redirect', __NAMESPACE__ . '\template_redirect' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function template_redirect() {
	global $wp_rewrite;

	if ( ! isset( $wp_rewrite ) || ! is_object( $wp_rewrite ) || ! $wp_rewrite->get_search_permastruct() ) {
		return;
	}

	$search_base = $wp_rewrite->search_base;

	if ( is_search() && ! is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false && strpos( $_SERVER['REQUEST_URI'], '&' ) === false ) {
		wp_redirect( get_search_link() );
		exit();
	}
}

add_filter( 'wpseo_json_ld_search_url', __NAMESPACE__ . '\wpseo_rewrite' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $url
 *
 * @return mixed
 */
function wpseo_rewrite( $url ) {
	return str_replace( '/?s=', '/search/', $url );
}
