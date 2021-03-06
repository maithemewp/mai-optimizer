<?php

namespace MaiOptimizer\Modules;

use MaiOptimizer\Helpers;

/**
 * Class RootRelativeURLs
 *
 * @package \OptimizeWP\Modules
 */
class RootRelativeUrls extends AbstractModule {

	public $root_rel_filters = [
		'bloginfo_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'wp_get_attachment_url',
		'the_content_more_link',
		'the_tags',
		'get_pagenum_link',
		'get_comment_link',
		'month_link',
		'day_link',
		'year_link',
		'term_link',
		'the_author_posts_link',
		'script_loader_src',
		'style_loader_src',
		'theme_file_uri',
		'parent_theme_file_uri',
	];

	public function hooks() {
		foreach ( (array) $this->root_rel_filters as $tag ) {
			add_filter( $tag, [ Helpers::class, 'root_relative_url' ] );
		}

		add_filter( 'wp_calculate_image_srcset', function ( $sources ) {
			foreach ( (array) $sources as $source => $src ) {
				$sources[ $source ]['url'] = Helpers::root_relative_url( $src['url'] );
			}

			return $sources;
		} );

		/**
		 * Compatibility with The SEO Framework
		 */
		add_action( 'the_seo_framework_do_before_output', function () {
			remove_filter( 'wp_get_attachment_url', Helpers::class . '\\root_relative_url' );
		} );

		add_action( 'the_seo_framework_do_after_output', function () {
			add_filter( 'wp_get_attachment_url', Helpers::class . '\\root_relative_url' );
		} );
	}

	/**
	 * Compare URL against relative URL
	 */
	public function url_compare( $url, $rel ) {
		$url = trailingslashit( $url );
		$rel = trailingslashit( $rel );

		return ( ( strcasecmp( $url, $rel ) === 0 ) || Helpers::root_relative_url( $url ) == $rel );
	}
}
