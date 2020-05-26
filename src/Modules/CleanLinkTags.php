<?php

namespace OptimizeWP\Modules;

/**
 * Class CleanLinkTags
 *
 * @package \OptimizeWP\Modules
 */
class CleanLinkTags extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\add_filter( 'script_loader_tag', [ $this, 'clean_script_tag' ] );
		\add_filter( 'style_loader_tag', [ $this, 'clean_style_tag' ] );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $input
	 *
	 * @return mixed
	 */
	function clean_script_tag( $input ) {
		$input = str_replace( "type='text/javascript' ", '', $input );

		return str_replace( "'", '"', $input );
	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $input
	 *
	 * @return string
	 */
	function clean_style_tag( $input ) {
		preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
		if ( empty( $matches[2] ) ) {
			return $input;
		}

		$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';

		return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
	}
}
