<?php

namespace MaiOptimizer;

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
				$class = $param->getClass();

				if ( ! \is_object( $class ) || ! \property_exists( $class, 'name' ) ) {
					continue;
				}

				if ( \class_exists( $class->name ) ) {
					if ( ! isset( $this->services[ $class->name ] ) ) {
						$this->add( $class->name );
					}

					$args[] = $this->services[ $class->name ];
				}
			}
		}

		$this->services[ $service ] = $args ? new $service( ...$args ) : new $service( ...$args );

		if ( \method_exists( $this->services[ $service ], 'call' ) ) {
			$this->services[ $service ]->call();
		}

		return $this->services[ $service ];
	}
}
