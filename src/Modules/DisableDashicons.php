<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableDashicons
 *
 * @package \OptimizeWP\Modules
 */
class DisableDashicons extends AbstractModule {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'disable_dashicons' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function disable_dashicons() {
		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'dashicons' );
			wp_deregister_style( 'dashicons' );
		}
	}

}
