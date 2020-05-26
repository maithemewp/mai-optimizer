<?php

namespace OptimizeWP;

class Container {
	public $services = [];

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $service
	 *
	 * @return object
	 */
	public function get( $service ) {
		return $this->services[ $service ];
	}

	public function add( $service, ...$args ) {
		if ( \method_exists( $service, '__construct' ) ) {
			$class  = new \ReflectionClass( $service );
			$params = $class->getConstructor()->getParameters();

			foreach ( $params as $param ) {
				$name = $param->getClass()->name;

				if ( class_exists( $name ) ) {
					if ( ! isset( $this->services[ $name ] ) ) {
						$this->add( $name );
					}

					$args[] = $this->services[ $name ];
				}
			}
		}

		$this->services[ $service ] = $args ? new $service( ...$args ) : new $service( ...$args );

		if ( \method_exists( $this->services[ $service ], 'call' ) ) {
			$this->services[ $service ]->call();
		}
	}
}
