<?php

namespace MaiOptimizer\Modules;

/**
 * Compression Class
 *
 * @package      Genesis Starter
 * @author       Seo Themes
 */
class MinifyHtml extends AbstractModule {

	public function hooks() {
		add_action( 'get_header', [ $this, 'wp_html_compression_start' ] );
	}

	public function wp_html_compression_start() {
		ob_start( [ $this, 'wp_html_compression_finish' ] );
	}

	public function wp_html_compression_finish( $html ) {
		return new MinifyHtmlCompressor( $html );
	}
}
