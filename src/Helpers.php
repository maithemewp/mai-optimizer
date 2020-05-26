<?php

namespace MaiOptimizer;

class Helpers {

	public static function string_contains( $needle, $haystack ) {
		return false !== strpos( $haystack, $needle );
	}

	public static function url_compare( $url, $rel ) {
		$url = trailingslashit( $url );
		$rel = trailingslashit( $rel );

		return ( ( strcasecmp( $url, $rel ) === 0 ) || self::root_relative_url( $url ) == $rel );
	}

	public static function root_relative_url( $input ) {
		if ( is_feed() ) {
			return $input;
		}

		$url = parse_url( $input );

		if ( ! isset( $url['host'] ) || ! isset( $url['path'] ) ) {
			return $input;
		}

		$site_url = parse_url( network_home_url() );  // falls back to home_url

		if ( ! isset( $url['scheme'] ) ) {
			$url['scheme'] = $site_url['scheme'];
		}

		$hosts_match   = $site_url['host'] === $url['host'];
		$schemes_match = $site_url['scheme'] === $url['scheme'];
		$ports_exist   = isset( $site_url['port'] ) && isset( $url['port'] );
		$ports_match   = ( $ports_exist ) ? $site_url['port'] === $url['port'] : true;

		if ( $hosts_match && $schemes_match && $ports_match ) {
			return wp_make_link_relative( $input );
		}

		return $input;
	}

	/**
	 * Quick and dirty way to mostly minify CSS.
	 *
	 * @since  0.1.0
	 *
	 * @author Gary Jones
	 *
	 * @param string $css CSS to minify.
	 *
	 * @return string
	 */
	public static function minify_css( $css ) {
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
		$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
		$css = preg_replace( '/;(?=\s*})/', '', $css );
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
		$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
		$css = preg_replace( '/0 0 0 0/', '0', $css );
		$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

		return trim( $css );
	}

	public function minify_js( $js ) {
		return $js;
	}

	/**
	 * Converts a string to different naming conventions.
	 *
	 * Camel:    myNameIsBond.
	 * Pascal:   MyNameIsBond.
	 * Snake:    my_name_is_bond.
	 * Ada:      My_Name_Is_Bond.
	 * Macro:    MY_NAME_IS_BOND.
	 * Kebab:    my-name-is-bond.
	 * Train:    My-Name-Is-Bond.
	 * Cobol:    MY-NAME-IS-BOND.
	 * Lower:    my name is bond.
	 * Upper:    MY NAME IS BOND.
	 * Title:    My Name Is Bond.
	 * Sentence: My name is bond.
	 * Dot:      my.name.is.bond.
	 *
	 * @since  0.3.0
	 *
	 * @author Lee Anthony https://seothemes.com
	 *
	 * @param string $string String to convert.
	 * @param string $case   Naming convention.
	 *
	 * @return string
	 */
	public static function convert_case( $string, $case = 'snake' ) {
		preg_match_all(
			'!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
			$string,
			$matches
		);

		$string     = implode( ' ', $matches[0] );
		$delimiters = 'sentence' === $case ? [ ' ', '-', '_' ] : [ ' ', '-', '_', '.' ];
		$lower      = trim( str_replace( $delimiters, $delimiters[0], strtolower( $string ) ), $delimiters[0] );
		$upper      = trim( ucwords( $lower ), $delimiters[0] );
		$pieces     = explode( $delimiters[0], $lower );

		$cases = [
			'camel'    => lcfirst( str_replace( ' ', '', $upper ) ),
			'pascal'   => str_replace( ' ', '', $upper ),
			'snake'    => strtolower( implode( '_', $pieces ) ),
			'ada'      => str_replace( ' ', '_', $upper ),
			'macro'    => strtoupper( implode( '_', $pieces ) ),
			'kebab'    => strtolower( implode( '-', $pieces ) ),
			'train'    => lcfirst( str_replace( ' ', '-', $upper ) ),
			'cobol'    => strtoupper( implode( '-', $pieces ) ),
			'lower'    => strtolower( $string ),
			'upper'    => strtoupper( $string ),
			'title'    => $upper,
			'sentence' => ucfirst( $lower ),
			'dot'      => strtolower( implode( '.', $pieces ) ),
		];

		return $cases[ \trim( $case ) ];
	}
}
