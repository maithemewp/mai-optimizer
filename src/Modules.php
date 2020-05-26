<?php

namespace MaiOptimizer;

use MaiOptimizer\Modules\AbstractModule;

class Modules {
	private $plugin;
	public $modules;

	public function __construct( Plugin $plugin ) {
		$this->plugin  = $plugin;
		$this->modules = $this->add_modules();
	}

	public function add_hooks() {
		\add_action( 'plugins_loaded', [ $this, 'do_modules' ] );
	}

	public function add_modules() {
		$modules = glob( $this->plugin->dir . 'src/Modules/*.php' );

		foreach ( $modules as $module ) {
			$name = __NAMESPACE__ . '\\Modules\\' . \basename( $module, '.php' );

			if ( \is_subclass_of( $name, AbstractModule::class ) ) {
				$this->modules[ $name ] = new $name();
			}
		}

		return $this->modules;
	}

	public function get_modules() {
		return $this->modules;
	}

	public function get_module( $name ) {
		return $this->modules[ $name ];
	}

	public function get_module_names() {
		$names = [];

		foreach ( $this->modules as $name => $object ) {
			$names[] = str_replace( __NAMESPACE__ . '\\Modules\\', '', $name );
		}

		return $names;
	}

	public function do_modules() {
		if ( is_admin() ) {
			return;
		}

		$options = \get_option( $this->plugin->slug, [] );

		foreach ( $this->get_module_names() as $name ) {
			if ( isset( $options[ $name ] ) && \sanitize_text_field( $options[ $name ] ) ) {

				/**
				 * @var AbstractModule $module
				 */
				$module = $this->modules[ __NAMESPACE__ . '\\Modules\\' . $name ];
				$module->call( $this->plugin );
			}
		}
	}
}
