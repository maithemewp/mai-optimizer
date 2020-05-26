<?php

namespace MaiOptimizer\Modules;

/**
 * Class MoveScriptsToFooter
 *
 * @package \OptimizeWP\Modules
 */
class MoveScriptsToFooter extends AbstractModule {

	public function hooks() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'move_scripts' ] );
	}

	public function move_scripts() {
		\remove_action( 'wp_head', 'wp_print_scripts' );
		\remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	}
}
