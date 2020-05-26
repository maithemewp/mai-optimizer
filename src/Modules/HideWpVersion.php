<?php

namespace OptimizeWP\Modules;

/**
 * Class RemoveGenerators
 *
 * @package \OptimizeWP\Modules
 */
class HideWpVersion extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\add_filter( 'the_generator', '__return_false' );
		\remove_action('wp_head', 'wp_generator');
	}
}
