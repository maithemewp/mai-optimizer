<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableEmbeds
 *
 * @package \OptimizeWP\Modules
 */
class DisableEmbeds extends AbstractModule {

	public function hooks() {
		\remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		\remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		\remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		\remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result' );
		\remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result' );
		\add_action( 'wp', [ $this, 'public_query_vars' ] );
		\add_filter( 'embed_oembed_discover', '__return_false' );
		\add_filter( 'tiny_mce_plugins', [ $this, 'disable_embeds_tiny_mce_plugin' ] );
		\add_filter( 'rewrite_rules_array', [ $this, 'disable_embeds_rewrites' ] );
	}

	public function public_query_vars() {
		global $wp;
		$wp->public_query_vars = \array_diff( $wp->public_query_vars, [ 'embed', ] );
	}

	public function disable_embeds_tiny_mce_plugin( $plugins ) {
		return array_diff( $plugins, [ 'wpembed' ] );
	}

	public function disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[ $rule ] );
			}
		}

		return $rules;
	}
}
