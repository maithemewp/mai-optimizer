<?php

namespace MaiOptimizer\Modules;

/**
 * Class LimitPostRevisions
 *
 * @package \OptimizeWP\Modules
 */
class LimitPostRevisions extends AbstractModule {

	public function hooks() {
		\add_action( 'init', [ $this, 'limit_post_revisions' ] );
	}

	public function limit_post_revisions() {
//		define( 'WP_POST_REVISIONS', 1 );
	}
}
