<?php

namespace OptimizeWP;

add_filter( 'wp_nav_menu_args', __NAMESPACE__, '\nav_menu_args' );
add_filter( 'nav_menu_item_id', '__return_null' );

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param array $args Nav menu args.
 *
 * @return array
 */
function nav_menu_args( $args = [] ) {
	$nav_menu_args              = [];
	$nav_menu_args['container'] = false;

	if ( ! $args['items_wrap'] ) {
		$nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	}

	if ( ! $args['walker'] ) {
		$nav_menu_args['walker'] = new Nav_Menu_Walker();
	}

	return array_merge( $args, $nav_menu_args );
}

