<?php

namespace OptimizeWP;

class Factory {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class
	 * @param array  $args
	 *
	 * @return object
	 */
	public function make( $class, $args = [] ) {
		static $objects = [];

		if ( ! \array_key_exists( $class, $objects ) ) {
			$objects[ $class ] = new $class( ...$args );
		}

		return $objects[ $class ];
	}
}
