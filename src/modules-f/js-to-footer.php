<?php

namespace OptimizeWP;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\js_to_footer' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function js_to_footer() {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
}
