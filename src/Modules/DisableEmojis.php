<?php

namespace OptimizeWP\Modules;

/**
 * Class RemoveEmojis
 *
 * @package \OptimizeWP\Modules
 */
class DisableEmojis extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		\remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		\remove_action( 'wp_print_styles', 'print_emoji_styles' );
		\remove_action( 'admin_print_styles', 'print_emoji_styles' );
		\remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		\remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		\remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		\add_filter( 'emoji_svg_url', '__return_false' );
		\add_filter( 'tiny_mce_plugins', [ $this, 'remove_tinymce' ] );
		\add_filter( 'wp_resource_hints', [ $this, 'remove_dns_prefetch' ], 10, 2 );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $plugins
	 *
	 * @return array
	 */
	function remove_tinymce( $plugins = [] ) {
		return \array_diff( $plugins, [ 'wpemoji' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $urls
	 * @param $relation_type
	 *
	 * @return array
	 */
	function remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			$emoji_svg_url = \apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

			$urls = \array_diff( $urls, [ $emoji_svg_url ] );
		}

		return $urls;
	}
}
