<?php

namespace MaiOptimizer\Modules;

/**
 * Class CombineScripts
 *
 * @package \OptimizeWP\Modules
 */
class CombineInlineScripts extends AbstractModule {


	public function hooks() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'combine_scripts' ], 999 );
	}

	public function combine_scripts() {
		if ( is_customize_preview() ) {
			return;
		}

		global $wp_scripts;

		$combined_scripts = '';

		foreach ( $wp_scripts->queue as $handle ) {

			/**
			 * @var \_WP_Dependency $dependency
			 */
			$dependency = $wp_scripts->registered[ $handle ];

			if ( property_exists( $dependency, 'extra' ) ) {
				$extra = $dependency->extra;

				if ( isset( $extra['after'] ) ) {
					$combined_scripts .= \implode( '', $extra['after'] );

					$wp_scripts->dequeue( $handle );
				}
			}
		}

		include_once ABSPATH . 'wp-admin/includes/file.php';
		\WP_Filesystem();
		global $wp_filesystem;

		$dir  = WP_CONTENT_DIR . '/cache/' . $this->plugin->slug;
		$file = $dir . '/combined.min.js';

		if ( ! \is_dir( $dir ) ) {
			\wp_mkdir_p( $dir );
		}

		$cache = \get_option( $this->plugin->slug . '-cache', false );

		if ( ! file_exists( $file ) || ! $cache ) {
			$wp_filesystem->put_contents( $file, \JShrink\Minifier::minify( $combined_scripts ) );

			\update_option( $this->plugin->slug . '-cache', true );
		}

		\wp_enqueue_script(
			$this->plugin->slug . '-combined-scripts',
			content_url( 'cache/' . $this->plugin->slug . '/combined.min.js' )
		);
	}
}
