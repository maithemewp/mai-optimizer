<?php

namespace MaiOptimizer\Modules;

use MaiOptimizer\Helpers;

class CombineInlineStyles extends AbstractModule {


	public function hooks() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'combine_styles' ], 999 );
	}

	public function combine_styles() {
		if ( is_customize_preview() ) {
			return;
		}

		global $wp_styles;

		$custom_css      = wp_get_custom_css();
		$combined_styles = $custom_css ? strip_tags( $custom_css ) : '';

		\remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

		/**
		 * @var \_WP_Dependency $dependency
		 */
		foreach ( $wp_styles->queue as $handle ) {

			/**
			 * @var \_WP_Dependency $dependency
			 */
			$dependency = $wp_styles->registered[ $handle ];

			if ( property_exists( $dependency, 'extra' ) ) {
				$extra = $dependency->extra;

				if ( isset( $extra['after'] ) ) {
					$combined_styles .= \implode( '', $extra['after'] );

					$wp_styles->dequeue( $handle );
				}
			}
		}

		include_once ABSPATH . 'wp-admin/includes/file.php';
		\WP_Filesystem();
		global $wp_filesystem;

		$dir  = WP_CONTENT_DIR . '/cache/' . $this->plugin->slug;
		$file = $dir . '/combined.min.css';

		if ( ! \is_dir( $dir ) ) {
			\wp_mkdir_p( $dir );
		}

		$cache = \get_option( $this->plugin->slug . '-cache', false );

		if ( ! file_exists( $file ) || ! $cache ) {
			$wp_filesystem->put_contents( $file, Helpers::minify_css( $combined_styles ) );

			\update_option( $this->plugin->slug . '-cache', true );
		}

		\wp_enqueue_style(
			$this->plugin->slug . '-combined-styles',
			content_url( 'cache/'. $this->plugin->slug . '/combined.min.css' )
		);
	}
}
