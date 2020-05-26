<?php

namespace OptimizeWP\Modules;

/**
 * Class LimitHeartbeat
 *
 * @package \OptimizeWP\Modules
 */
class LimitHeartbeat extends Module {

	public function hooks() {
		\add_action( 'init', [ $this, 'limit_heartbeat' ] );
	}

	public function limit_heartbeat() {
		global $pagenow;
		if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) {
			wp_deregister_script( 'heartbeat' );
		}
	}

}
