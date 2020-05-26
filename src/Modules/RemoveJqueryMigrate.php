<?php

namespace MaiOptimizer\Modules;

/**
 * Class RemoveJqueryMigrate
 *
 * @package \OptimizeWP\Modules
 */
class RemoveJqueryMigrate extends AbstractModule {

	public function hooks() {
		\add_action( 'wp_default_scripts', [ $this, 'remove_jquery_migrate' ] );
	}

	function remove_jquery_migrate( $scripts ) {
		if ( ! \is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];

			if ( $script->deps ) {
				$script->deps = \array_diff( $script->deps, [ 'jquery-migrate' ] );
			}
		}
	}


}
