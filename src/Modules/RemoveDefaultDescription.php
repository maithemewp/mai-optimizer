<?php

namespace MaiOptimizer\Modules;

/**
 * Class RemoveDefaultDescription
 *
 * @package \MaiOptimizer\Modules
 */
class RemoveDefaultDescription extends AbstractModule {
	public function hooks() {
		add_filter( 'get_bloginfo_rss', [ $this, 'remove_default_description' ] );
	}

	public function remove_default_description( $bloginfo ) {
		$default_tagline = 'Just another WordPress site';

		return ( $bloginfo === $default_tagline ) ? '' : $bloginfo;
	}
}
