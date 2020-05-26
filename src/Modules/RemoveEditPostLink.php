<?php

namespace OptimizeWP\Modules;

/**
 * Class RemovePostEditLink
 *
 * @package \OptimizeWP\Modules
 */
class RemoveEditPostLink extends Module {

	public function hooks() {
		\add_filter( 'edit_post_link', '__return_empty_string' );
	}
}
