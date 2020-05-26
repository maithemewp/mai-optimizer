<?php

namespace MaiOptimizer\Modules;

/**
 * Class DisableRssFeeds
 *
 * @package \OptimizeWP\Modules
 */
class DisableRssFeeds extends AbstractModule {

	public function hooks() {
		\remove_action( 'wp_head', 'feed_links', 2 );
		\remove_action( 'wp_head', 'feed_links_extra', 3 );
		\add_action( 'do_feed', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_rdf', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_rss', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_rss2', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_atom', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_rss2_comments', [ $this, 'disable_feed' ], 1 );
		\add_action( 'do_feed_atom_comments', [ $this, 'disable_feed' ], 1 );
	}

	public function disable_feed() {
		\wp_die( __( 'No feed available, please visit the <a href="' . \esc_url( \home_url( '/' ) ) . '">homepage</a>!', 'optimizewp' ) );
	}
}
