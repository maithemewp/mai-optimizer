<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableSelfClosing
 *
 * @package \OptimizeWP\Modules
 */
class RemoveSelfClosingTags extends AbstractModule {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'get_avatar', [ $this, 'remove_self_closing_tags' ] );
		add_filter( 'comment_id_fields', [ $this, 'remove_self_closing_tags' ] );
		add_filter( 'post_thumbnail_html', [ $this, 'remove_self_closing_tags' ] );
	}

	/**
	 * Remove unnecessary self-closing tags
	 */
	function remove_self_closing_tags( $input ) {
		return str_replace( ' />', '>', $input );
	}
}
