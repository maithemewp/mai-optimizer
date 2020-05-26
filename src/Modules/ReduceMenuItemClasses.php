<?php

namespace MaiOptimizer\Modules;

/**
 * Class ReduceMenuItemClasses
 *
 * @package \MaiOptimizer\Modules
 */
class ReduceMenuItemClasses extends AbstractModule {

	public function hooks() {
		add_filter( 'wp_nav_menu_args', [ $this, 'nav_menu_args' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function nav_menu_args( $args = [] ) {
		$nav_menu_args              = [];
		$nav_menu_args['container'] = false;

		if ( ! $args['items_wrap'] ) {
			$nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
		}

		if ( ! $args['walker'] ) {
			$walker                  = new NavMenuWalker();
			$nav_menu_args['walker'] = $walker;

			$walker->hooks();
		}

		return array_merge( $args, $nav_menu_args );
	}

}
