<?php

namespace OptimizeWP\Plugin;

class MinifyHTML {

	/**
	 * CSS settings
	 *
	 * @var boolean
	 */
	protected static $compress_css = true;

	/**
	 * JS settings
	 *
	 * @var boolean
	 */
	protected static $compress_js = true;

	/**
	 * Comment settings
	 *
	 * @var boolean
	 */
	protected static $info_comment = true;

	/**
	 * Remove comments
	 *
	 * @var boolean
	 */
	protected static $remove_comments = true;

	/**
	 * HTML variable
	 *
	 * @var [type]
	 */
	protected static $html;

	private $plugin;
	private $cache;

	/**
	 * Html constructor.
	 *
	 * @param $plugin
	 * @param $cache
	 */
	public function __construct( $plugin, $cache ) {
		$this->plugin = $plugin;
		$this->cache = $cache;
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'get_header', [ __CLASS__, 'start' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function start() {
		ob_start( [ __CLASS__, 'finish' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $html
	 *
	 * @return string
	 */
	public static function finish( $html ) {
		return self::minify_html( $html );
	}

	/**
	 * Minify HTML
	 *
	 * @param  string $html Current html.
	 *
	 * @return string $html Minified html.
	 */
	protected static function minify_html( $html ) {
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all( $pattern, $html, $matches, PREG_SET_ORDER );
		$overriding = false;
		$raw_tag    = false;

		// Variable reused for output.
		$html = '';
		foreach ( $matches as $token ) {
			$tag     = ( isset( $token['tag'] ) ) ? strtolower( $token['tag'] ) : null;
			$content = $token[0];
			if ( is_null( $tag ) ) {
				if ( ! empty( $token['script'] ) ) {
					$strip = self::$compress_js;
				} elseif ( ! empty( $token['style'] ) ) {
					$strip = self::$compress_css;
				} elseif ( '<!--wp-html-compression no compression-->' === $content ) {
					$overriding = ! $overriding;
					// Don't print the comment.
					continue;
				} elseif ( self::$remove_comments ) {
					if ( ! $overriding && 'textarea' !== $raw_tag ) {
						// Remove any HTML comments, except MSIE conditional comments.
						$content = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content );
					}
				}
			} else {
				if ( 'pre' === $tag || 'textarea' === $tag || 'script' === $tag ) {
					$raw_tag = $tag;
				} elseif ( '/pre' === $tag || '/textarea' === $tag || '/script' === $tag ) {
					$raw_tag = false;
				} else {
					if ( $raw_tag || $overriding ) {
						$strip = false;
					} else {
						$strip = true;
						// Remove any empty attributes, except action, alt, content, src.
						$content = preg_replace( '/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content );
						// Remove any space before the end of self-closing XHTML tags. JS excluded.
						$content = str_replace( ' />', '/>', $content );
					}
				}
			} // End if().
			if ( $strip ) {
				$content = self::remove_white_space( $content );
			}
			$html .= $content;
		} // End foreach().

		return $html;
	}

	/**
	 * Parse HTML
	 *
	 * @param  string $html Built up HTML.
	 */
	public static function parse_html( $html ) {
		self::$html = self::minify_html( $html );
	}

	/**
	 * Remove White Space
	 *
	 * @param  string $str String containing HTML.
	 *
	 * @return string $str Stripped HTML.
	 */
	protected static function remove_white_space( $str ) {
		$str = str_replace( "\n", '', $str );
		$str = str_replace( "\t", '', $str );
		$str = str_replace( "\r", '', $str );
		while ( stristr( $str, '  ' ) ) {
			$str = str_replace( '  ', ' ', $str );
		}

		return $str;
	}
}
