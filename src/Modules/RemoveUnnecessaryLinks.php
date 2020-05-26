<?php

namespace MaiOptimizer\Modules;

/**
 * Class RemoveUnnecessaryLinks
 *
 * @package \OptimizeWP\Modules
 */
class RemoveUnnecessaryLinks extends AbstractModule {

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		\remove_action( 'wp_head', 'rsd_link' );
		\remove_action( 'wp_head', 'wlwmanifest_link' );
		\remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		\remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}
}
