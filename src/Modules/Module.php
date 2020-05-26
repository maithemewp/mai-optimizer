<?php

namespace OptimizeWP\Modules;

use OptimizeWP\Plugin;

/**
 * Class Module
 *
 * @package OptimizeWP\Modules
 */
abstract class Module {

	/**
	 * @var Plugin $plugin
	 */
	protected $plugin;

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	private function conditional() {
		if ( is_admin() || isset( $_GET['sitemap'] ) || in_array( $GLOBALS['pagenow'], [
				'wp-login.php',
				'wp-register.php',
			] ) ) {
			return false;
		}

		$option  = $this->format_module_name( \get_called_class() );
		$options = \get_option( $this->plugin->handle );

		return isset( $options[ $option ] ) ? $options[ $option ] : true;
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param Plugin $plugin
	 *
	 * @return void
	 */
	public function call( Plugin $plugin ) {
		$this->plugin = $plugin;

		if ( $this->conditional() ) {
			$this->hooks();
		}
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function hooks();

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $formatted
	 *
	 * @return array
	 */
	public function get_modules( $formatted = true ) {
		$modules      = [];
		$module_dir   = $this->plugin->dir . 'src/Modules/';
		$module_class = $module_dir . 'Module.php';
		$module_files = \glob( $module_dir . '*.php' );

		foreach ( $module_files as $module_file ) {
			if ( $module_file === $module_class ) {
				continue;
			}

			$module = \basename( $module_file, '.php' );

			$modules[] = $formatted ? $this->format_module_name( $module ) : $module;
		}

		return $modules;
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $class
	 *
	 * @return string
	 */
	public function format_module_name( $class ) {
		return \strtolower( \preg_replace(
			'/(?<!^)[A-Z]/',
			'-$0',
			$class
		) );
	}
}
