<?php

namespace MaiOptimizer\Modules;

/**
 * Class RemovePostEditLink
 *
 * @package \OptimizeWP\Modules
 */
class RemoveEditPostLink extends AbstractModule {

	public function hooks() {
		\add_filter( 'edit_post_link', '__return_empty_string' );
	}
}
