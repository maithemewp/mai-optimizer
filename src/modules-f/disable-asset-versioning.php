<?php

namespace OptimizeWP;

add_filter( 'script_loader_src', __NAMESPACE__, '\remove_script_version', 15 );
add_filter( 'style_loader_src', __NAMESPACE__, '\remove_script_version', 15 );
/**
 * Remove version query string from all styles and scripts.
 *
 * @since 1.0.0
 *
 * @param $src
 *
 * @return bool|string
 */
function remove_script_version( $src ) {
	return $src ? esc_url( remove_query_arg( 'ver', $src ) ) : false;
}

