<?php

namespace OptimizeWP\Modules;

/**
 * Class DisableAssetVersioning
 *
 * @package \OptimizeWP\Modules
 */
class RemoveQueryStrings extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'script_loader_src', [ $this, 'remove_script_version' ], 15, 1 );
		add_filter( 'style_loader_src', [ $this, 'remove_script_version' ], 15, 1 );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $src
	 *
	 * @return bool|string
	 */
	function remove_script_version( $src ) {
		return $src ? \esc_url( \remove_query_arg( 'ver', $src ) ) : false;
	}
}
