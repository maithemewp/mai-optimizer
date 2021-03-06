<?php

namespace MaiOptimizer\Modules;

class MinifyHtmlCompressor {

	/**
	 * CSS settings
	 *
	 * @var boolean
	 */
	protected $compress_css = true;
	/**
	 * JS settings
	 *
	 * @var boolean
	 */
	protected $compress_js = true;
	/**
	 * Comment settings
	 *
	 * @var boolean
	 */
	protected $info_comment = true;
	/**
	 * Remove comments
	 *
	 * @var boolean
	 */
	protected $remove_comments = true;
	/**
	 * HTML variable
	 *
	 * @var [type]
	 */
	protected $html;

	/**
	 * Constructor
	 *
	 * @param object $html HTML Object.
	 */
	public function __construct( $html ) {
		if ( ! empty( $html ) ) {
			$this->parse_html( $html );
		}
	}

	/**
	 * Parse HTML
	 *
	 * @param  string $html Built up HTML.
	 */
	public function parse_html( $html ) {
		$this->html = $this->minify_html( $html );
	}

	/**
	 * Convert to string
	 *
	 * @return string Convert to string.
	 */
	public function __toString() {
		return $this->html;
	}

	/**
	 * Minify HTML
	 *
	 * @param  string $html Current html.
	 *
	 * @return string $html Minified html.
	 */
	protected function minify_html( $html ) {
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all( $pattern, $html, $matches, PREG_SET_ORDER );
		$overriding = false;
		$raw_tag    = false;
		$strip      = false;
		$html       = '';

		foreach ( $matches as $token ) {
			$tag     = ( isset( $token['tag'] ) ) ? strtolower( $token['tag'] ) : null;
			$content = $token[0];

			if ( is_null( $tag ) ) {
				if ( ! empty( $token['script'] ) ) {
					$strip = $this->compress_js;

				} elseif ( ! empty( $token['style'] ) ) {
					$strip = $this->compress_css;

				} elseif ( '<!--wp-html-compression no compression-->' === $content ) {
					$overriding = ! $overriding;
					continue;

				} elseif ( $this->remove_comments ) {
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
			}

			if ( $strip ) {
				$content = $this->remove_white_space( $content );
			}

			$html .= $content;
		}

		return $html;
	}

	/**
	 * Remove White Space
	 *
	 * @param  string $str String containing HTML.
	 *
	 * @return string $str Stripped HTML.
	 */
	protected function remove_white_space( $str ) {
		$str = str_replace( "\n", '', $str );
		$str = str_replace( "\t", '', $str );
		$str = str_replace( "\r", '', $str );

		while ( stristr( $str, '  ' ) ) {
			$str = str_replace( '  ', ' ', $str );
		}

		return $str;
	}
}
