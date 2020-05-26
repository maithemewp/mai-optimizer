<?php

namespace OptimizeWP\Modules;

/**
 * Class RemoveCoreWidgetStyles
 *
 * @package \OptimizeWP\Modules
 */
class RemoveWidgetInlineStyles extends Module {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\add_filter( 'use_default_gallery_style', '__return_false' );
		\add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}
}
