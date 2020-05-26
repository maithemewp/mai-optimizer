<?php

namespace MaiOptimizer;

class Factory {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class
	 * @param mixed  $args
	 *
	 * @return object
	 */
	public function make( $class, $args = null ) {
		static $objects = [];

		if ( ! \array_key_exists( $class, $objects ) ) {
			$objects[ $class ] = $args ? new $class( ...$args ) : new $class();
		}

		return $objects[ $class ];
	}
}
